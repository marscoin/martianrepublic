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
        Schema::create('publications', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('userid')->nullable()->index('userid');
            $table->string('title', 500)->nullable();
            $table->string('ipfs_hash', 50)->nullable()->index('ipfs_hash');
            $table->string('local_path', 500)->nullable();
            $table->string('notarization', 150)->nullable();
            $table->timestamp('notarized_at')->nullable()->index('notarized_at');
            $table->integer('blockid')->nullable()->index('blockid');
            $table->timestamp('updated_at')->nullable()->useCurrent();
            $table->timestamp('created_at')->nullable()->useCurrent();

            $table->unique(['userid', 'notarization'], 'userid_notarization');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};
