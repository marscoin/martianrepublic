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
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('userid')->index();
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->string('category', 100)->nullable()->index();
            $table->integer('quantity')->default(1);
            $table->string('unit', 50)->nullable();
            $table->string('location', 255)->nullable();
            $table->string('condition', 50)->nullable();
            $table->string('ipfs_hash', 100)->nullable();
            $table->string('notarization', 150)->nullable();
            $table->timestamp('notarized_at')->nullable();
            $table->string('image_path', 500)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
