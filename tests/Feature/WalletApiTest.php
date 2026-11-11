<?php

/**
 * Tests for the Wallet API controller.
 * Uses direct controller/model testing to avoid RouteServiceProvider namespace issues.
 */

use App\Http\Controllers\Wallet\ApiController;
use App\Models\CivicWallet;
use App\Models\Feed;
use App\Models\Profile;
use App\Models\User;
use App\Services\BlockchainRpc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tests\CreatesTestDatabase;
use Tests\TestCase;

uses(TestCase::class, CreatesTestDatabase::class)->beforeEach(function () {
    $this->createCoreTables();
    $this->createWalletTables();
    $this->createCitizenTable();
    $this->createFeedTable();
});

// Helper to create a user with profile and civic wallet
function createWalletApiUser(array $overrides = []): array
{
    $user = User::create([
        'fullname' => $overrides['name'] ?? 'API User',
        'email' => $overrides['email'] ?? 'apiuser@test.mars',
        'password' => Hash::make('password'),
    ]);

    $profile = Profile::create([
        'userid' => $user->id,
        'twofakey' => 'test-key',
        'twofaset' => 1,
        'openchallenge' => 0,
        'wallet_open' => $overrides['wallet_open'] ?? 1,
        'civic_wallet_open' => $overrides['civic_wallet_open'] ?? 1,
        'citizen' => $overrides['citizen'] ?? 0,
        'general_public' => $overrides['general_public'] ?? 0,
        'has_application' => 0,
        'signed_eula' => 0,
        'endorse_cnt' => 0,
    ]);

    $wallet = CivicWallet::create([
        'user_id' => $user->id,
        'wallet_type' => 'civic',
        'public_addr' => $overrides['address'] ?? 'MApiTestAddr'.$user->id.str_repeat('X', 22),
        'encrypted_seed' => 'test-seed',
        'opened_at' => now(),
    ]);

    return compact('user', 'profile', 'wallet');
}

// ============================================================
// Controller class and method existence
// ============================================================

test('Wallet ApiController class exists', function () {
    expect(class_exists(ApiController::class))->toBeTrue();
});

test('Wallet ApiController has key methods', function () {
    $reflection = new ReflectionClass(ApiController::class);
    expect($reflection->hasMethod('getBalance'))->toBeTrue();
    expect($reflection->hasMethod('permapinpic'))->toBeTrue();
    expect($reflection->hasMethod('setfeed'))->toBeTrue();
    expect($reflection->hasMethod('closewallet'))->toBeTrue();
    expect($reflection->hasMethod('linkCivicWallet'))->toBeTrue();
    expect($reflection->hasMethod('getPrice'))->toBeTrue();
    expect($reflection->hasMethod('marsPrice'))->toBeTrue();
});

// ============================================================
// closewallet resets wallet_open flag via direct model manipulation
// ============================================================

test('closewallet resets wallet_open to zero', function () {
    ['user' => $user, 'profile' => $profile] = createWalletApiUser([
        'wallet_open' => 5,
    ]);

    $this->actingAs($user);
    $controller = app()->make(ApiController::class);
    $response = $controller->closewallet(new Request);

    expect($response->getStatusCode())->toBe(200);

    $profile->refresh();
    expect($profile->wallet_open)->toBe(0);
});

test('closewallet works when profile has no wallet open', function () {
    ['user' => $user, 'profile' => $profile] = createWalletApiUser([
        'wallet_open' => 0,
    ]);

    $this->actingAs($user);
    $controller = app()->make(ApiController::class);
    $response = $controller->closewallet(new Request);

    expect($response->getStatusCode())->toBe(200);
    $profile->refresh();
    expect($profile->wallet_open)->toBe(0);
});

// ============================================================
// Feed creation via the Feed model
// ============================================================

test('Feed model can create entries', function () {
    $feed = Feed::create([
        'userid' => 1,
        'address' => 'MTestFeedAddr',
        'tag' => 'GP',
        'message' => 'Test feed entry',
        'txid' => 'txid_test_123',
    ]);

    expect($feed->id)->toBeGreaterThan(0);
    expect($feed->tag)->toBe('GP');
    expect($feed->message)->toBe('Test feed entry');
});

// A node that knows exactly the transactions it is given. No real node is called.
function fakeNode(array $transactions): void
{
    app()->instance(BlockchainRpc::class, new class($transactions) extends BlockchainRpc
    {
        public function __construct(private array $transactions) {}

        public function call(string $method, array $params = [], int $timeout = 30): mixed
        {
            return $method === 'getrawtransaction' ? ($this->transactions[$params[0]] ?? null) : null;
        }
    });
}

function setfeedRequest(string $txid, string $address, array $extra = []): Request
{
    return new Request(array_merge([
        'txid' => $txid, 'type' => 'GP', 'address' => $address,
        'embedded_link' => 'https://ipfs.marscoin.org/ipfs/QmApplication', 'message' => '',
    ], $extra));
}

test('setfeed does not make a user general public on a claimed txid alone', function () {
    ['user' => $user, 'profile' => $profile, 'wallet' => $wallet] = createWalletApiUser(['general_public' => 0]);
    fakeNode([]);

    $this->actingAs($user);
    $response = app()->make(ApiController::class)->setfeed(setfeedRequest(str_repeat('a', 64), $wallet->public_addr));

    // The page still gets its answer and the pending row, as before.
    expect($response->getStatusCode())->toBe(200);
    expect($response->getData(true))->toBe(['Hash' => str_repeat('a', 64)]);
    expect(Feed::where('userid', $user->id)->where('tag', 'GP')->whereNull('mined')->count())->toBe(1);
    $profile->refresh();
    expect($profile->general_public)->toBe(0);
    expect($profile->has_application)->toBe(1);
});

test('setfeed makes a user general public when the node confirms their own application transaction', function () {
    ['user' => $user, 'profile' => $profile, 'wallet' => $wallet] = createWalletApiUser(['general_public' => 0]);
    $txid = str_repeat('a', 64);
    $funding = str_repeat('b', 64);
    fakeNode([
        $txid => ['vin' => [['txid' => $funding, 'vout' => 0]], 'vout' => [
            ['scriptPubKey' => ['asm' => 'OP_RETURN '.bin2hex('GP_QmApplication'), 'type' => 'nulldata']],
        ]],
        $funding => ['vout' => [['scriptPubKey' => ['address' => $wallet->public_addr]]]],
    ]);

    $this->actingAs($user);
    app()->make(ApiController::class)->setfeed(setfeedRequest($txid, $wallet->public_addr));

    expect($profile->refresh()->general_public)->toBe(1);
});

test('setfeed ignores a real transaction that is not the user\'s own application', function (string $record, bool $fundedByUser) {
    ['user' => $user, 'profile' => $profile, 'wallet' => $wallet] = createWalletApiUser(['general_public' => 0]);
    $txid = str_repeat('a', 64);
    $funding = str_repeat('b', 64);
    fakeNode([
        $txid => ['vin' => [['txid' => $funding, 'vout' => 0]], 'vout' => [
            ['scriptPubKey' => ['asm' => $record]],
            // An output paying the user proves nothing about who signed.
            ['scriptPubKey' => ['asm' => 'OP_DUP OP_HASH160 00 OP_EQUALVERIFY OP_CHECKSIG', 'address' => $wallet->public_addr]],
        ]],
        $funding => ['vout' => [['scriptPubKey' => ['addresses' => [$fundedByUser ? $wallet->public_addr : 'MSomeoneElse']]]]],
    ]);

    $this->actingAs($user);
    app()->make(ApiController::class)->setfeed(setfeedRequest($txid, $wallet->public_addr));

    expect($profile->refresh()->general_public)->toBe(0);
})->with([
    'signed by someone else' => ['OP_RETURN '.bin2hex('GP_QmApplication'), false],
    'another application' => ['OP_RETURN '.bin2hex('GP_QmOther'), true],
    'no record at all' => ['OP_DUP OP_HASH160 00 OP_EQUALVERIFY OP_CHECKSIG', true],
]);

test('setfeed no longer changes status for records other than an application', function () {
    ['user' => $user, 'profile' => $profile, 'wallet' => $wallet] = createWalletApiUser(['general_public' => 0]);
    fakeNode([]);

    $this->actingAs($user);
    $response = app()->make(ApiController::class)->setfeed(setfeedRequest(str_repeat('a', 64), $wallet->public_addr, ['type' => 'LB']));

    expect($response->getStatusCode())->toBe(200);
    expect(Feed::where('userid', $user->id)->where('tag', 'LB')->count())->toBe(1);
    expect($profile->refresh()->general_public)->toBe(0);
    expect($profile->has_application)->toBe(0);
});

test('setfeed refuses another person\'s address and unknown record types', function () {
    ['user' => $user, 'profile' => $profile, 'wallet' => $wallet] = createWalletApiUser(['general_public' => 0]);
    fakeNode([]);
    $this->actingAs($user);
    $controller = app()->make(ApiController::class);

    expect($controller->setfeed(setfeedRequest(str_repeat('a', 64), 'MSomeoneElse'))->getStatusCode())->toBe(403);
    expect($controller->setfeed(setfeedRequest(str_repeat('a', 64), $wallet->public_addr, ['type' => 'CT']))->getStatusCode())->toBe(422);
    expect(Feed::count())->toBe(0);
    expect($profile->refresh()->general_public)->toBe(0);
});

test('setfeed accepts the record type under the name the join page uses', function () {
    ['user' => $user, 'wallet' => $wallet] = createWalletApiUser(['general_public' => 0]);
    fakeNode([]);
    $this->actingAs($user);
    $request = new Request(['tag' => 'GP', 'txid' => str_repeat('a', 64), 'address' => $wallet->public_addr, 'embedded_link' => 'https://ipfs.marscoin.org/ipfs/QmApplication', 'message' => '']);

    expect(app()->make(ApiController::class)->setfeed($request)->getStatusCode())->toBe(200);
    expect(Feed::where('userid', $user->id)->where('tag', 'GP')->count())->toBe(1);
});

test('setfeed with GP tag sets has_application flag', function () {
    ['user' => $user, 'profile' => $profile, 'wallet' => $wallet] = createWalletApiUser();

    $this->actingAs($user);
    $controller = app()->make(ApiController::class);
    $request = new Request([
        'txid' => 'tx_gp_app',
        'type' => 'GP',
        'address' => $wallet->public_addr,
        'embedded_link' => '',
        'message' => 'GP Application',
    ]);
    $controller->setfeed($request);

    $profile->refresh();
    expect($profile->has_application)->toBe(1);
});

// ============================================================
// getPrice / marsPrice methods exist and are callable
// ============================================================

test('getPrice method exists and is public', function () {
    $reflection = new ReflectionClass(ApiController::class);
    $method = $reflection->getMethod('getPrice');
    expect($method->isPublic())->toBeTrue();
});

test('marsPrice method exists and is public', function () {
    $reflection = new ReflectionClass(ApiController::class);
    $method = $reflection->getMethod('marsPrice');
    expect($method->isPublic())->toBeTrue();
});

// ============================================================
// linkCivicWallet links matching wallet to profile
// ============================================================

test('linkCivicWallet links civic wallet id to profile', function () {
    ['user' => $user, 'profile' => $profile, 'wallet' => $wallet] = createWalletApiUser([
        'civic_wallet_open' => 0,
    ]);

    $this->actingAs($user);
    $controller = app()->make(ApiController::class);
    $request = new Request(['address' => $wallet->public_addr]);
    $response = $controller->linkCivicWallet($request);

    expect($response->getStatusCode())->toBe(200);

    $data = $response->getData(true);
    expect($data['success'])->toBeTrue();
    expect($data['civic_wallet_id'])->toBe($wallet->id);

    $profile->refresh();
    expect($profile->civic_wallet_open)->toBe($wallet->id);
});

test('linkCivicWallet returns 404 for non-matching address', function () {
    ['user' => $user] = createWalletApiUser();

    $this->actingAs($user);
    $controller = app()->make(ApiController::class);
    $request = new Request(['address' => 'MNonExistentAddrXXXXXXXXXXXXXXXXX']);
    $response = $controller->linkCivicWallet($request);

    expect($response->getStatusCode())->toBe(404);
});
