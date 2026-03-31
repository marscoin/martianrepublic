<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add governance tier system to proposals table.
     * New 4-tier system: Signal, Operational, Legislative, Constitutional
     */
    public function up(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            $table->enum('tier', ['signal', 'operational', 'legislative', 'constitutional'])
                ->default('signal')->after('category');
            $table->timestamp('screening_ends_at')->nullable()->after('mined');
            $table->timestamp('voting_ends_at')->nullable()->after('screening_ends_at');
            $table->timestamp('timelock_ends_at')->nullable()->after('voting_ends_at');
            $table->timestamp('enacted_at')->nullable()->after('timelock_ends_at');
            $table->timestamp('sunset_at')->nullable()->after('enacted_at');
            $table->timestamp('amended_at')->nullable()->after('updated_at');
            $table->mediumText('original_description')->nullable()->after('description');
            $table->string('original_ipfs_hash', 150)->nullable()->after('ipfs_hash');
            $table->boolean('voting_extended')->default(false)->after('active');
            $table->timestamp('quiet_ending_triggered_at')->nullable()->after('voting_extended');
            $table->string('git_hash', 64)->nullable()->after('txid');
        });

        DB::statement("ALTER TABLE proposals MODIFY COLUMN status ENUM('draft','screening','challenged','voting','voting_extended','timelock','active','rejected','expired','withdrawn','closed','passed','sunset','submitted') DEFAULT 'screening'");

        DB::statement("UPDATE proposals SET tier = 'signal' WHERE category = 'poll'");
        DB::statement("UPDATE proposals SET tier = 'operational' WHERE category = 'regulation'");
        DB::statement("UPDATE proposals SET tier = 'legislative' WHERE category IN ('statute', 'law')");
        DB::statement("UPDATE proposals SET tier = 'constitutional' WHERE category = 'amendment'");
    }

    public function down(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            $table->dropColumn([
                'tier', 'screening_ends_at', 'voting_ends_at', 'timelock_ends_at',
                'enacted_at', 'sunset_at', 'amended_at', 'original_description',
                'original_ipfs_hash', 'voting_extended', 'quiet_ending_triggered_at', 'git_hash',
            ]);
        });
        DB::statement("ALTER TABLE proposals MODIFY COLUMN status ENUM('submitted','voting','rejected','closed','passed','expired') DEFAULT 'submitted'");
    }
};
