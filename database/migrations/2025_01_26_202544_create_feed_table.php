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
        Schema::create('feed', function (Blueprint $table) {
            $table->comment('cached notarized activity feed on blockchain');
            $table->integer('id', true);
            $table->string('address', 50)->nullable()->index('address');
            $table->integer('userid')->nullable()->index('userid');
            $table->string('tag', 10)->nullable()->index('tag');
            $table->string('message', 765)->nullable()->index('message');
            $table->string('embedded_link', 250)->nullable();
            $table->string('txid', 250)->nullable();
            $table->integer('blockid')->nullable()->index('blockid');
            $table->timestamp('mined')->nullable()->index('mined');
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable();
            $table->timestamp('created_at')->nullable();

            $table->unique(['address', 'tag', 'txid'], 'address_tag_txid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feed');
    }
};
