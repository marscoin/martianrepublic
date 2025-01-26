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
        Schema::create('forum_threads', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id')->index('category_id');
            $table->unsignedInteger('author_id');
            $table->string('title');
            $table->boolean('pinned')->nullable();
            $table->boolean('locked')->nullable();
            $table->integer('first_post_id')->nullable();
            $table->integer('last_post_id')->nullable();
            $table->integer('reply_count')->nullable()->default(0);
            $table->integer('proposal_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_threads');
    }
};
