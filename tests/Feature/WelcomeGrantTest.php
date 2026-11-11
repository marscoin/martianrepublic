<?php

use App\Includes\MarscoinECDSA;
use App\Models\Citizen;
use App\Models\CivicWallet;
use App\Models\Profile;
use App\Models\User;
use App\Models\WelcomeGrant;
use App\Models\WelcomeGrantNonce;
use App\Services\WelcomeGrant\GrantSender;
use App\Services\WelcomeGrant\GrantSendException;
use App\Services\WelcomeGrant\RpcGrantSender;
use App\Services\WelcomeGrant\WelcomeGrantService;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Process;
use Tests\CreatesTestDatabase;
use Tests\TestCase;

/**
 * Stands in for the node. No test in this file talks to a real node.
 */
class FakeGrantSender implements GrantSender
{
    public array $sent = [];

    public ?int $balance = 500000000;

    public ?Throwable $failure = null;

    public ?Closure $duringSend = null;

    public function availableZubrins(): ?int
    {
        return $this->balance;
    }

    public function send(string $address, int $zubrins): string
    {
        if ($this->duringSend) {
            ($this->duringSend)();
        }
        if ($this->failure) {
            throw $this->failure;
        }
        $this->sent[] = [$address, $zubrins];

        return str_repeat('ab', 32);
    }
}

uses(TestCase::class, CreatesTestDatabase::class)->beforeEach(function () {
    $this->createCoreTables();
    $this->createWalletTables();
    $this->createCitizenTable();
    (require database_path('migrations/2026_11_11_000001_create_welcome_grants.php'))->up();

    config(['welcome_grant.enabled' => true, 'welcome_grant.wallet' => 'welcome-grant']);
    $this->sender = new FakeGrantSender;
    $this->app->instance(GrantSender::class, $this->sender);
});

/**
 * A newcomer with a real key, signed in, steps 1 and 2 done unless overridden.
 */
function grantNewcomer(array $citizen = [], array $profile = []): array
{
    $key = new MarscoinECDSA;
    $key->generateRandomPrivateKey();
    $address = $key->getAddress();
    $user = User::create(['fullname' => 'New Comer', 'email' => $address.'@martianrepublic.org', 'password' => Hash::make('x')]);
    Profile::create(array_merge(['userid' => $user->id, 'has_application' => 1], $profile));
    CivicWallet::create(['user_id' => $user->id, 'wallet_type' => 'MARS', 'public_addr' => $address]);
    $row = new Citizen;
    $row->forceFill(array_merge([
        'userid' => $user->id, 'public_address' => $address, 'firstname' => 'New', 'lastname' => 'Comer',
        'avatar_link' => 'https://ipfs.marscoin.org/ipfs/QmPhoto', 'liveness_link' => 'https://ipfs.marscoin.org/ipfs/QmVideo',
    ], $citizen))->save();

    return ['user' => $user, 'key' => $key, 'address' => $address];
}

function grantChallenge($test, User $user): array
{
    return $test->actingAs($user, 'sanctum')->postJson('/api/grant/challenge')->assertOk()->json();
}

function grantClaim($test, array $who, ?array $challenge = null, array $override = []): array
{
    $challenge ??= grantChallenge($test, $who['user']);

    return $test->actingAs($who['user'], 'sanctum')->postJson('/api/grant/claim', array_merge([
        'address' => $who['address'], 'nonce' => $challenge['nonce'],
        'signature' => $who['key']->signMessage($challenge['message'], true),
    ], $override))->assertOk()->json();
}

test('grant routes require a signed-in user', function () {
    $this->postJson('/api/grant/challenge')->assertStatus(401);
    $this->postJson('/api/grant/claim')->assertStatus(401);
    $this->getJson('/api/grant/status')->assertStatus(401);
});

test('challenge gives an eligible newcomer the exact message to sign', function () {
    $who = grantNewcomer();
    $challenge = grantChallenge($this, $who['user']);

    expect(array_keys($challenge))->toBe(['eligible', 'reason', 'status', 'address', 'amount_zubrins', 'nonce', 'message', 'expires_at', 'txid']);
    expect($challenge['eligible'])->toBeTrue();
    expect($challenge['status'])->toBe('available');
    expect($challenge['address'])->toBe($who['address']);
    expect($challenge['amount_zubrins'])->toBe(1000000);
    expect($challenge['nonce'])->toMatch('/^[0-9a-f]{64}$/');
    expect($challenge['message'])->toBe("Martian Republic welcome grant\naddress: {$who['address']}\nnonce: {$challenge['nonce']}");
    expect(strtotime($challenge['expires_at']))->toBeGreaterThan(time() + 500)->toBeLessThanOrEqual(time() + 600);
});

test('a valid signed claim is paid once to the civic address', function () {
    $who = grantNewcomer();
    $claim = grantClaim($this, $who);

    expect($claim)->toBe(['status' => 'sent', 'txid' => str_repeat('ab', 32), 'amount_zubrins' => 1000000, 'reason' => null]);
    expect($this->sender->sent)->toBe([[$who['address'], 1000000]]);

    $status = $this->getJson('/api/grant/status')->assertOk()->json();
    expect($status['granted'])->toBeTrue();
    expect($status['txid'])->toBe(str_repeat('ab', 32));
    expect($status['amount_zubrins'])->toBe(1000000);
    expect($status['granted_at'])->not->toBeNull();
});

test('status before any grant', function () {
    $who = grantNewcomer();
    $this->actingAs($who['user'], 'sanctum')->getJson('/api/grant/status')->assertOk()
        ->assertExactJson(['granted' => false, 'txid' => null, 'amount_zubrins' => null, 'granted_at' => null]);
});

test('a signature from another key is rejected', function () {
    $who = grantNewcomer();
    $challenge = grantChallenge($this, $who['user']);
    $other = new MarscoinECDSA;
    $other->generateRandomPrivateKey();

    $claim = grantClaim($this, $who, $challenge, ['signature' => $other->signMessage($challenge['message'], true)]);
    expect($claim['status'])->toBe('invalid_signature');

    $claim = grantClaim($this, $who, $challenge, ['signature' => 'not a signature']);
    expect($claim['status'])->toBe('invalid_signature');

    // A valid signature over different text does not count either.
    $claim = grantClaim($this, $who, $challenge, ['signature' => $who['key']->signMessage('nonce: '.$challenge['nonce'], true)]);
    expect($claim['status'])->toBe('invalid_signature');

    expect($this->sender->sent)->toBe([]);
    expect(WelcomeGrant::count())->toBe(0);
});

test('a nonce cannot be used twice', function () {
    $who = grantNewcomer();
    $challenge = grantChallenge($this, $who['user']);
    expect(grantClaim($this, $who, $challenge)['status'])->toBe('sent');

    // Even if the grant row vanished, the used nonce stays dead.
    WelcomeGrant::query()->delete();
    $replay = grantClaim($this, $who, $challenge);
    expect($replay['status'])->toBe('expired');
    expect($this->sender->sent)->toHaveCount(1);
});

test('an expired nonce is rejected', function () {
    $who = grantNewcomer();
    $challenge = grantChallenge($this, $who['user']);
    $this->travel(11)->minutes();

    expect(grantClaim($this, $who, $challenge)['status'])->toBe('expired');
    expect($this->sender->sent)->toBe([]);
});

test('only the newest challenge of a user is valid', function () {
    $who = grantNewcomer();
    $old = grantChallenge($this, $who['user']);
    grantChallenge($this, $who['user']);

    expect(grantClaim($this, $who, $old)['status'])->toBe('expired');
});

test('a nonce issued to one user cannot be claimed by another', function () {
    $alice = grantNewcomer();
    $mallory = grantNewcomer();
    $challenge = grantChallenge($this, $alice['user']);

    // Mallory presents Alice's nonce with his own address and key.
    $message = "Martian Republic welcome grant\naddress: {$mallory['address']}\nnonce: {$challenge['nonce']}";
    $claim = grantClaim($this, $mallory, ['nonce' => $challenge['nonce'], 'message' => $message]);
    expect($claim['status'])->toBe('expired');
    expect($this->sender->sent)->toBe([]);
});

test('the grant is never paid to an address other than the account civic address', function () {
    $who = grantNewcomer();
    $other = new MarscoinECDSA;
    $other->generateRandomPrivateKey();
    $challenge = grantChallenge($this, $who['user']);

    // Another address, properly signed by that other address.
    $message = "Martian Republic welcome grant\naddress: {$other->getAddress()}\nnonce: {$challenge['nonce']}";
    $claim = grantClaim($this, $who, $challenge, ['address' => $other->getAddress(), 'signature' => $other->signMessage($message, true)]);

    expect($claim['status'])->toBe('not_eligible');
    expect($this->sender->sent)->toBe([]);
});

test('a second claim reports the first grant and pays nothing', function () {
    $who = grantNewcomer();
    expect(grantClaim($this, $who)['status'])->toBe('sent');

    $again = grantChallenge($this, $who['user']);
    expect($again['status'])->toBe('already_granted');
    expect($again['eligible'])->toBeFalse();
    expect($again['nonce'])->toBeNull();
    expect($again['txid'])->toBe(str_repeat('ab', 32));

    $claim = $this->postJson('/api/grant/claim', ['address' => $who['address'], 'nonce' => str_repeat('0', 64), 'signature' => 'x'])->assertOk()->json();
    expect($claim['status'])->toBe('already_granted');
    expect($claim['txid'])->toBe(str_repeat('ab', 32));
    expect($this->sender->sent)->toHaveCount(1);
});

test('the database refuses a second grant row for a user or an address', function () {
    $row = ['user_id' => 1, 'address' => 'MAddressOne', 'amount_zubrins' => 1000000];
    WelcomeGrant::create($row);

    expect(fn () => WelcomeGrant::create(['address' => 'MAddressTwo'] + $row))->toThrow(UniqueConstraintViolationException::class);
    expect(fn () => WelcomeGrant::create(['user_id' => 2] + $row))->toThrow(UniqueConstraintViolationException::class);

    $service = app(WelcomeGrantService::class);
    expect($service->reserve(1, 'MAddressOne', 1000000, null))->toBeNull();
    expect($service->reserve(3, 'MAddressThree', 1000000, null))->not->toBeNull();
    expect($service->reserve(3, 'MAddressThree', 1000000, null))->toBeNull();
});

test('a claim arriving while the first is being sent cannot pay again', function () {
    $who = grantNewcomer();
    $first = grantChallenge($this, $who['user']);
    // A second, equally valid nonce, as if two requests raced past the checks.
    $second = WelcomeGrantNonce::create(['user_id' => $who['user']->id, 'address' => $who['address'], 'nonce' => bin2hex(random_bytes(32)), 'expires_at' => now()->addMinutes(10)]);
    $service = app(WelcomeGrantService::class);
    $inner = null;
    $this->sender->duringSend = function () use (&$inner, $service, $who, $second) {
        $this->sender->duringSend = null;
        $inner = $service->claim($who['user'], $who['address'], $second->nonce,
            $who['key']->signMessage($service->message($who['address'], $second->nonce), true), '127.0.0.1');
    };

    expect(grantClaim($this, $who, $first)['status'])->toBe('sent');
    expect($inner['status'])->toBe('unavailable');
    expect($this->sender->sent)->toHaveCount(1);
    expect(WelcomeGrant::count())->toBe(1);
});

test('steps one and two must be complete', function (array $citizen, array $profile) {
    $who = grantNewcomer($citizen, $profile);
    $challenge = grantChallenge($this, $who['user']);
    expect($challenge['status'])->toBe('not_eligible');
    expect($challenge['eligible'])->toBeFalse();
    expect($challenge['reason'])->toBeString();
    expect($challenge['nonce'])->toBeNull();
})->with([
    'no name' => [['firstname' => null], []],
    'no photo' => [['avatar_link' => null], []],
    'no video' => [['liveness_link' => ''], []],
    'already general public' => [[], ['general_public' => 1]],
    'already citizen' => [[], ['citizen' => 1]],
]);

test('eligibility is checked again when the claim arrives', function () {
    $who = grantNewcomer();
    $challenge = grantChallenge($this, $who['user']);
    Profile::where('userid', $who['user']->id)->update(['general_public' => 1]);

    expect(grantClaim($this, $who, $challenge)['status'])->toBe('not_eligible');
    expect($this->sender->sent)->toBe([]);
});

test('nothing is offered or paid while the feature is switched off', function () {
    $who = grantNewcomer();
    $challenge = grantChallenge($this, $who['user']);
    config(['welcome_grant.enabled' => false]);

    expect(grantClaim($this, $who, $challenge)['status'])->toBe('unavailable');
    $off = grantChallenge($this, $who['user']);
    expect($off['status'])->toBe('unavailable');
    expect($off['nonce'])->toBeNull();
    expect($this->sender->sent)->toBe([]);
});

test('the feature is off by default', function () {
    expect((require config_path('welcome_grant.php'))['enabled'])->toBeFalse();
});

test('the global daily cap stops further grants', function () {
    config(['welcome_grant.daily_cap' => 1, 'welcome_grant.per_ip_daily_cap' => 10]);
    expect(grantClaim($this, grantNewcomer())['status'])->toBe('sent');

    $late = grantNewcomer();
    $challenge = grantChallenge($this, $late['user']);
    expect($challenge['status'])->toBe('unavailable');

    // Even with a valid nonce in hand, the cap holds at claim time.
    config(['welcome_grant.daily_cap' => 2]);
    $challenge = grantChallenge($this, $late['user']);
    config(['welcome_grant.daily_cap' => 1]);
    expect(grantClaim($this, $late, $challenge)['status'])->toBe('unavailable');
    expect($this->sender->sent)->toHaveCount(1);

    // A new day opens the cap again.
    $this->travel(1)->days();
    expect(grantClaim($this, $late)['status'])->toBe('sent');
});

test('the per-IP daily cap stops further grants from one network', function () {
    config(['welcome_grant.per_ip_daily_cap' => 2]);
    expect(grantClaim($this, grantNewcomer())['status'])->toBe('sent');
    expect(grantClaim($this, grantNewcomer())['status'])->toBe('sent');

    $third = grantNewcomer();
    expect(grantChallenge($this, $third['user'])['status'])->toBe('unavailable');
    expect($this->sender->sent)->toHaveCount(2);

    // Another network is not affected.
    $challenge = $this->actingAs($third['user'], 'sanctum')->withServerVariables(['REMOTE_ADDR' => '203.0.113.9'])
        ->postJson('/api/grant/challenge')->assertOk()->json();
    expect($challenge['status'])->toBe('available');
});

test('no grant when the grant wallet is short or unreachable', function (?int $balance) {
    $who = grantNewcomer();
    $challenge = grantChallenge($this, $who['user']);
    $this->sender->balance = $balance;

    expect(grantClaim($this, $who, $challenge)['status'])->toBe('unavailable');
    expect(grantChallenge($this, $who['user'])['status'])->toBe('unavailable');
    expect($this->sender->sent)->toBe([]);
    expect(WelcomeGrant::count())->toBe(0);
})->with(['short' => [999999], 'amount but no fee' => [1000000], 'unreachable' => [null]]);

test('a send the node refused can be retried and is paid once', function () {
    $who = grantNewcomer();
    $this->sender->failure = new GrantSendException('error code: -6 Insufficient funds', true);
    $claim = grantClaim($this, $who);
    expect($claim['status'])->toBe('unavailable');
    expect(WelcomeGrant::first()->status)->toBe('failed');

    $this->sender->failure = null;
    expect(grantClaim($this, $who)['status'])->toBe('sent');
    expect($this->sender->sent)->toHaveCount(1);
    $grant = WelcomeGrant::first();
    expect(WelcomeGrant::count())->toBe(1);
    expect($grant->status)->toBe('sent');
    expect($grant->attempts)->toBe(2);
});

test('a send with unknown outcome is never retried automatically', function () {
    $who = grantNewcomer();
    $this->sender->failure = new RuntimeException('connection dropped');
    expect(grantClaim($this, $who)['status'])->toBe('unavailable');
    expect(WelcomeGrant::first()->status)->toBe('uncertain');

    $this->sender->failure = null;
    expect(grantChallenge($this, $who['user'])['status'])->toBe('unavailable');
    $claim = $this->postJson('/api/grant/claim', ['address' => $who['address'], 'nonce' => str_repeat('0', 64), 'signature' => 'x'])->assertOk()->json();
    expect($claim['status'])->toBe('unavailable');
    expect($this->sender->sent)->toBe([]);
    expect($this->getJson('/api/grant/status')->json('granted'))->toBeFalse();
});

test('a malformed claim is answered, not a server error', function () {
    $who = grantNewcomer();
    $claim = $this->actingAs($who['user'], 'sanctum')->postJson('/api/grant/claim', ['nonce' => ['x']])->assertOk()->json();
    expect($claim['status'])->toBe('invalid_signature');
});

test('the amount is capped at one MARS whatever the configuration says', function () {
    config(['welcome_grant.amount_zubrins' => 5000000000]);
    expect(app(WelcomeGrantService::class)->amount())->toBe(100000000);
});

test('the node sender uses only the named grant wallet', function () {
    config(['welcome_grant.wallet' => 'welcome-grant']);
    Process::fake(['*getbalance*' => Process::result("0.05000000\n"), '*sendtoaddress*' => Process::result(str_repeat('cd', 32)."\n")]);
    $sender = new RpcGrantSender;
    $key = new MarscoinECDSA;
    $key->generateRandomPrivateKey();

    expect($sender->availableZubrins())->toBe(5000000);
    expect($sender->send($key->getAddress(), 1000000))->toBe(str_repeat('cd', 32));
    Process::assertRan(fn ($process) => in_array('-rpcwallet=welcome-grant', $process->command)
        && in_array('sendtoaddress', $process->command) && in_array('0.01000000', $process->command));
});

test('the node sender refuses to act without a named wallet', function () {
    config(['welcome_grant.wallet' => '']);
    Process::fake();
    $sender = new RpcGrantSender;

    expect($sender->availableZubrins())->toBeNull();
    expect(fn () => $sender->send('M'.str_repeat('a', 33), 1000000))->toThrow(GrantSendException::class);
    Process::assertNothingRan();
});

test('the node sender tells a refusal from an unknown outcome', function () {
    $address = 'M'.str_repeat('a', 33);
    Process::fake(['*' => Process::result('', "error code: -6\nerror message:\nInsufficient funds", 6)]);
    try {
        (new RpcGrantSender)->send($address, 1000000);
        $this->fail('expected a refusal');
    } catch (GrantSendException $error) {
        expect($error->nothingSent)->toBeTrue();
    }

    Process::fake(['*' => Process::result('garbage', '', 0)]);
    try {
        (new RpcGrantSender)->send($address, 1000000);
        $this->fail('expected an unknown outcome');
    } catch (GrantSendException $error) {
        expect($error->nothingSent)->toBeFalse();
    }
});
