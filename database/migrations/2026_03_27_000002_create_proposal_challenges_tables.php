<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tier challenge system: citizens can challenge a proposal's tier classification
     * during the 3-sol screening period. Reclassification is upward only.
     */
    public function up(): void
    {
        Schema::create('proposal_challenges', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('proposal_id');
            $table->unsignedInteger('challenger_user_id');
            $table->enum('current_tier', ['signal', 'operational', 'legislative', 'constitutional']);
            $table->enum('proposed_tier', ['signal', 'operational', 'legislative', 'constitutional']);
            $table->text('reason')->nullable();
            $table->enum('status', ['open', 'upheld', 'reclassified', 'expired'])->default('open');
            $table->unsignedInteger('votes_keep')->default(0);
            $table->unsignedInteger('votes_reclassify')->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index('proposal_id');
            $table->index('status');
        });

        Schema::create('challenge_votes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('challenge_id');
            $table->unsignedInteger('user_id');
            $table->enum('vote', ['keep', 'reclassify']);
            $table->timestamps();

            $table->unique(['challenge_id', 'user_id']);
            $table->index('challenge_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('challenge_votes');
        Schema::dropIfExists('proposal_challenges');
    }
};
