<?php

use App\Models\Citizen;
use App\Models\CivicWallet;
use App\Models\Feed;
use App\Models\HDWallet;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\CreatesTestDatabase;
use Tests\TestCase;

uses(TestCase::class, CreatesTestDatabase::class)->beforeEach(function () {
    $this->createCoreTables();
    $this->createWalletTables();
    $this->createCitizenTable();
    $this->createFeedTable();
    (require database_path('migrations/2026_10_09_000001_create_organization_workspaces.php'))->up();
});

function applicant(string $address, array $profile = [], bool $civic = true): User
{
    $user = User::create(['fullname' => 'Ada Applicant', 'email' => $address.'@martianrepublic.org', 'password' => Hash::make('x')]);
    Profile::create(array_merge(['userid' => $user->id, 'has_application' => 1], $profile));
    if ($civic) {
        CivicWallet::create(['user_id' => $user->id, 'wallet_type' => 'MARS', 'public_addr' => $address]);
    }
    $citizen = new Citizen;
    $citizen->forceFill(['userid' => $user->id, 'firstname' => 'Ada', 'lastname' => 'Applicant', 'public_address' => $address])->save();

    return $user;
}

function citizenship($test, User $user): array
{
    return $test->actingAs($user, 'sanctum')->getJson('/api/workspace')->assertOk()->json('citizenship');
}

// ============================================================
// GET /api/workspace: where the application really is
// ============================================================

test('saved details alone are a draft, while status keeps its old value', function () {
    $citizenship = citizenship($this, applicant('MDraftAddress'));

    expect($citizenship['status'])->toBe('submitted');
    expect($citizenship['application_stage'])->toBe('draft');
});

test('nothing saved yet has no application stage', function () {
    $citizenship = citizenship($this, applicant('MNewAddress', ['has_application' => 0]));

    expect($citizenship['status'])->toBe('new');
    expect($citizenship['application_stage'])->toBeNull();
});

test('a reported broadcast that is not mined is published and pending', function () {
    $user = applicant('MPendingAddress');
    Feed::create(['userid' => $user->id, 'address' => 'MPendingAddress', 'tag' => 'GP', 'txid' => str_repeat('a', 64)]);

    expect(citizenship($this, $user)['application_stage'])->toBe('published_pending');
});

test('a mined application is confirmed', function (array $profile, string $status) {
    $citizenship = citizenship($this, applicant('MConfirmedAddress', $profile));

    expect($citizenship['status'])->toBe($status);
    expect($citizenship['application_stage'])->toBe('confirmed');
})->with([
    'general public' => [['general_public' => 1, 'has_application' => 0], 'public_member'],
    'citizen' => [['general_public' => 1, 'citizen' => 1, 'has_application' => 0], 'citizen'],
]);

// ============================================================
// GET /api/feed/applicant: an address to send Marscoin to
// ============================================================

test('applicants registered in the app are listed with their civic address', function () {
    applicant('MCivicOnlyAddress');

    $row = $this->getJson('/api/feed/applicant')->assertOk()->json('data.0');
    expect($row['fullname'])->toBe('Ada Applicant');
    expect($row['address'])->toBe('MCivicOnlyAddress');
});

test('the civic address is preferred and the hd wallet address remains the fallback', function () {
    $both = applicant('MCivicAddress');
    HDWallet::create(['user_id' => $both->id, 'wallet_type' => 'MARS', 'public_addr' => 'MGeneralAddress']);

    expect($this->getJson('/api/feed/applicant')->assertOk()->json('data.0.address'))->toBe('MCivicAddress');

    $legacy = applicant('MLegacyCitizenRow', [], false);
    HDWallet::create(['user_id' => $legacy->id, 'wallet_type' => 'MARS', 'public_addr' => 'MLegacyHdAddress']);

    expect($this->getJson('/api/feed/applicant')->assertOk()->json('data.0.address'))->toBe('MLegacyHdAddress');
});
