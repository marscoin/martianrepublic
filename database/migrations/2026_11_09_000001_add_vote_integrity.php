<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Votes are now counted only when they spend a ballot issued to citizens.
     * Each counted vote records that ballot, and each proposal records which
     * citizens have been credited through which issuance, so that no citizen
     * is counted twice.
     */
    public function up(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->string('ballot_txid', 64)->nullable()->after('txid');
            $table->integer('ballot_vout')->nullable()->after('ballot_txid');
            $table->index(['proposal_id', 'ballot_txid'], 'votes_proposal_ballot');
        });

        Schema::create('ballot_credits', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('proposal_id');
            $table->string('join_txid', 64);
            $table->string('address', 64);
            $table->timestamp('created_at')->nullable();

            $table->unique(['proposal_id', 'address'], 'ballot_credits_citizen');
            $table->index(['proposal_id', 'join_txid'], 'ballot_credits_issuance');
        });

        // Proposals whose voting window closed before results were computed
        // (smoke tests run with minute-long windows) keep the outcome they
        // have shown since: expired. They are not decided retroactively.
        DB::table('proposals')
            ->where('status', 'voting')
            ->whereNotNull('voting_ends_at')
            ->where('voting_ends_at', '<', now())
            ->update(['status' => 'expired', 'active' => 0]);
    }

    public function down(): void
    {
        Schema::dropIfExists('ballot_credits');
        Schema::table('votes', function (Blueprint $table) {
            $table->dropIndex('votes_proposal_ballot');
            $table->dropColumn(['ballot_txid', 'ballot_vout']);
        });
    }
};
