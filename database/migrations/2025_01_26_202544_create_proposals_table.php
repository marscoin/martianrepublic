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
        Schema::create('proposals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index('user_id');
            $table->string('title', 800)->index('title');
            $table->mediumText('description');
            $table->string('category', 50)->default('');
            $table->string('discussion')->nullable();
            $table->timestamp('created_at')->nullable()->index('created_at');
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('mined')->nullable()->index('mined');
            $table->string('author', 50)->nullable()->index('author');
            $table->string('ipfs_hash', 150)->nullable();
            $table->tinyInteger('threshold')->nullable()->default(0);
            $table->tinyInteger('participation')->nullable()->default(0);
            $table->integer('duration')->nullable();
            $table->integer('expiration')->nullable();
            $table->string('txid', 128)->nullable();
            $table->string('public_address', 128)->nullable();
            $table->float('mars_paid')->nullable();
            $table->boolean('active')->nullable()->default(true);
            $table->enum('status', ['submitted', 'voting', 'rejected', 'closed', 'passed', 'expired'])->nullable()->default('submitted');
            $table->integer('votes_required')->nullable();
            $table->integer('citizen_count')->nullable();
            $table->enum('closed_reason', ['Missing Meta Data', 'Test'])->nullable();

            $table->unique(['ipfs_hash', 'txid'], 'ipfs_hash_txid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
