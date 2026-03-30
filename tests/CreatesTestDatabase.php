<?php

namespace Tests;

use Illuminate\Support\Facades\Schema;

/**
 * Shared database schema setup for tests.
 * Call the methods you need in your beforeEach() — only creates the tables you ask for.
 */
trait CreatesTestDatabase
{
    protected function createCoreTables(): void
    {
        $schema = Schema::connection('sqlite');
        $schema->dropAllTables();

        $this->createUsersTable($schema);
        $this->createSessionsTable($schema);
        $this->createProfileTable($schema);
    }

    protected function createUsersTable($schema = null): void
    {
        $schema ??= Schema::connection('sqlite');
        $schema->create('users', function ($table) {
            $table->id();
            $table->string('fullname');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('status', 20)->default('active');
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
            $table->rememberToken();
        });
    }

    protected function createSessionsTable($schema = null): void
    {
        $schema ??= Schema::connection('sqlite');
        $schema->create('sessions', function ($table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    protected function createProfileTable($schema = null): void
    {
        $schema ??= Schema::connection('sqlite');
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
    }

    protected function createWalletTables($schema = null): void
    {
        $schema ??= Schema::connection('sqlite');
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

        $schema->create('hd_wallet', function ($table) {
            $table->integer('id', true);
            $table->integer('user_id')->nullable();
            $table->string('wallet_type', 50)->nullable();
            $table->text('backup')->nullable();
            $table->text('encrypted_seed')->nullable();
            $table->string('public_addr', 100)->nullable()->unique();
            $table->timestamp('opened_at')->nullable();
            $table->timestamps();
        });
    }

    protected function createCitizenTable($schema = null): void
    {
        $schema ??= Schema::connection('sqlite');
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
    }

    protected function createProposalTables($schema = null): void
    {
        $schema ??= Schema::connection('sqlite');
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
    }

    protected function createBallotTable($schema = null): void
    {
        $schema ??= Schema::connection('sqlite');
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
    }

    protected function createForumTables($schema = null): void
    {
        $schema ??= Schema::connection('sqlite');
        $schema->create('forum_categories', function ($table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#00e4ff');
            $table->integer('weight')->default(0);
            $table->boolean('accepts_threads')->default(true);
            $table->boolean('is_private')->default(false);
            $table->integer('thread_count')->default(0);
            $table->integer('post_count')->default(0);
            $table->timestamps();
        });

        $schema->create('forum_threads', function ($table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('author_id');
            $table->string('title');
            $table->string('slug')->nullable();
            $table->boolean('pinned')->default(false);
            $table->boolean('locked')->default(false);
            $table->integer('reply_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        $schema->create('forum_posts', function ($table) {
            $table->id();
            $table->unsignedBigInteger('thread_id');
            $table->unsignedBigInteger('author_id');
            $table->text('body');
            $table->integer('sequence')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    protected function createFeedTable($schema = null): void
    {
        $schema ??= Schema::connection('sqlite');
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
    }

    protected function createInventoryTable($schema = null): void
    {
        $schema ??= Schema::connection('sqlite');
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
    }

    protected function createPublicationTable($schema = null): void
    {
        $schema ??= Schema::connection('sqlite');
        $schema->create('publications', function ($table) {
            $table->integer('id', true);
            $table->integer('userid')->nullable();
            $table->string('title', 500)->nullable();
            $table->text('content')->nullable();
            $table->string('ipfs_hash', 200)->nullable();
            $table->timestamps();
        });
    }

    protected function createBadsTables($schema = null): void
    {
        $schema ??= Schema::connection('sqlite');

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
    }

    /**
     * Create all tables — use when you need a full database.
     */
    protected function createAllTables(): void
    {
        $schema = Schema::connection('sqlite');
        $schema->dropAllTables();

        $this->createUsersTable($schema);
        $this->createSessionsTable($schema);
        $this->createProfileTable($schema);
        $this->createWalletTables($schema);
        $this->createCitizenTable($schema);
        $this->createFeedTable($schema);
        $this->createProposalTables($schema);
        $this->createBallotTable($schema);
        $this->createForumTables($schema);
        $this->createInventoryTable($schema);
        $this->createPublicationTable($schema);
        $this->createBadsTables($schema);
    }
}
