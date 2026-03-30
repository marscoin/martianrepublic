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
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

uses(Tests\TestCase::class)->beforeEach(function () {
    $schema = Schema::connection('sqlite');
    $schema->dropAllTables();

    $schema->create('users', function ($table) {
        $table->id();
        $table->string('fullname');
        $table->string('email')->unique();
        $table->string('password');
        $table->timestamps();
        $table->rememberToken();
    });

    $schema->create('sessions', function ($table) {
        $table->string('id')->primary();
        $table->foreignId('user_id')->nullable()->index();
        $table->string('ip_address', 45)->nullable();
        $table->text('user_agent')->nullable();
        $table->longText('payload');
        $table->integer('last_activity')->index();
    });

    $schema->create('profile', function ($table) {
        $table->integer('id', true);
        $table->integer('userid')->nullable()->unique();
        $table->integer('twofaset')->nullable();
        $table->string('twofakey', 500)->nullable();
        $table->integer('openchallenge')->nullable();
        $table->timestamps();
        $table->integer('wallet_open')->default(0);
        $table->integer('civic_wallet_open')->default(0);
        $table->integer('general_public')->nullable();
        $table->integer('endorse_cnt')->nullable();
        $table->integer('citizen')->nullable();
        $table->integer('has_application')->nullable()->default(0);
        $table->integer('signed_eula')->nullable()->default(0);
    });

    $schema->create('proposals', function ($table) {
        $table->integer('id', true);
        $table->integer('user_id')->nullable();
        $table->string('title', 500)->nullable();
        $table->text('description')->nullable();
        $table->string('category', 100)->nullable();
        $table->string('status', 50)->default('submitted');
        $table->string('tier', 50)->nullable();
        $table->integer('active')->default(1);
        $table->integer('duration')->nullable();
        $table->integer('discussion')->nullable();
        $table->string('txid', 200)->nullable();
        $table->string('ipfs_hash', 200)->nullable();
        $table->timestamps();
        $table->timestamp('mined')->nullable();
    });

    $schema->create('votes', function ($table) {
        $table->integer('id', true);
        $table->integer('proposal_id')->nullable();
        $table->string('vote', 5)->nullable();
        $table->string('txid', 200)->nullable();
        $table->timestamps();
    });

    $schema->create('ballots', function ($table) {
        $table->id();
        $table->integer('userid');
        $table->integer('proposalid');
        $table->string('btxid', 200)->nullable();
        $table->string('status', 50)->default('requested');
        $table->text('encrypted_key')->nullable();
        $table->text('encryption_iv')->nullable();
        $table->string('ballot_txid', 200)->nullable();
        $table->timestamp('confirmed_at')->nullable();
        $table->boolean('notified')->default(false);
        $table->string('hidden_target', 200)->nullable();
        $table->timestamps();
    });

    $schema->create('civic_wallet', function ($table) {
        $table->integer('id', true);
        $table->integer('user_id')->nullable();
        $table->string('wallet_type', 50)->nullable();
        $table->text('backup')->nullable();
        $table->text('encrypted_seed')->nullable();
        $table->string('public_addr', 100)->nullable()->unique();
        $table->timestamp('opened_at')->nullable();
        $table->timestamps();
    });
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
