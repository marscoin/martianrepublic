<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->integer('id', true);
            $table->enum('vote', ['Y', 'N', 'A'])->nullable();
            $table->integer('proposal_id')->nullable();
            $table->string('txid', 500)->nullable();
            $table->timestamp('mined')->nullable();
            $table->integer('block')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->unique(['vote', 'txid'], 'vote_txid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
