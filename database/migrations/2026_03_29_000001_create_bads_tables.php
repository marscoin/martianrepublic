<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Oversight committees — founded by congressional proposals
        Schema::create('oversight_committees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('role_tag', 50)->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('proposal_id')->nullable();
            $table->string('proposal_txid', 128)->nullable();
            $table->json('device_types')->nullable();
            $table->enum('status', ['active', 'dissolved'])->default('active');
            $table->timestamps();

            $table->foreign('proposal_id')->references('id')->on('proposals')->nullOnDelete();
        });

        // Deputies — citizens appointed by Congress with scoped certification authority
        Schema::create('deputies', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->index();
            $table->unsignedBigInteger('committee_id')->index();
            $table->string('civic_address', 128);
            $table->string('role_tag', 50);
            $table->unsignedInteger('appointment_proposal_id')->nullable();
            $table->string('appointment_txid', 128)->nullable();
            $table->enum('status', ['active', 'expired', 'recalled'])->default('active');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('committee_id')->references('id')->on('oversight_committees');
        });

        // Instruments — certified colony devices
        Schema::create('instruments', function (Blueprint $table) {
            $table->id();
            $table->string('address', 128)->unique();
            $table->string('did')->nullable();
            $table->string('pubkey_hex', 130)->nullable();
            $table->tinyInteger('crypto_suite_id')->default(1);
            $table->unsignedSmallInteger('device_type');
            $table->string('device_type_name');
            $table->string('device_category', 50);
            $table->string('make', 255)->nullable();
            $table->string('model', 255)->nullable();
            $table->string('serial', 255);
            $table->string('dice_cdi_hash', 64)->nullable();
            $table->enum('status', ['active', 'revoked', 'calibration_due', 'calibration_expired'])->default('active');
            $table->unsignedBigInteger('certified_by_deputy_id')->nullable();
            $table->string('cert_txid', 128)->nullable();
            $table->string('mqtt_namespace', 255)->nullable();
            $table->json('operational_params')->nullable();
            $table->string('location', 255)->nullable();
            $table->string('firmware_version', 50)->nullable();
            $table->string('revoke_txid', 128)->nullable();
            $table->tinyInteger('revoke_reason')->nullable();
            $table->text('revoke_notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('certified_by_deputy_id')->references('id')->on('deputies')->nullOnDelete();
            $table->index(['status', 'device_category']);
        });

        // Attestations — Merkle root batches anchored to blockchain
        Schema::create('attestations', function (Blueprint $table) {
            $table->id();
            $table->string('txid', 128)->unique()->nullable();
            $table->unsignedBigInteger('instrument_id')->index();
            $table->integer('block_height')->nullable();
            $table->integer('reading_count')->default(0);
            $table->string('merkle_root', 64);
            $table->string('data_cid', 100)->nullable();
            $table->string('prev_attestation_txid', 128)->nullable();
            $table->binary('signature')->nullable();
            $table->boolean('verified')->default(false);
            $table->timestamp('batch_start')->nullable();
            $table->timestamp('batch_end')->nullable();
            $table->timestamps();

            $table->foreign('instrument_id')->references('id')->on('instruments');
        });

        // Attestation readings — individual sensor values within a batch
        Schema::create('attestation_readings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attestation_id')->index();
            $table->integer('reading_index');
            $table->json('metrics');
            $table->enum('quality', ['good', 'uncertain', 'bad'])->default('good');
            $table->string('reading_hash', 64)->nullable();
            $table->timestamp('recorded_at')->nullable();
            $table->timestamps();

            $table->foreign('attestation_id')->references('id')->on('attestations')->cascadeOnDelete();
        });

        // Anomalies — flagged readings
        Schema::create('anomalies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attestation_id')->nullable();
            $table->unsignedBigInteger('instrument_id')->index();
            $table->integer('reading_index')->nullable();
            $table->enum('anomaly_type', ['out_of_range', 'sudden_change', 'drift', 'gap', 'stale']);
            $table->enum('severity', ['info', 'warning', 'critical'])->default('warning');
            $table->enum('status', ['unreviewed', 'acknowledged', 'false_positive', 'confirmed'])->default('unreviewed');
            $table->unsignedInteger('reviewed_by_user_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('attestation_id')->references('id')->on('attestations')->nullOnDelete();
            $table->foreign('instrument_id')->references('id')->on('instruments');
        });

        // Calibration records
        Schema::create('calibration_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instrument_id')->index();
            $table->unsignedBigInteger('calibrator_deputy_id')->nullable();
            $table->string('txid', 128)->nullable();
            $table->string('new_dice_cdi_hash', 64)->nullable();
            $table->json('calibration_data')->nullable();
            $table->timestamp('calibrated_at');
            $table->timestamp('due_at')->nullable();
            $table->timestamps();

            $table->foreign('instrument_id')->references('id')->on('instruments');
            $table->foreign('calibrator_deputy_id')->references('id')->on('deputies')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calibration_records');
        Schema::dropIfExists('anomalies');
        Schema::dropIfExists('attestation_readings');
        Schema::dropIfExists('attestations');
        Schema::dropIfExists('instruments');
        Schema::dropIfExists('deputies');
        Schema::dropIfExists('oversight_committees');
    }
};
