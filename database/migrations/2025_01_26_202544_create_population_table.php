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
        Schema::create('population', function (Blueprint $table) {
            $table->comment('size of the population at a given block for census operations');
            $table->integer('id', true);
            $table->integer('block')->default(0);
            $table->integer('general_public_count')->default(0);
            $table->integer('citizen_count')->default(0);
            $table->timestamp('created_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('population');
    }
};
