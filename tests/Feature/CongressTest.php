<?php

/**
 * Tests for Congress models, ballot system, and proposal handling.
 * Uses direct controller testing (like WalletFlowTest) to avoid
 * RouteServiceProvider namespace prefix issues in test environment.
 */

use App\Models\User;
use App\Models\Profile;
use App\Models\Proposals;
use App\Models\Ballots;
use App\Http\Controllers\Congress\CongressController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

uses(Tests\TestCase::class, Tests\CreatesTestDatabase::class)->beforeEach(function () {
    $this->createCoreTables();
    $this->createProposalTables();
    $this->createBallotTable();
    $this->createWalletTables();
});

test('Proposals model has correct table and casts', function () {
    $proposal = new Proposals();
    expect($proposal->getTable())->toBe('proposals');
    expect($proposal->getCasts())->toHaveKey('created_at');
});

test('ballot model relations resolve correctly', function () {
    $user = User::create([
        'fullname' => 'Test Citizen',
        'email' => 'citizen@test.mars',
        'password' => Hash::make('password'),
    ]);

    $proposal = Proposals::forceCreate([
        'user_id' => $user->id,
        'title' => 'Ballot Test Proposal',
        'description' => 'Testing ballot relations.',
        'status' => 'submitted',
    ]);

    $ballot = Ballots::create([
        'userid' => $user->id,
        'proposalid' => $proposal->id,
        'status' => 'received',
        'encrypted_key' => 'test_key_data',
        'confirmed_at' => now(),
    ]);

    expect($ballot->proposal)->not->toBeNull();
    expect($ballot->proposal->title)->toBe('Ballot Test Proposal');
    expect($ballot->user)->not->toBeNull();
    expect($ballot->user->fullname)->toBe('Test Citizen');
});

test('Ballots::pendingForUser returns confirmed unvoted ballots', function () {
    $user = User::create([
        'fullname' => 'Voter',
        'email' => 'voter@test.mars',
        'password' => Hash::make('password'),
    ]);

    $proposal = Proposals::forceCreate([
        'user_id' => $user->id,
        'title' => 'Pending Ballot Test',
        'description' => 'Testing pending.',
        'status' => 'submitted',
    ]);

    // Pending ballot
    Ballots::create([
        'userid' => $user->id,
        'proposalid' => $proposal->id,
        'status' => 'received',
        'encrypted_key' => 'key1',
        'confirmed_at' => now(),
    ]);

    // Used ballot (should NOT appear)
    Ballots::create([
        'userid' => $user->id,
        'proposalid' => $proposal->id,
        'status' => 'used',
        'encrypted_key' => 'key2',
        'confirmed_at' => now(),
    ]);

    $pending = Ballots::pendingForUser($user->id);
    expect($pending)->toHaveCount(1);
    expect($pending->first()->status)->toBe('received');
});

test('Ballots::pendingForUser excludes unconfirmed ballots', function () {
    $user = User::create([
        'fullname' => 'Unconfirmed',
        'email' => 'unconfirmed@test.mars',
        'password' => Hash::make('password'),
    ]);

    $proposal = Proposals::forceCreate([
        'user_id' => $user->id,
        'title' => 'Unconfirmed Test',
        'description' => 'Test',
        'status' => 'submitted',
    ]);

    // Unconfirmed ballot (no confirmed_at)
    Ballots::create([
        'userid' => $user->id,
        'proposalid' => $proposal->id,
        'status' => 'received',
        'encrypted_key' => 'key1',
        'confirmed_at' => null,
    ]);

    $pending = Ballots::pendingForUser($user->id);
    expect($pending)->toHaveCount(0);
});

test('CongressController class exists and has expected methods', function () {
    expect(class_exists(CongressController::class))->toBeTrue();

    $reflection = new ReflectionClass(CongressController::class);
    expect($reflection->hasMethod('showAll'))->toBeTrue();
    expect($reflection->hasMethod('proposal'))->toBeTrue();
    expect($reflection->hasMethod('showVoting'))->toBeTrue();
    expect($reflection->hasMethod('newProposal'))->toBeTrue();
    expect($reflection->hasMethod('acquireBallot'))->toBeTrue();
    expect($reflection->hasMethod('pendingBallots'))->toBeTrue();
    expect($reflection->hasMethod('backupBallotKey'))->toBeTrue();
    expect($reflection->hasMethod('confirmBallot'))->toBeTrue();
});

test('proposal status transitions are valid values', function () {
    $validStatuses = ['submitted', 'screening', 'active', 'passed', 'failed', 'expired', 'withdrawn'];

    $proposal = Proposals::forceCreate([
        'title' => 'Status Test',
        'description' => 'Test',
        'status' => 'submitted',
    ]);

    expect($validStatuses)->toContain($proposal->status);
});
