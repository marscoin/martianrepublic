<?php

/**
 * Tests for BADS Instrument Registry, Chain of Trust models, and controller.
 */

use App\Models\User;
use App\Models\Profile;
use App\Models\CivicWallet;
use App\Models\Bads\OversightCommittee;
use App\Models\Bads\Deputy;
use App\Models\Bads\Instrument;
use App\Models\Bads\CalibrationRecord;
use App\Models\Bads\Attestation;
use App\Models\Bads\Anomaly;
use App\Http\Controllers\InstrumentController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

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

    $schema->create('proposals', function ($table) {
        $table->integer('id', true);
        $table->integer('user_id')->nullable();
        $table->string('title', 500)->nullable();
        $table->text('description')->nullable();
        $table->string('status', 50)->default('submitted');
        $table->timestamps();
    });

    $schema->create('oversight_committees', function ($table) {
        $table->id();
        $table->string('name');
        $table->string('slug')->unique();
        $table->string('role_tag', 50)->unique();
        $table->text('description')->nullable();
        $table->unsignedInteger('proposal_id')->nullable();
        $table->string('proposal_txid', 128)->nullable();
        $table->json('device_types')->nullable();
        $table->string('status')->default('active');
        $table->timestamps();
    });

    $schema->create('deputies', function ($table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('committee_id');
        $table->string('civic_address', 100)->nullable();
        $table->string('role_tag', 50)->nullable();
        $table->unsignedInteger('appointment_proposal_id')->nullable();
        $table->string('appointment_txid', 128)->nullable();
        $table->string('status')->default('active');
        $table->timestamps();
    });

    $schema->create('instruments', function ($table) {
        $table->id();
        $table->string('address', 100)->nullable();
        $table->integer('device_type')->nullable();
        $table->string('device_type_name')->nullable();
        $table->string('device_category')->nullable();
        $table->string('make')->nullable();
        $table->string('model')->nullable();
        $table->string('serial')->nullable();
        $table->string('dice_cdi_hash', 128)->nullable();
        $table->string('status')->default('active');
        $table->unsignedBigInteger('certified_by_deputy_id')->nullable();
        $table->string('cert_txid', 128)->nullable();
        $table->string('mqtt_namespace')->nullable();
        $table->json('operational_params')->nullable();
        $table->string('location')->nullable();
        $table->string('firmware_version', 50)->nullable();
        $table->string('revoke_txid', 128)->nullable();
        $table->string('revoke_reason', 50)->nullable();
        $table->text('revoke_notes')->nullable();
        $table->timestamps();
        $table->softDeletes();
    });

    $schema->create('attestations', function ($table) {
        $table->id();
        $table->string('txid', 128)->nullable();
        $table->unsignedBigInteger('instrument_id');
        $table->integer('block_height')->nullable();
        $table->integer('reading_count')->default(0);
        $table->string('merkle_root', 128)->nullable();
        $table->string('data_cid', 200)->nullable();
        $table->text('signature')->nullable();
        $table->boolean('verified')->default(false);
        $table->timestamp('batch_start')->nullable();
        $table->timestamp('batch_end')->nullable();
        $table->timestamps();
    });

    $schema->create('anomalies', function ($table) {
        $table->id();
        $table->unsignedBigInteger('attestation_id')->nullable();
        $table->unsignedBigInteger('instrument_id');
        $table->integer('reading_index')->nullable();
        $table->string('anomaly_type', 50)->nullable();
        $table->string('severity', 20)->nullable();
        $table->string('status', 20)->default('open');
        $table->unsignedBigInteger('reviewed_by_user_id')->nullable();
        $table->text('notes')->nullable();
        $table->timestamps();
    });

    $schema->create('calibration_records', function ($table) {
        $table->id();
        $table->unsignedBigInteger('instrument_id');
        $table->unsignedBigInteger('calibrator_deputy_id')->nullable();
        $table->string('txid', 128)->nullable();
        $table->string('new_dice_cdi_hash', 128)->nullable();
        $table->json('calibration_data')->nullable();
        $table->timestamp('calibrated_at')->nullable();
        $table->timestamp('due_at')->nullable();
        $table->timestamps();
    });
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
