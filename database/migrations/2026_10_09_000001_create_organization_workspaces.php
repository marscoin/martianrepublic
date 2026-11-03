<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('owner_id')->index();
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();
        });
        Schema::create('organization_members', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('organization_id')->constrained('organizations')->cascadeOnDelete();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('role', 12)->default('member');
            $table->string('status', 12)->default('invited');
            $table->unsignedBigInteger('invited_by');
            $table->timestamp('joined_at')->nullable();
            $table->timestamps();
            $table->unique(['organization_id', 'user_id']);
        });
        Schema::create('organization_tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained('organizations')->cascadeOnDelete();
            $table->string('title', 160);
            $table->text('details')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('assigned_to')->nullable()->index();
            $table->date('due_date')->nullable();
            $table->string('status', 12)->default('open');
            $table->unsignedInteger('version')->default(1);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->index(['organization_id', 'status', 'created_at']);
        });
        Schema::create('organization_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('organization_id')->constrained('organizations')->cascadeOnDelete();
            $table->uuid('task_id')->nullable();
            $table->unsignedBigInteger('actor_id');
            $table->string('kind', 30);
            $table->text('body');
            $table->json('metadata')->nullable();
            $table->timestamp('created_at');
            $table->index(['organization_id', 'id']);
        });
        Schema::create('organization_attachments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained('organizations')->cascadeOnDelete();
            $table->unsignedBigInteger('uploaded_by');
            $table->unsignedBigInteger('activity_id')->nullable()->index();
            $table->string('path');
            $table->string('mime', 50);
            $table->unsignedInteger('size');
            $table->string('sha256', 64);
            $table->timestamp('created_at');
        });
        // Receipts are committed in the same transaction as the change. They
        // remain available after restarts, including a lost mobile response.
        Schema::create('organization_mutations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->uuid('client_id');
            $table->string('operation', 180);
            $table->string('request_hash', 64);
            $table->json('response');
            $table->unsignedSmallInteger('status_code');
            $table->timestamp('created_at');
            $table->unique(['user_id', 'client_id']);
        });
    }

    public function down(): void
    {
        foreach (['organization_mutations', 'organization_attachments', 'organization_activities', 'organization_tasks', 'organization_members', 'organizations'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
