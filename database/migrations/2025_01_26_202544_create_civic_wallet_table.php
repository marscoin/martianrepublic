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
        Schema::create('civic_wallet', function (Blueprint $table) {
            $table->comment('Primary martian republic wallet. ');
            $table->increments('id');
            $table->integer('user_id')->unique('user_id');
            $table->string('wallet_type', 500);
            $table->boolean('backup')->default(false);
            $table->string('encrypted_seed', 164)->nullable();
            $table->string('public_addr', 50)->nullable()->unique('public_addr');
            $table->timestamps();
            $table->timestamp('opened_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('civic_wallet');
    }
};
