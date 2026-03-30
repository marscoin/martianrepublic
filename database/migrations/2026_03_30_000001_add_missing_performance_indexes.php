<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // proposals.status — used in phase sync, tab filtering, every page load
        Schema::table('proposals', function (Blueprint $table) {
            $table->index('status');
        });

        // ballots.userid + proposalid — used in every ballot lookup
        Schema::table('ballots', function (Blueprint $table) {
            $table->index('userid');
            $table->index('proposalid');
            $table->index(['userid', 'status']);
        });

        // votes.proposal_id — used in vote tallying
        Schema::table('votes', function (Blueprint $table) {
            $table->index('proposal_id');
        });

        // forum_posts.author_id — used in joins with users
        Schema::table('forum_posts', function (Blueprint $table) {
            $table->index('author_id');
        });

        // forum_threads.author_id — used in thread listing joins
        Schema::table('forum_threads', function (Blueprint $table) {
            $table->index('author_id');
        });
    }

    public function down(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });
        Schema::table('ballots', function (Blueprint $table) {
            $table->dropIndex(['userid']);
            $table->dropIndex(['proposalid']);
            $table->dropIndex(['userid', 'status']);
        });
        Schema::table('votes', function (Blueprint $table) {
            $table->dropIndex(['proposal_id']);
        });
        Schema::table('forum_posts', function (Blueprint $table) {
            $table->dropIndex(['author_id']);
        });
        Schema::table('forum_threads', function (Blueprint $table) {
            $table->dropIndex(['author_id']);
        });
    }
};
