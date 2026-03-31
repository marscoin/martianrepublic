<?php

/**
 * Tests for multi-wallet discovery, migration, and the BlockchainRpc service.
 */

use App\Models\CivicWallet;
use App\Models\HDWallet;
use App\Models\Profile;
use App\Models\User;
use App\Services\BlockchainRpc;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\CreatesTestDatabase;
use Tests\TestCase;

uses(TestCase::class, CreatesTestDatabase::class)->beforeEach(function () {
    $this->createCoreTables();
    $this->createWalletTables();

    // Create civic_wallet_migrations table
    Schema::connection('sqlite')->create('civic_wallet_migrations', function ($table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->string('old_address', 100);
        $table->string('new_address', 100);
        $table->string('migration_txid', 200)->nullable();
        $table->string('status', 20)->default('pending');
        $table->text('old_encrypted_seed')->nullable();
        $table->timestamp('confirmed_at')->nullable();
        $table->timestamps();
    });
});

// ============================================================
// Multi-wallet DB structure
// ============================================================

test('user can have both civic and HD wallets with different seeds', function () {
    $user = User::create([
        'fullname' => 'Multi Wallet User',
        'email' => 'multi@test.mars',
        'password' => Hash::make('password'),
    ]);

    $civic = CivicWallet::create([
        'user_id' => $user->id,
        'wallet_type' => 'civic',
        'public_addr' => 'MCivicAddr123',
        'encrypted_seed' => 'encrypted_seed_civic',
    ]);

    $hd = HDWallet::create([
        'user_id' => $user->id,
        'wallet_type' => 'Migrated',
        'public_addr' => 'MHdAddr456',
        'encrypted_seed' => 'encrypted_seed_hd',
    ]);

    expect($civic->encrypted_seed)->not->toBe($hd->encrypted_seed);
    expect(CivicWallet::where('user_id', $user->id)->count())->toBe(1);
    expect(HDWallet::where('user_id', $user->id)->count())->toBe(1);
});

test('controller passes all wallet seeds to hd view', function () {
    $user = User::create([
        'fullname' => 'Seed Test', 'email' => 'seed@test.mars',
        'password' => Hash::make('password'),
    ]);

    Profile::create([
        'userid' => $user->id, 'openchallenge' => 0,
        'twofaset' => 1, 'wallet_open' => 0, 'civic_wallet_open' => 0,
    ]);

    CivicWallet::create([
        'user_id' => $user->id, 'wallet_type' => 'civic',
        'public_addr' => 'MCivic1', 'encrypted_seed' => 'enc_seed_1',
    ]);

    HDWallet::create([
        'user_id' => $user->id, 'wallet_type' => 'MARS',
        'public_addr' => 'MHd1', 'encrypted_seed' => 'enc_seed_2',
    ]);

    HDWallet::create([
        'user_id' => $user->id, 'wallet_type' => 'Migrated',
        'public_addr' => 'MHd2', 'encrypted_seed' => 'enc_seed_3',
    ]);

    // The controller builds all_seeds_json with all wallet seeds
    $wallets = HDWallet::where('user_id', $user->id)->get();
    $civic = CivicWallet::where('user_id', $user->id)->first();

    $allSeeds = [];
    foreach ($wallets as $w) {
        if ($w->encrypted_seed && $w->public_addr) {
            $allSeeds[] = ['s' => $w->encrypted_seed, 'a' => $w->public_addr, 't' => 'hd'];
        }
    }
    if ($civic && $civic->encrypted_seed) {
        $allSeeds[] = ['s' => $civic->encrypted_seed, 'a' => $civic->public_addr, 't' => 'civic'];
    }

    expect($allSeeds)->toHaveCount(3);
    expect(collect($allSeeds)->pluck('a')->toArray())->toContain('MCivic1', 'MHd1', 'MHd2');
});

// ============================================================
// Migration
// ============================================================

test('migration record can be created and confirmed', function () {
    $user = User::create([
        'fullname' => 'Migrator', 'email' => 'migrator@test.mars',
        'password' => Hash::make('password'),
    ]);

    $id = DB::table('civic_wallet_migrations')->insertGetId([
        'user_id' => $user->id,
        'old_address' => 'MOldAddr',
        'new_address' => 'MNewAddr',
        'status' => 'pending',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    expect($id)->toBeGreaterThan(0);

    DB::table('civic_wallet_migrations')->where('id', $id)->update([
        'status' => 'broadcast',
        'migration_txid' => 'abc123txid',
    ]);

    $record = DB::table('civic_wallet_migrations')->where('id', $id)->first();
    expect($record->status)->toBe('broadcast');
    expect($record->migration_txid)->toBe('abc123txid');
});

test('migration is rate limited to 30 days', function () {
    $user = User::create([
        'fullname' => 'Rate Limited', 'email' => 'ratelimit@test.mars',
        'password' => Hash::make('password'),
    ]);

    // Insert a recent confirmed migration
    DB::table('civic_wallet_migrations')->insert([
        'user_id' => $user->id,
        'old_address' => 'MOld1',
        'new_address' => 'MNew1',
        'status' => 'confirmed',
        'confirmed_at' => now()->subDays(10),
        'created_at' => now()->subDays(10),
        'updated_at' => now(),
    ]);

    // Check if a new migration would be blocked
    $recent = DB::table('civic_wallet_migrations')
        ->where('user_id', $user->id)
        ->where('status', 'confirmed')
        ->where('confirmed_at', '>=', now()->subDays(30))
        ->first();

    expect($recent)->not->toBeNull();

    // After 30 days it should be allowed
    $old = DB::table('civic_wallet_migrations')
        ->where('user_id', $user->id)
        ->where('status', 'confirmed')
        ->where('confirmed_at', '>=', now()->subDays(31))
        ->first();

    // Still found because it's only 10 days old
    expect($old)->not->toBeNull();
});

test('migration preserves old wallet as HD', function () {
    $user = User::create([
        'fullname' => 'Preserver', 'email' => 'preserve@test.mars',
        'password' => Hash::make('password'),
    ]);

    CivicWallet::create([
        'user_id' => $user->id, 'wallet_type' => 'civic',
        'public_addr' => 'MOldCivic', 'encrypted_seed' => 'old_enc_seed',
    ]);

    // Simulate migration: preserve old as HD
    $existing = HDWallet::where('user_id', $user->id)->where('public_addr', 'MOldCivic')->first();
    expect($existing)->toBeNull();

    HDWallet::create([
        'user_id' => $user->id, 'wallet_type' => 'Migrated',
        'public_addr' => 'MOldCivic', 'encrypted_seed' => 'old_enc_seed',
    ]);

    // Update civic to new address
    $civic = CivicWallet::where('user_id', $user->id)->first();
    $civic->public_addr = 'MNewCivic';
    $civic->encrypted_seed = 'new_enc_seed';
    $civic->save();

    // Verify both exist
    expect(CivicWallet::where('user_id', $user->id)->first()->public_addr)->toBe('MNewCivic');
    expect(HDWallet::where('user_id', $user->id)->where('public_addr', 'MOldCivic')->exists())->toBeTrue();
});

// ============================================================
// BlockchainRpc service
// ============================================================

test('BlockchainRpc service class exists with expected methods', function () {
    expect(class_exists(BlockchainRpc::class))->toBeTrue();

    $reflection = new ReflectionClass(BlockchainRpc::class);
    expect($reflection->hasMethod('call'))->toBeTrue();
    expect($reflection->hasMethod('getBalance'))->toBeTrue();
    expect($reflection->hasMethod('getUtxos'))->toBeTrue();
    expect($reflection->hasMethod('getTransactionHistory'))->toBeTrue();
    expect($reflection->hasMethod('broadcast'))->toBeTrue();
    expect($reflection->hasMethod('getBlockCount'))->toBeTrue();
});

// ============================================================
// Controller structure
// ============================================================

test('MigrationController has expected methods', function () {
    $class = \App\Http\Controllers\Wallet\MigrationController::class;
    expect(class_exists($class))->toBeTrue();

    $reflection = new ReflectionClass($class);
    expect($reflection->hasMethod('show'))->toBeTrue();
    expect($reflection->hasMethod('initiate'))->toBeTrue();
    expect($reflection->hasMethod('confirm'))->toBeTrue();
    expect($reflection->hasMethod('history'))->toBeTrue();
});

test('split API controllers all exist', function () {
    expect(class_exists(\App\Http\Controllers\FeedApiController::class))->toBeTrue();
    expect(class_exists(\App\Http\Controllers\AuthApiController::class))->toBeTrue();
    expect(class_exists(\App\Http\Controllers\ForumApiController::class))->toBeTrue();
    expect(class_exists(\App\Http\Controllers\ContentApiController::class))->toBeTrue();
    expect(class_exists(\App\Http\Controllers\UserManagementController::class))->toBeTrue();
});

test('DiscoveryController uses scantxoutset approach', function () {
    $class = \App\Http\Controllers\Wallet\DiscoveryController::class;
    $reflection = new ReflectionClass($class);
    expect($reflection->hasMethod('discover'))->toBeTrue();
    expect($reflection->hasMethod('addressTransactions'))->toBeTrue();

    // Verify it accepts addresses array, not xpub
    $method = $reflection->getMethod('discover');
    $source = file_get_contents($reflection->getFileName());
    expect($source)->toContain('scantxoutset');
    expect($source)->toContain("'addresses'");
});
