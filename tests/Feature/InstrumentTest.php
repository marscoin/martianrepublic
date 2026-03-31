<?php

/**
 * Tests for BADS Instrument Registry, Chain of Trust models, and controller.
 */

use App\Http\Controllers\InstrumentController;
use App\Models\Bads\Anomaly;
use App\Models\Bads\Attestation;
use App\Models\Bads\CalibrationRecord;
use App\Models\Bads\Deputy;
use App\Models\Bads\Instrument;
use App\Models\Bads\OversightCommittee;
use App\Models\CivicWallet;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\CreatesTestDatabase;
use Tests\TestCase;

uses(TestCase::class, CreatesTestDatabase::class)->beforeEach(function () {
    $this->createCoreTables();
    $this->createWalletTables();
    $this->createProposalTables();
    $this->createBadsTables();
});

function createTestDeputy(): array
{
    $user = User::create([
        'fullname' => 'Deputy Pioneer',
        'email' => 'deputy@test.mars',
        'password' => Hash::make('password'),
    ]);

    Profile::create([
        'userid' => $user->id,
        'openchallenge' => 0,
        'twofaset' => 1,
        'citizen' => 1,
        'civic_wallet_open' => 1,
    ]);

    CivicWallet::create([
        'user_id' => $user->id,
        'wallet_type' => 'civic',
        'public_addr' => 'MTestDeputyAddr123',
    ]);

    $committee = OversightCommittee::create([
        'name' => 'Atmospheric Systems',
        'slug' => 'atmospheric',
        'role_tag' => 'DEPUTY_ATM',
        'description' => 'Oversees atmospheric monitoring',
        'status' => 'active',
    ]);

    $deputy = Deputy::create([
        'user_id' => $user->id,
        'committee_id' => $committee->id,
        'civic_address' => 'MTestDeputyAddr123',
        'role_tag' => 'DEPUTY_ATM',
        'status' => 'active',
    ]);

    return [$user, $committee, $deputy];
}

test('InstrumentController exists with all CRUD methods', function () {
    expect(class_exists(InstrumentController::class))->toBeTrue();

    $reflection = new ReflectionClass(InstrumentController::class);
    expect($reflection->hasMethod('index'))->toBeTrue();
    expect($reflection->hasMethod('show'))->toBeTrue();
    expect($reflection->hasMethod('create'))->toBeTrue();
    expect($reflection->hasMethod('store'))->toBeTrue();
    expect($reflection->hasMethod('committees'))->toBeTrue();
    expect($reflection->hasMethod('chainOfTrust'))->toBeTrue();
});

test('instrument → deputy → committee chain of trust resolves', function () {
    [$user, $committee, $deputy] = createTestDeputy();

    $instrument = Instrument::create([
        'address' => 'MInstrumentAddr001',
        'device_type' => 0x0101,
        'device_type_name' => 'O2 Partial Pressure Sensor',
        'device_category' => 'atmospheric',
        'serial' => 'ATM-O2-001',
        'certified_by_deputy_id' => $deputy->id,
        'status' => 'active',
    ]);

    // Full chain
    expect($instrument->certifiedBy)->not->toBeNull();
    expect($instrument->certifiedBy->id)->toBe($deputy->id);
    expect($instrument->certifiedBy->user->fullname)->toBe('Deputy Pioneer');
    expect($instrument->certifiedBy->committee->name)->toBe('Atmospheric Systems');
});

test('committee hasManyThrough instruments via deputies', function () {
    [$user, $committee, $deputy] = createTestDeputy();

    Instrument::create([
        'address' => 'MInstr001', 'device_type' => 0x0101,
        'device_type_name' => 'O2 Sensor', 'device_category' => 'atmospheric',
        'serial' => 'S001', 'certified_by_deputy_id' => $deputy->id, 'status' => 'active',
    ]);

    Instrument::create([
        'address' => 'MInstr002', 'device_type' => 0x0102,
        'device_type_name' => 'CO2 Sensor', 'device_category' => 'atmospheric',
        'serial' => 'S002', 'certified_by_deputy_id' => $deputy->id, 'status' => 'active',
    ]);

    expect($committee->deputies)->toHaveCount(1);
    expect($committee->instruments)->toHaveCount(2);
});

test('calibration record links instrument and calibrating deputy', function () {
    [$user, $committee, $deputy] = createTestDeputy();

    $instrument = Instrument::create([
        'address' => 'MInstrCal001', 'device_type' => 0x0101,
        'device_type_name' => 'O2 Sensor', 'device_category' => 'atmospheric',
        'serial' => 'CAL001', 'certified_by_deputy_id' => $deputy->id, 'status' => 'active',
    ]);

    $cal = CalibrationRecord::create([
        'instrument_id' => $instrument->id,
        'calibrator_deputy_id' => $deputy->id,
        'calibrated_at' => now(),
        'due_at' => now()->addMonths(6),
    ]);

    expect($cal->instrument->serial)->toBe('CAL001');
    expect($cal->calibrator->user->fullname)->toBe('Deputy Pioneer');
});

test('instrument attestations and anomalies are tracked', function () {
    [$user, $committee, $deputy] = createTestDeputy();

    $instrument = Instrument::create([
        'address' => 'MAttest001', 'device_type' => 0x0801,
        'device_type_name' => 'External Temperature Sensor', 'device_category' => 'environmental',
        'serial' => 'ENV001', 'certified_by_deputy_id' => $deputy->id, 'status' => 'active',
    ]);

    $attestation = Attestation::create([
        'instrument_id' => $instrument->id,
        'reading_count' => 100,
        'merkle_root' => hash('sha256', 'test_merkle'),
        'verified' => true,
    ]);

    $anomaly = Anomaly::create([
        'attestation_id' => $attestation->id,
        'instrument_id' => $instrument->id,
        'anomaly_type' => 'spike',
        'severity' => 'medium',
        'status' => 'open',
    ]);

    expect($instrument->attestations)->toHaveCount(1);
    expect($instrument->anomalies)->toHaveCount(1);
    expect($attestation->anomalies->first()->severity)->toBe('medium');
});

test('device type constants are properly defined', function () {
    expect(Instrument::DEVICE_TYPES)->toBeArray();
    expect(Instrument::DEVICE_TYPES[0x0101])->toBe('O2 Partial Pressure Sensor');
    expect(Instrument::DEVICE_TYPES[0x0803])->toBe('Seismometer');
    expect(count(Instrument::DEVICE_TYPES))->toBe(17);
});

test('revoke reasons are properly defined', function () {
    expect(Instrument::REVOKE_REASONS)->toBeArray();
    expect(Instrument::REVOKE_REASONS[0x01])->toBe('Malfunction');
    expect(Instrument::REVOKE_REASONS[0x04])->toBe('Compromised');
    expect(count(Instrument::REVOKE_REASONS))->toBe(8);
});

test('device categories in controller match model types', function () {
    $categories = InstrumentController::DEVICE_CATEGORIES;

    expect($categories)->toHaveKey('atmospheric');
    expect($categories)->toHaveKey('power');
    expect($categories)->toHaveKey('agricultural');
    expect($categories)->toHaveKey('medical');
    expect($categories)->toHaveKey('environmental');

    // Verify category prefix → device type mapping
    expect($categories['atmospheric']['prefix'])->toBe(0x01);
    expect($categories['power']['prefix'])->toBe(0x02);
});

test('soft deleted instruments are excluded from queries', function () {
    [$user, $committee, $deputy] = createTestDeputy();

    $active = Instrument::create([
        'address' => 'MActive001', 'device_type' => 0x0101,
        'device_type_name' => 'Active Sensor', 'device_category' => 'atmospheric',
        'serial' => 'ACT001', 'certified_by_deputy_id' => $deputy->id, 'status' => 'active',
    ]);

    $revoked = Instrument::create([
        'address' => 'MRevoked001', 'device_type' => 0x0102,
        'device_type_name' => 'Revoked Sensor', 'device_category' => 'atmospheric',
        'serial' => 'REV001', 'certified_by_deputy_id' => $deputy->id, 'status' => 'revoked',
    ]);
    $revoked->delete(); // soft delete

    expect(Instrument::count())->toBe(1);
    expect(Instrument::withTrashed()->count())->toBe(2);
});
