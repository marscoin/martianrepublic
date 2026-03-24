<?php

use App\Models\User;
use App\Models\Profile;
use App\Models\CivicWallet;
use App\Models\Citizen;
use Illuminate\Support\Facades\Hash;

uses(Tests\TestCase::class)->beforeEach(function () {
    // Build all tables directly to avoid SQLite index name collision issues
    $schema = \Illuminate\Support\Facades\Schema::connection('sqlite');

    $schema->dropAllTables();

    // Users
    $schema->create('users', function ($table) {
        $table->id();
        $table->string('fullname');
        $table->string('email')->unique();
        $table->string('password');
        $table->timestamps();
        $table->rememberToken();
    });

    // Sessions
    $schema->create('sessions', function ($table) {
        $table->string('id')->primary();
        $table->foreignId('user_id')->nullable()->index();
        $table->string('ip_address', 45)->nullable();
        $table->text('user_agent')->nullable();
        $table->longText('payload');
        $table->integer('last_activity')->index();
    });

    // Profile
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

    // Civic Wallet
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

    // Citizen
    $schema->create('citizen', function ($table) {
        $table->integer('id', true);
        $table->integer('userid')->nullable();
        $table->string('firstname', 100)->nullable();
        $table->string('lastname', 100)->nullable();
        $table->string('displayname', 200)->nullable();
        $table->text('shortbio')->nullable();
        $table->string('avatar_link', 500)->nullable();
        $table->string('liveness_link', 500)->nullable();
        $table->string('public_address', 100)->nullable();
        $table->timestamps();
    });

    // Proposals
    $schema->create('proposals', function ($table) {
        $table->integer('id', true);
        $table->integer('user_id')->nullable();
        $table->string('title', 500)->nullable();
        $table->text('description')->nullable();
        $table->string('category', 100)->nullable();
        $table->string('status', 50)->default('submitted');
        $table->integer('active')->default(1);
        $table->integer('duration')->nullable();
        $table->integer('discussion')->nullable();
        $table->string('txid', 200)->nullable();
        $table->string('ipfs_hash', 200)->nullable();
        $table->timestamps();
        $table->timestamp('mined')->nullable();
    });

    // Votes
    $schema->create('votes', function ($table) {
        $table->integer('id', true);
        $table->integer('proposal_id')->nullable();
        $table->string('vote', 5)->nullable();
        $table->string('txid', 200)->nullable();
        $table->timestamps();
    });

    // Publications
    $schema->create('publications', function ($table) {
        $table->integer('id', true);
        $table->integer('userid')->nullable();
        $table->string('title', 500)->nullable();
        $table->text('content')->nullable();
        $table->string('ipfs_hash', 200)->nullable();
        $table->timestamps();
    });

    // Inventory Items
    $schema->create('inventory_items', function ($table) {
        $table->id();
        $table->integer('userid');
        $table->string('name');
        $table->text('description')->nullable();
        $table->string('category');
        $table->integer('quantity')->default(1);
        $table->string('unit')->nullable();
        $table->string('location')->nullable();
        $table->string('condition');
        $table->string('ipfs_hash')->nullable();
        $table->string('notarization')->nullable();
        $table->timestamp('notarized_at')->nullable();
        $table->string('image_path')->nullable();
        $table->timestamps();
        $table->softDeletes();
    });

    // Feed (needed for citizen registry queries)
    $schema->create('feed', function ($table) {
        $table->integer('id', true);
        $table->integer('userid')->nullable();
        $table->string('address', 100)->nullable();
        $table->string('tag', 10)->nullable();
        $table->text('message')->nullable();
        $table->string('txid', 200)->nullable();
        $table->string('embedded_link', 500)->nullable();
        $table->string('ipfs_hash', 200)->nullable();
        $table->integer('blockid')->nullable();
        $table->timestamp('mined')->nullable();
        $table->timestamps();
    });
});

// ============================================================
// Public Pages (no auth required)
// ============================================================

test('homepage loads', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
});

test('login page loads', function () {
    $response = $this->get('/login');
    $response->assertStatus(200);
});

test('signup page loads', function () {
    $response = $this->get('/signup');
    $response->assertStatus(200);
});

test('privacy page loads', function () {
    $response = $this->get('/privacy');
    $response->assertStatus(200);
});

test('tos page loads', function () {
    $response = $this->get('/tos');
    $response->assertStatus(200);
});

test('support page loads', function () {
    $response = $this->get('/support');
    $response->assertStatus(200);
});

// ============================================================
// Public pages that were previously redirecting to login
// ============================================================

test('citizen registry is publicly accessible', function () {
    $response = $this->get('/citizen/all');
    $response->assertStatus(200);
});

test('congress dashboard is publicly accessible', function () {
    $response = $this->get('/congress/all');
    $response->assertStatus(200);
});

test('inventory page is publicly accessible', function () {
    $response = $this->get('/inventory/all');
    $response->assertStatus(200);
});

test('logbook page is publicly accessible', function () {
    $response = $this->get('/logbook/all');
    $response->assertStatus(200);
});

test('map page is publicly accessible', function () {
    $response = $this->get('/map/all');
    $response->assertStatus(200);
});

// ============================================================
// Auth-protected pages redirect when not logged in
// ============================================================

test('wallet dashboard requires auth', function () {
    $response = $this->get('/wallet/dashboard');
    $response->assertRedirect('/login');
});

test('congress voting requires auth', function () {
    $response = $this->get('/congress/voting');
    $response->assertRedirect('/login');
});

// ============================================================
// Authenticated user flows
// ============================================================

test('authenticated user can view citizen registry', function () {
    $user = User::create([
        'fullname' => 'Test User',
        'email' => 'test@test.com',
        'password' => Hash::make('password'),
    ]);

    Profile::create([
        'userid' => $user->id,
        'twofakey' => 'test-dummy-value',
        'twofaset' => 1,
        'openchallenge' => 0,
        'wallet_open' => 0,
        'civic_wallet_open' => 1,
        'citizen' => 0,
        'general_public' => 1,
        'has_application' => 0,
        'signed_eula' => 0,
        'endorse_cnt' => 0,
    ]);

    CivicWallet::create([
        'user_id' => $user->id,
        'wallet_type' => 'civic',
        'public_addr' => 'MTestAddress123456789012345678',
        'opened_at' => now(),
    ]);

    Citizen::create([
        'userid' => $user->id,
        'firstname' => 'Test',
        'lastname' => 'User',
        'displayname' => 'Test User',
        'shortbio' => 'Test account',
        'public_address' => 'MTestAddress123456789012345678',
    ]);

    $response = $this->actingAs($user)->get('/citizen/all');
    $response->assertStatus(200);
});

test('authenticated user can view congress dashboard', function () {
    $user = User::create([
        'fullname' => 'Test User',
        'email' => 'test@test.com',
        'password' => Hash::make('password'),
    ]);

    Profile::create([
        'userid' => $user->id,
        'twofakey' => 'test-dummy-value',
        'twofaset' => 1,
        'openchallenge' => 0,
        'wallet_open' => 0,
        'civic_wallet_open' => 1,
        'citizen' => 0,
        'general_public' => 1,
        'has_application' => 0,
        'signed_eula' => 0,
        'endorse_cnt' => 0,
    ]);

    $response = $this->actingAs($user)->get('/congress/all');
    $response->assertStatus(200);
});
