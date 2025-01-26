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
        Schema::create('forum_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id')->default(0);
            $table->string('title');
            $table->string('description')->nullable();
            $table->integer('weight')->default(0);
            $table->integer('accepts_threads')->default(0);
            $table->integer('newest_thread_id')->nullable();
            $table->integer('latest_active_thread_id')->nullable();
            $table->integer('post_count')->default(0);
            $table->integer('thread_count')->default(0);
            $table->integer('is_private')->default(0);
            $table->integer('_lft')->default(0);
            $table->integer('_rgt')->default(0);
            $table->unsignedInteger('parent_id')->nullable();
            $table->text('color')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_categories');
    }
};
