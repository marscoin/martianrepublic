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
        Schema::create('script_info', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('script_name', 50)->nullable();
            $table->enum('status', ['enabled', 'disabled'])->nullable();
            $table->string('notes', 500)->nullable();
            $table->string('display_name', 250)->nullable();
            $table->timestamp('last_updated')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('script_info');
    }
};
