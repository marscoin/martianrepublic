<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // One row per user and per address, ever. The row is written before
        // any coins move, so the unique keys are what prevents double payment.
        Schema::create('welcome_grants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('address', 64)->unique();
            $table->unsignedBigInteger('amount_zubrins');
            // pending: reserved, send in progress. sent: paid. failed: the node
            // refused, safe to retry. uncertain: outcome unknown, never retried
            // automatically.
            $table->string('status', 12)->default('pending');
            $table->string('txid', 64)->nullable();
            $table->string('ip_hash', 64)->nullable();
            $table->unsignedInteger('attempts')->default(1);
            $table->string('last_error', 255)->nullable();
            $table->timestamp('attempted_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            $table->index(['attempted_at', 'status']);
            $table->index(['ip_hash', 'attempted_at']);
        });

        Schema::create('welcome_grant_nonces', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('address', 64);
            $table->string('nonce', 64)->unique();
            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('welcome_grant_nonces');
        Schema::dropIfExists('welcome_grants');
    }
};
