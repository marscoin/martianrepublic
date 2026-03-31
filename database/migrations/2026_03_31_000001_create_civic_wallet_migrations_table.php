<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('civic_wallet_migrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('old_address', 100);
            $table->string('new_address', 100);
            $table->string('migration_txid', 200)->nullable();
            $table->string('status', 20)->default('pending'); // pending, confirmed, failed
            $table->text('old_encrypted_seed')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('old_address');
            $table->index('new_address');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('civic_wallet_migrations');
    }
};
