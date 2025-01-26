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
        Schema::create('ballots', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('userid')->nullable();
            $table->integer('proposalid')->nullable();
            $table->integer('btxid')->nullable();
            $table->enum('status', ['requested', 'in_shuffle', 'received', 'used'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ballots');
    }
};
