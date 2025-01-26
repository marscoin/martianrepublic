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
        Schema::create('citizen', function (Blueprint $table) {
            $table->comment('a cache table making onboarding easier');
            $table->integer('id', true);
            $table->integer('userid')->nullable()->unique('userid');
            $table->string('firstname', 50)->nullable();
            $table->string('lastname', 50)->nullable();
            $table->string('displayname', 50)->nullable();
            $table->string('shortbio', 800)->nullable();
            $table->string('avatar_link', 500)->nullable();
            $table->string('liveness_link', 500)->nullable();
            $table->string('public_address', 500)->nullable()->unique('public_address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citizen');
    }
};
