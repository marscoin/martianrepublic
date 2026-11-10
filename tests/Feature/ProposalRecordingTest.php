<?php

/**
 * Recording a proposal after it has been notarized on chain.
 *
 * The new-proposal page sends the IPFS gateway link. A check added on
 * 2026-03-31 accepted only a bare content ID, so every submission after that
 * date was paid for, notarized, and then refused.
 */

use App\Http\Controllers\Wallet\ApiController;
use App\Includes\AppHelper;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\CreatesTestDatabase;
use Tests\TestCase;

const PROPOSAL_CID = 'Qmaow2cUCwQoYmKM37cgXLCFAtJFXz7h2xSrKCQDt6nrxw';

uses(TestCase::class, CreatesTestDatabase::class)->beforeEach(function () {
    $this->createCoreTables();
    $this->createProposalTables();
    $this->createFeedTable();
    $this->createForumTables();
    $schema = Schema::connection('sqlite');
    $schema->table('proposals', function ($table) {
        foreach (['screening_ends_at', 'voting_ends_at', 'timelock_ends_at', 'enacted_at', 'sunset_at'] as $column) {
            $table->timestamp($column)->nullable();
        }
        foreach (['author', 'public_address', 'git_hash'] as $column) {
            $table->string($column, 200)->nullable();
        }
        foreach (['participation', 'threshold', 'expiration'] as $column) {
            $table->integer($column)->nullable();
        }
    });
    // The forum tables as they are in production.
    $schema->dropIfExists('forum_posts');
    $schema->dropIfExists('forum_threads');
    $schema->create('forum_posts', function ($table) {
        $table->increments('id');
        $table->unsignedInteger('thread_id');
        $table->unsignedInteger('author_id');
        $table->text('content');
        $table->integer('post_id')->nullable();
        $table->integer('sequence')->nullable();
        $table->text('authorName')->nullable();
        $table->timestamps();
        $table->softDeletes();
    });
    $schema->create('forum_threads', function ($table) {
        $table->increments('id');
        $table->unsignedInteger('category_id');
        $table->unsignedInteger('author_id');
        $table->string('title');
        $table->boolean('pinned')->nullable();
        $table->boolean('locked')->nullable();
        $table->integer('first_post_id')->nullable();
        $table->integer('last_post_id')->nullable();
        $table->integer('reply_count')->nullable();
        $table->integer('proposal_id')->nullable();
        $table->timestamps();
        $table->softDeletes();
    });
    if (! $schema->hasTable('citizen')) {
        $schema->create('citizen', function ($table) {
            $table->integer('id', true);
            $table->integer('userid')->nullable();
            $table->string('firstname', 100)->nullable();
            $table->string('lastname', 100)->nullable();
        });
    }
});

function recordProposal(array $overrides = []): Illuminate\Http\JsonResponse
{
    $document = json_encode(['data' => ['title' => 'Open the greenhouse on Sol 3', 'description' => 'A test.', 'category' => 'signal']]);
    $request = Request::create('/api/cacheproposal', 'POST', array_merge([
        'type' => 'PR',
        'txid' => str_repeat('ab', 32),
        'message' => $document,
        'embedded_link' => 'https://ipfs.marscoin.org/ipfs/'.PROPOSAL_CID,
        'address' => 'MU9wWe1Zj6jkpSphWNXPYmn5yum6DtYC8x',
    ], $overrides));

    return app()->make(ApiController::class)->cacheproposal($request);
}

function signedInCitizen(): User
{
    $user = User::forceCreate(['fullname' => 'Test Citizen', 'email' => 'citizen@example.org', 'password' => 'x']);
    test()->actingAs($user);

    return $user;
}

test('a content ID is read from a bare ID or the gateway link, and nothing else', function () {
    expect(AppHelper::cidFromLink(PROPOSAL_CID))->toBe(PROPOSAL_CID);
    expect(AppHelper::cidFromLink('https://ipfs.marscoin.org/ipfs/'.PROPOSAL_CID))->toBe(PROPOSAL_CID);
    expect(AppHelper::cidFromLink('  https://ipfs.marscoin.org/ipfs/'.PROPOSAL_CID.' '))->toBe(PROPOSAL_CID);
    expect(AppHelper::cidFromLink('https://evil.example/ipfs/'.PROPOSAL_CID))->toBeNull();
    expect(AppHelper::cidFromLink('https://ipfs.marscoin.org/ipfs/'.PROPOSAL_CID.'/../../etc'))->toBeNull();
    expect(AppHelper::cidFromLink('https://ipfs.marscoin.org/ipfs/'))->toBeNull();
    expect(AppHelper::cidFromLink(''))->toBeNull();
});

test('a proposal sent with the gateway link, as the page sends it, is recorded with its lifecycle', function () {
    signedInCitizen();

    $response = recordProposal();
    expect($response->getStatusCode())->toBe(200);

    $proposal = Proposal::find($response->getData()->Proposal);
    expect($proposal->ipfs_hash)->toBe('https://ipfs.marscoin.org/ipfs/'.PROPOSAL_CID);
    expect($proposal->status)->toBe('screening');
    expect($proposal->tier)->toBe('signal');
    // Three sols of screening, then seven of voting.
    expect(now()->diffInDays($proposal->screening_ends_at, false))->toBeGreaterThan(2.9)->toBeLessThan(3.1);
    expect(now()->diffInDays($proposal->voting_ends_at, false))->toBeGreaterThan(9.9)->toBeLessThan(10.1);
    expect((int) $proposal->discussion)->toBe((int) $response->getData()->Discussion);
});

test('a bare content ID is stored in the same link form', function () {
    signedInCitizen();
    $response = recordProposal(['embedded_link' => PROPOSAL_CID]);
    expect(Proposal::find($response->getData()->Proposal)->ipfs_hash)->toBe('https://ipfs.marscoin.org/ipfs/'.PROPOSAL_CID);
});

test('recording the same proposal twice returns the first record', function () {
    signedInCitizen();
    $first = recordProposal()->getData();
    $again = recordProposal()->getData();

    expect($again->Proposal)->toBe($first->Proposal);
    expect(Proposal::count())->toBe(1);
    expect(DB::table('forum_threads')->count())->toBe(1);
});

test('a proposal the chain tracker recorded first is not duplicated', function () {
    signedInCitizen();
    $tracked = Proposal::forceCreate([
        'title' => 'Open the greenhouse on Sol 3',
        'status' => 'screening',
        'txid' => str_repeat('ab', 32),
        'ipfs_hash' => 'https://ipfs.marscoin.org/ipfs/'.PROPOSAL_CID,
        'discussion' => 7,
    ]);

    $response = recordProposal()->getData();
    expect($response->Proposal)->toBe($tracked->id);
    expect($response->Discussion)->toBe(7);
    expect(Proposal::count())->toBe(1);
});

test('links to other hosts, malformed IDs and malformed transaction IDs are refused', function () {
    signedInCitizen();
    expect(recordProposal(['embedded_link' => 'https://evil.example/ipfs/'.PROPOSAL_CID])->getStatusCode())->toBe(400);
    expect(recordProposal(['embedded_link' => 'not-a-cid'])->getStatusCode())->toBe(400);
    expect(recordProposal(['txid' => 'xyz'])->getStatusCode())->toBe(400);
    expect(recordProposal(['message' => 'not json'])->getStatusCode())->toBe(400);
    expect(Proposal::count())->toBe(0);
});
