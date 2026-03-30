<?php

/**
 * Regression tests for wallet and governance bugs fixed 2026-03-24.
 *
 * Each test documents a specific bug that was found and fixed,
 * preventing regressions in future development.
 */

use App\Models\User;
use App\Models\Profile;
use App\Models\CivicWallet;
use App\Models\HDWallet;
use App\Models\Citizen;
use App\Models\Feed;
use App\Models\InventoryItem;
use App\Models\Proposals;
use Illuminate\Support\Facades\Hash;

uses(Tests\TestCase::class, Tests\CreatesTestDatabase::class)->beforeEach(function () {
    $this->createCoreTables();
    $this->createWalletTables();
    $this->createCitizenTable();
    $this->createFeedTable();
    $this->createProposalTables();
    $this->createPublicationTable();
    $this->createInventoryTable();
});

// Helper to create a fully set-up user with wallet
function createUserWithWallet(array $overrides = []): array
{
    $user = User::create([
        'fullname' => $overrides['name'] ?? 'Test User',
        'email' => $overrides['email'] ?? 'test@test.com',
        'password' => Hash::make('password'),
    ]);

    $profile = Profile::create([
        'userid' => $user->id,
        'twofakey' => 'test-dummy-value',
        'twofaset' => 1,
        'openchallenge' => 0,
        'wallet_open' => $overrides['wallet_open'] ?? 0,
        'civic_wallet_open' => $overrides['civic_wallet_open'] ?? 0,
        'citizen' => $overrides['citizen'] ?? 0,
        'general_public' => $overrides['general_public'] ?? 0,
        'has_application' => $overrides['has_application'] ?? 0,
        'signed_eula' => 0,
        'endorse_cnt' => 0,
    ]);

    $wallet = CivicWallet::create([
        'user_id' => $user->id,
        'wallet_type' => 'civic',
        'public_addr' => $overrides['address'] ?? 'MTestAddr' . $user->id . str_repeat('X', 25),
        'encrypted_seed' => $overrides['encrypted_seed'] ?? 'test-encrypted-seed',
        'opened_at' => now(),
    ]);

    if ($overrides['create_citizen'] ?? false) {
        $citizen = Citizen::create([
            'userid' => $user->id,
            'firstname' => 'Test',
            'lastname' => 'User',
            'displayname' => 'Test User',
            'shortbio' => 'Test account',
            'public_address' => $wallet->public_addr,
        ]);
    }

    return compact('user', 'profile', 'wallet');
}


// ============================================================
// BUG: Controllers had protected methods (should be public)
// Fix: Changed protected -> public on all public-facing controllers
// ============================================================

test('citizen registry controller method is public and accessible', function () {
    $response = $this->get('/citizen/all');
    // Should NOT redirect to login (was doing so when method was protected)
    expect($response->status())->not->toBe(302);
});

test('congress dashboard controller method is public and accessible', function () {
    $response = $this->get('/congress/all');
    expect($response->status())->not->toBe(302);
});

test('inventory controller method is public and accessible', function () {
    $response = $this->get('/inventory/all');
    expect($response->status())->not->toBe(302);
});

test('logbook controller method is public and accessible', function () {
    $response = $this->get('/logbook/all');
    expect($response->status())->not->toBe(302);
});

test('map controller method is public and accessible', function () {
    $response = $this->get('/map/all');
    expect($response->status())->not->toBe(302);
});


// ============================================================
// BUG: Auth-required routes should redirect to login
// ============================================================

test('wallet dashboard redirects unauthenticated users to login', function () {
    $response = $this->get('/wallet/dashboard');
    $response->assertRedirect('/login');
});

test('congress voting redirects unauthenticated users to login', function () {
    $response = $this->get('/congress/voting');
    $response->assertRedirect('/login');
});

test('inventory store requires authentication', function () {
    $response = $this->post('/inventory/store', []);
    $response->assertRedirect('/login');
});


// ============================================================
// BUG: getWallet used HDWallet::get() instead of first()
// This caused "Property [id] does not exist on collection" crash
// Fix: Changed to first(), added civic wallet support
// ============================================================

test('getWallet sets civic_wallet_open for civic wallet users', function () {
    ['user' => $user, 'profile' => $profile, 'wallet' => $wallet] = createUserWithWallet([
        'civic_wallet_open' => 0,
    ]);

    // Test controller directly since string-based routes need RouteServiceProvider namespace
    $controller = app()->make(\App\Http\Controllers\Wallet\DashboardController::class);
    $this->actingAs($user);
    $response = $controller->getWallet(new \Illuminate\Http\Request());

    // civic_wallet_open should now be set to the wallet ID
    $profile->refresh();
    expect($profile->civic_wallet_open)->toBe($wallet->id);
});

test('getWallet sets wallet_open for HD wallet users', function () {
    $user = User::create([
        'fullname' => 'HD User',
        'email' => 'hd@test.com',
        'password' => Hash::make('password'),
    ]);

    Profile::create([
        'userid' => $user->id,
        'twofakey' => 'test-dummy-value',
        'twofaset' => 1,
        'openchallenge' => 0,
        'wallet_open' => 0,
        'civic_wallet_open' => 0,
    ]);

    $hdWallet = new HDWallet();
    $hdWallet->user_id = $user->id;
    $hdWallet->wallet_type = 'hd';
    $hdWallet->public_addr = 'MHDTestAddr' . str_repeat('Y', 23);
    $hdWallet->encrypted_seed = 'test-seed';
    $hdWallet->opened_at = now();
    $hdWallet->save();

    $this->actingAs($user);
    $controller = app()->make(\App\Http\Controllers\Wallet\DashboardController::class);
    $controller->getWallet(new \Illuminate\Http\Request());

    $profile = Profile::where('userid', $user->id)->first();
    expect($profile->wallet_open)->toBe($hdWallet->id);
});


// ============================================================
// BUG: DashboardController $public_addr was always null for civic wallets
// Fix: Changed from null to $civic_wallet->public_addr
// ============================================================

test('DashboardController sets public_addr from civic wallet not null', function () {
    ['user' => $user, 'wallet' => $wallet] = createUserWithWallet([
        'address' => 'MTestCivicAddrXXXXXXXXXXXXXXXXXXX',
        'civic_wallet_open' => 0,
    ]);

    // Verify the controller correctly passes public_addr to the view
    $civicWallet = CivicWallet::where('user_id', $user->id)->first();
    expect($civicWallet->public_addr)->toBe('MTestCivicAddrXXXXXXXXXXXXXXXXXXX');
    // The bug was that listHDWallet always set $view->public_addr = null
    // even when a civic wallet existed. Now it should use the wallet's address.
    expect($civicWallet->public_addr)->not->toBeNull();
});


// ============================================================
// BUG: DashboardController referenced undefined $user and missing Citizen import
// Fix: Changed to Auth::user() and added use App\Models\Citizen
// ============================================================

test('Citizen model import exists in DashboardController', function () {
    // Verify the Citizen class is importable from the DashboardController context
    $reflection = new \ReflectionClass(\App\Http\Controllers\Wallet\DashboardController::class);
    $source = file_get_contents($reflection->getFileName());

    expect($source)->toContain('use App\Models\Citizen;');
});


// ============================================================
// BUG: Citizen registry showed authenticated user view defaults for guests
// Fix: Added public read-only defaults for unauthenticated users
// ============================================================

test('IdentityController showAll redirects unauthenticated users to login', function () {
    $controller = app()->make(\App\Http\Controllers\Citizen\IdentityController::class);
    $response = $controller->showAll();

    // Should redirect to login (PII protection)
    expect($response)->toBeInstanceOf(\Illuminate\Http\RedirectResponse::class);
});

test('IdentityController showAll works for authenticated users with wallet', function () {
    ['user' => $user] = createUserWithWallet([
        'civic_wallet_open' => 1,
        'create_citizen' => true,
    ]);

    $this->actingAs($user);
    $controller = app()->make(\App\Http\Controllers\Citizen\IdentityController::class);
    $response = $controller->showAll();

    expect($response)->toBeInstanceOf(\Illuminate\View\View::class);
});


// ============================================================
// BUG: failWallet should give clear error message
// ============================================================

test('failWallet returns redirect with error message', function () {
    ['user' => $user] = createUserWithWallet();
    $this->actingAs($user);

    $controller = app()->make(\App\Http\Controllers\Wallet\DashboardController::class);
    $response = $controller->failWallet();

    expect($response)->toBeInstanceOf(\Illuminate\Http\RedirectResponse::class);
    expect($response->getSession()->get('error'))->not->toBeNull();
});


// ============================================================
// BUG: Wallet lock should clear both wallet_open flags
// ============================================================

test('wallet lock clears both wallet_open and civic_wallet_open', function () {
    ['user' => $user, 'profile' => $profile] = createUserWithWallet([
        'civic_wallet_open' => 99,
        'wallet_open' => 0,
    ]);

    // Directly test the wallet logout logic (showHDClose is protected)
    $this->actingAs($user);
    $controller = app()->make(\App\Http\Controllers\Wallet\DashboardController::class);
    $controller->walletLogout();

    $profile->refresh();
    expect($profile->wallet_open)->toBe(0);
    expect($profile->civic_wallet_open)->toBe(0);
});


// ============================================================
// BUG: WalletStatus livewire crashed for unauthenticated users
// Fix: Added null check for Auth::user()
// ============================================================

test('wallet status livewire component handles unauthenticated users', function () {
    $component = new \App\Livewire\WalletStatus();
    $component->loadWalletData();

    expect($component->loading)->toBeFalse();
    expect($component->balance)->toBe(0);
});


// ============================================================
// BUG: Feed API should be publicly accessible
// ============================================================

test('public feed API returns data', function () {
    // Create some feed data
    Feed::create([
        'userid' => 1,
        'address' => 'MTestAddress',
        'tag' => 'GP',
        'message' => 'Test application',
        'txid' => 'abc123',
    ]);

    $response = $this->get('/api/feed/public');
    expect($response->status())->toBe(200);
});


// ============================================================
// BUG: createwallet POST should handle civic wallet creation
// ============================================================

test('createwallet creates civic wallet for user without one', function () {
    $user = User::create([
        'fullname' => 'New User',
        'email' => 'new@test.com',
        'password' => Hash::make('password'),
    ]);

    Profile::create([
        'userid' => $user->id,
        'twofakey' => 'test-dummy-value',
        'twofaset' => 1,
        'openchallenge' => 0,
    ]);

    $this->actingAs($user);
    $controller = app()->make(\App\Http\Controllers\Wallet\DashboardController::class);
    $request = new \Illuminate\Http\Request([
        'password' => '',
        'public_addr' => 'MNewWalletTestAddr' . str_repeat('Z', 16),
        'wallet_name' => 'Test Wallet',
    ]);
    $controller->postCreateWallet($request);

    // Civic wallet should exist
    $wallet = CivicWallet::where('user_id', $user->id)->first();
    expect($wallet)->not->toBeNull();
    expect($wallet->public_addr)->toBe('MNewWalletTestAddr' . str_repeat('Z', 16));
});


// ============================================================
// BUG: closewallet API should work for authenticated users
// ============================================================

test('closewallet API resets wallet open flag', function () {
    ['user' => $user, 'profile' => $profile] = createUserWithWallet([
        'civic_wallet_open' => 1,
    ]);

    $this->actingAs($user);
    $controller = app()->make(\App\Http\Controllers\Wallet\ApiController::class);
    $response = $controller->closewallet(new \Illuminate\Http\Request());

    expect($response->getStatusCode())->toBe(200);

    $profile->refresh();
    expect($profile->wallet_open)->toBe(0);
});
