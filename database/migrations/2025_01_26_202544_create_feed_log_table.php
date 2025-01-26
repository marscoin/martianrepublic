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
        Schema::create('feed_log', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('block')->nullable()->index('block');
            $table->string('hash', 256)->nullable()->index('hash');
            $table->timestamp('mined')->nullable()->index('mined');
            $table->timestamp('processed_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feed_log');
    }
};
