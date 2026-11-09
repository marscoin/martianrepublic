<?php

/**
 * Proposal results: quorum, thresholds, lifecycle after the vote, and the
 * production timing of governance tiers.
 */

use App\Includes\GovernanceTiers;
use App\Models\Proposal;
use App\Services\ProposalService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\CreatesTestDatabase;
use Tests\TestCase;

uses(TestCase::class, CreatesTestDatabase::class)->beforeEach(function () {
    $this->createCoreTables();
    $this->createProposalTables();
    $this->createFeedTable();
    $this->createForumTables();
    Schema::connection('sqlite')->table('proposals', function ($table) {
        $table->timestamp('screening_ends_at')->nullable();
        $table->timestamp('voting_ends_at')->nullable();
        $table->timestamp('timelock_ends_at')->nullable();
        $table->timestamp('enacted_at')->nullable();
        $table->timestamp('sunset_at')->nullable();
        $table->timestamp('amended_at')->nullable();
        $table->boolean('voting_extended')->default(false);
        $table->string('author', 200)->nullable();
    });
});

function citizens(int $count): void
{
    for ($userid = 1; $userid <= $count; $userid++) {
        DB::table('feed')->insert(['userid' => $userid, 'tag' => 'CT', 'address' => "M{$userid}"]);
    }
}

function proposalWithVotes(string $tier, int $yes, int $no, int $abstain = 0, array $attributes = []): Proposal
{
    $proposal = Proposal::forceCreate(array_merge([
        'title' => "A {$tier} proposal",
        'description' => 'Test',
        'tier' => $tier,
        'status' => 'voting',
        'active' => 1,
        'screening_ends_at' => now()->subDays(20),
        'voting_ends_at' => now()->subMinute(),
    ], $attributes));
    $n = 0;
    foreach (['Y' => $yes, 'N' => $no, 'A' => $abstain] as $vote => $count) {
        for ($i = 0; $i < $count; $i++) {
            DB::table('votes')->insert(['proposal_id' => $proposal->id, 'vote' => $vote, 'txid' => "tx{$proposal->id}-".$n++]);
        }
    }

    return $proposal;
}

test('test mode is off, so a sol is a day', function () {
    expect(GovernanceTiers::TEST_MODE)->toBeFalse();

    $start = Carbon::parse('2026-11-10 00:00:00');
    $times = GovernanceTiers::calculateTimestamps('operational', $start);

    expect($times['screening_ends_at']->toDateTimeString())->toBe('2026-11-13 00:00:00');
    expect($times['voting_ends_at']->toDateTimeString())->toBe('2026-11-27 00:00:00');
    expect($times['timelock_ends_at']->toDateTimeString())->toBe('2026-11-30 00:00:00');
});

test('a proposal passes with quorum and threshold met', function () {
    citizens(20);
    // Operational: quorum 25% of 20 = 5 voters, threshold 60%.
    $proposal = proposalWithVotes('operational', 4, 2, 0, ['timelock_ends_at' => now()->addDays(3)]);

    $tally = (new ProposalService)->tallyVotes($proposal->id);
    expect($tally)->toMatchArray(['yays' => 4, 'nays' => 2, 'turnout' => 6, 'quorum_required' => 5, 'quorum_met' => true, 'passed' => true]);
    expect($tally['yay_percent'])->toBe(66.67);

    (new ProposalService)->syncPhases();
    $proposal->refresh();
    expect($proposal->status)->toBe('passed');
    expect((int) $proposal->active)->toBe(0);
    expect($proposal->enacted_at)->not->toBeNull();
    // Passed, but not yet in force.
    expect(GovernanceTiers::currentPhase($proposal))->toBe('timelock');
});

test('a proposal is rejected without quorum, however lopsided the vote', function () {
    citizens(20);
    $proposal = proposalWithVotes('operational', 4, 0);

    $tally = (new ProposalService)->tallyVotes($proposal->id);
    expect($tally['yay_percent'])->toBe(100.0);
    expect($tally['quorum_met'])->toBeFalse();
    expect($tally['passed'])->toBeFalse();

    (new ProposalService)->syncPhases();
    expect($proposal->refresh()->status)->toBe('rejected');
    expect($proposal->enacted_at)->toBeNull();
    expect(GovernanceTiers::currentPhase($proposal))->toBe('rejected');
});

test('abstentions count toward quorum but not toward the threshold', function () {
    citizens(20);
    // 3 yes, 1 no, 1 abstain: turnout 5 meets quorum; 75% of yes+no passes 60%.
    $proposal = proposalWithVotes('operational', 3, 1, 1);
    $tally = (new ProposalService)->tallyVotes($proposal->id);
    expect($tally)->toMatchArray(['abstains' => 1, 'total' => 4, 'turnout' => 5, 'quorum_met' => true, 'passed' => true]);
    expect($tally['yay_percent'])->toBe(75.0);

    // Abstentions alone reach quorum but decide nothing.
    $silent = proposalWithVotes('operational', 0, 0, 6);
    expect((new ProposalService)->tallyVotes($silent->id)['passed'])->toBeFalse();
});

test('a proposal is rejected below its tier threshold', function () {
    citizens(10);
    // Constitutional: quorum 50% of 10 = 5, threshold 75%.
    $proposal = proposalWithVotes('constitutional', 5, 2);
    $tally = (new ProposalService)->tallyVotes($proposal->id);
    expect($tally['quorum_met'])->toBeTrue();
    expect($tally['yay_percent'])->toBe(71.43);
    expect($tally['passed'])->toBeFalse();

    (new ProposalService)->syncPhases();
    expect($proposal->refresh()->status)->toBe('rejected');
});

test('nothing is decided while voting is open, and a result is decided once', function () {
    citizens(10);
    $open = proposalWithVotes('signal', 3, 0, 0, ['voting_ends_at' => now()->addDay()]);
    (new ProposalService)->syncPhases();
    expect($open->refresh()->status)->toBe('voting');
    expect(GovernanceTiers::currentPhase($open))->toBe('voting');

    $closed = proposalWithVotes('signal', 3, 0);
    (new ProposalService)->syncPhases();
    expect($closed->refresh()->status)->toBe('passed');
    // Votes recorded afterwards cannot change it.
    DB::table('votes')->insert(['proposal_id' => $closed->id, 'vote' => 'N', 'txid' => 'late1']);
    DB::table('votes')->insert(['proposal_id' => $closed->id, 'vote' => 'N', 'txid' => 'late2']);
    DB::table('votes')->insert(['proposal_id' => $closed->id, 'vote' => 'N', 'txid' => 'late3']);
    DB::table('votes')->insert(['proposal_id' => $closed->id, 'vote' => 'N', 'txid' => 'late4']);
    (new ProposalService)->syncPhases();
    expect($closed->refresh()->status)->toBe('passed');
});

test('a passed binding proposal takes effect after its timelock and later reaches sunset', function () {
    citizens(8);
    $proposal = proposalWithVotes('operational', 4, 0, 0, [
        'voting_ends_at' => now()->subDays(5),
        'timelock_ends_at' => now()->subDays(2),
        'sunset_at' => now()->addDays(600),
    ]);
    (new ProposalService)->syncPhases();
    $proposal->refresh();
    expect($proposal->status)->toBe('passed');
    expect(GovernanceTiers::currentPhase($proposal))->toBe('active');

    $proposal->sunset_at = now()->subDay();
    $proposal->save();
    (new ProposalService)->syncPhases();
    expect($proposal->refresh()->status)->toBe('sunset');
});

test('a passed signal proposal simply reads as passed', function () {
    citizens(10);
    $proposal = proposalWithVotes('signal', 2, 1);
    (new ProposalService)->syncPhases();
    expect(GovernanceTiers::currentPhase($proposal->refresh()))->toBe('passed');
});

test('quorum always needs at least one voter', function () {
    // No citizens registered yet: an empty vote still cannot pass.
    $proposal = proposalWithVotes('signal', 0, 0);
    $tally = (new ProposalService)->tallyVotes($proposal->id);
    expect($tally['quorum_required'])->toBe(1);
    expect($tally['passed'])->toBeFalse();
});

test('the public API reports correct percentages when one side has no votes', function () {
    citizens(4);
    $proposal = proposalWithVotes('signal', 2, 0, 0, ['voting_ends_at' => now()->addDay()]);

    $list = $this->getJson('/api/congress/proposals')->assertOk()->json('data.0');
    expect((float) $list['yay_percent'])->toBe(100.0);
    expect((float) $list['nay_percent'])->toBe(0.0);

    $votes = $this->getJson("/api/congress/proposals/{$proposal->id}/votes")->assertOk()->json();
    expect($votes)->toMatchArray(['yays' => 2, 'nays' => 0, 'abstains' => 0, 'turnout' => 2, 'quorum_required' => 1, 'quorum_met' => true, 'passing' => true]);
});
