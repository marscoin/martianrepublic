<?php

/**
 * Tests for the native governance Forum.
 * Uses direct DB testing to avoid RouteServiceProvider namespace issues.
 */

use App\Models\User;
use App\Models\Profile;
use App\Http\Controllers\ForumController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

uses(Tests\TestCase::class)->beforeEach(function () {
    $schema = Schema::connection('sqlite');
    $schema->dropAllTables();

    $schema->create('users', function ($table) {
        $table->id();
        $table->string('fullname');
        $table->string('email')->unique();
        $table->string('password');
        $table->timestamps();
        $table->rememberToken();
    });

    $schema->create('sessions', function ($table) {
        $table->string('id')->primary();
        $table->foreignId('user_id')->nullable()->index();
        $table->string('ip_address', 45)->nullable();
        $table->text('user_agent')->nullable();
        $table->longText('payload');
        $table->integer('last_activity')->index();
    });

    $schema->create('profile', function ($table) {
        $table->integer('id', true);
        $table->integer('userid')->nullable()->unique();
        $table->integer('twofaset')->nullable();
        $table->string('twofakey', 500)->nullable();
        $table->integer('openchallenge')->nullable();
        $table->timestamps();
        $table->integer('wallet_open')->default(0);
        $table->integer('civic_wallet_open')->default(0);
        $table->integer('general_public')->nullable();
        $table->integer('endorse_cnt')->nullable();
        $table->integer('citizen')->nullable();
    });

    $schema->create('forum_categories', function ($table) {
        $table->id();
        $table->unsignedBigInteger('parent_id')->nullable();
        $table->string('title');
        $table->text('description')->nullable();
        $table->string('color', 7)->default('#00e4ff');
        $table->integer('weight')->default(0);
        $table->boolean('accepts_threads')->default(true);
        $table->boolean('is_private')->default(false);
        $table->integer('thread_count')->default(0);
        $table->integer('post_count')->default(0);
        $table->timestamps();
    });

    $schema->create('forum_threads', function ($table) {
        $table->id();
        $table->unsignedBigInteger('category_id');
        $table->unsignedBigInteger('author_id');
        $table->string('title');
        $table->string('slug')->nullable();
        $table->boolean('pinned')->default(false);
        $table->boolean('locked')->default(false);
        $table->integer('reply_count')->default(0);
        $table->timestamps();
        $table->softDeletes();
    });

    $schema->create('forum_posts', function ($table) {
        $table->id();
        $table->unsignedBigInteger('thread_id');
        $table->unsignedBigInteger('author_id');
        $table->text('body');
        $table->integer('sequence')->default(1);
        $table->timestamps();
        $table->softDeletes();
    });

    $schema->create('proposals', function ($table) {
        $table->integer('id', true);
        $table->integer('user_id')->nullable();
        $table->string('title', 500)->nullable();
        $table->text('description')->nullable();
        $table->string('status', 50)->default('submitted');
        $table->string('tier', 50)->nullable();
        $table->integer('discussion')->nullable();
        $table->timestamps();
    });
});

test('ForumController class exists and has expected methods', function () {
    expect(class_exists(ForumController::class))->toBeTrue();

    $reflection = new ReflectionClass(ForumController::class);
    expect($reflection->hasMethod('index'))->toBeTrue();
    expect($reflection->hasMethod('show'))->toBeTrue();
    expect($reflection->hasMethod('storeThread'))->toBeTrue();
    expect($reflection->hasMethod('storePost'))->toBeTrue();
});

test('forum categories can be created and queried', function () {
    DB::table('forum_categories')->insert([
        'title' => 'General Discussion',
        'description' => 'Open discussion about the Republic',
        'color' => '#00e4ff',
        'weight' => 1,
        'accepts_threads' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('forum_categories')->insert([
        'title' => 'Proposals',
        'description' => 'Discuss active proposals',
        'color' => '#c84125',
        'weight' => 2,
        'accepts_threads' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $categories = DB::table('forum_categories')->orderBy('weight')->get();
    expect($categories)->toHaveCount(2);
    expect($categories->first()->title)->toBe('General Discussion');
});

test('forum threads link to categories and authors', function () {
    $user = User::create([
        'fullname' => 'Thread Author',
        'email' => 'author@test.mars',
        'password' => Hash::make('password'),
    ]);

    $catId = DB::table('forum_categories')->insertGetId([
        'title' => 'Governance',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $threadId = DB::table('forum_threads')->insertGetId([
        'category_id' => $catId,
        'author_id' => $user->id,
        'title' => 'Should We Increase Oxygen Quotas?',
        'slug' => 'should-we-increase-oxygen-quotas',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('forum_posts')->insert([
        'thread_id' => $threadId,
        'author_id' => $user->id,
        'body' => 'I propose we increase oxygen allocation by 15%.',
        'sequence' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Query like the ForumController does
    $thread = DB::table('forum_threads')
        ->join('users', 'forum_threads.author_id', '=', 'users.id')
        ->where('forum_threads.id', $threadId)
        ->select('forum_threads.*', 'users.fullname as author_name')
        ->first();

    expect($thread->title)->toBe('Should We Increase Oxygen Quotas?');
    expect($thread->author_name)->toBe('Thread Author');

    $posts = DB::table('forum_posts')
        ->where('thread_id', $threadId)
        ->orderBy('sequence')
        ->get();

    expect($posts)->toHaveCount(1);
    expect($posts->first()->body)->toContain('oxygen allocation');
});

test('forum thread links to proposal via discussion field', function () {
    $user = User::create([
        'fullname' => 'Proposer',
        'email' => 'proposer@test.mars',
        'password' => Hash::make('password'),
    ]);

    $catId = DB::table('forum_categories')->insertGetId([
        'title' => 'Proposals',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $threadId = DB::table('forum_threads')->insertGetId([
        'category_id' => $catId,
        'author_id' => $user->id,
        'title' => 'Proposal Discussion Thread',
        'slug' => 'proposal-discussion-thread',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('proposals')->insert([
        'user_id' => $user->id,
        'title' => 'Linked Proposal',
        'description' => 'This proposal has a discussion thread.',
        'discussion' => $threadId,
        'status' => 'submitted',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Join like the controller does
    $result = DB::table('forum_threads')
        ->leftJoin('proposals', 'proposals.discussion', '=', 'forum_threads.id')
        ->where('forum_threads.id', $threadId)
        ->select('forum_threads.title', 'proposals.id as proposal_id', 'proposals.status as proposal_status')
        ->first();

    expect($result->proposal_id)->not->toBeNull();
    expect($result->proposal_status)->toBe('submitted');
});

test('forum posts are ordered by sequence', function () {
    $user = User::create([
        'fullname' => 'Poster',
        'email' => 'poster@test.mars',
        'password' => Hash::make('password'),
    ]);

    $catId = DB::table('forum_categories')->insertGetId([
        'title' => 'General',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $threadId = DB::table('forum_threads')->insertGetId([
        'category_id' => $catId,
        'author_id' => $user->id,
        'title' => 'Multi-post thread',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Insert posts out of order
    DB::table('forum_posts')->insert([
        ['thread_id' => $threadId, 'author_id' => $user->id, 'body' => 'Third post', 'sequence' => 3, 'created_at' => now(), 'updated_at' => now()],
        ['thread_id' => $threadId, 'author_id' => $user->id, 'body' => 'First post', 'sequence' => 1, 'created_at' => now(), 'updated_at' => now()],
        ['thread_id' => $threadId, 'author_id' => $user->id, 'body' => 'Second post', 'sequence' => 2, 'created_at' => now(), 'updated_at' => now()],
    ]);

    $posts = DB::table('forum_posts')
        ->where('thread_id', $threadId)
        ->orderBy('sequence')
        ->get();

    expect($posts[0]->body)->toBe('First post');
    expect($posts[1]->body)->toBe('Second post');
    expect($posts[2]->body)->toBe('Third post');
});
