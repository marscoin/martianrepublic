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
        Schema::create('profile', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('userid')->nullable()->unique('userid');
            $table->integer('twofaset')->nullable();
            $table->string('twofakey', 500)->nullable();
            $table->integer('openchallenge')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->integer('wallet_open')->default(0)->index('wallet_open');
            $table->integer('civic_wallet_open')->default(0)->index('civic_wallet_open');
            $table->integer('general_public')->nullable()->index('general_public');
            $table->integer('endorse_cnt')->nullable();
            $table->integer('citizen')->nullable()->index('citizen');
            $table->integer('has_application')->nullable()->default(0)->index('has_application');
            $table->integer('signed_eula')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile');
    }
};
