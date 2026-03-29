<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Profile;
use App\Models\Proposals;
use App\Models\Threads;
use App\Models\Posts;

class ForumController extends Controller
{

    /**
     * Forum home — all categories + recent threads.
     */
    public function index()
    {
        // Categories with thread/post counts
        $categories = DB::table('forum_categories')
            ->select('id', 'parent_id', 'title', 'description', 'color', 'thread_count', 'post_count', 'accepts_threads', 'is_private')
            ->orderBy('weight')
            ->orderBy('title')
            ->get();

        // Recent threads: pinned first, then by last activity
        $threads = DB::table('forum_threads')
            ->join('users', 'forum_threads.author_id', '=', 'users.id')
            ->leftJoin('forum_categories', 'forum_threads.category_id', '=', 'forum_categories.id')
            ->leftJoin('proposals', 'proposals.discussion', '=', 'forum_threads.id')
            ->select(
                'forum_threads.*',
                'users.fullname as author_name',
                'forum_categories.title as category_title',
                'forum_categories.color as category_color',
                'proposals.id as proposal_id',
                'proposals.status as proposal_status',
                'proposals.tier as proposal_tier'
            )
            ->whereNull('forum_threads.deleted_at')
            ->orderByDesc('forum_threads.pinned')
            ->orderByDesc('forum_threads.updated_at')
            ->limit(30)
            ->get();

        // Auth context for the view
        $viewData = $this->getAuthContext();
        $viewData['categories'] = $categories;
        $viewData['threads'] = $threads;

        return view('forum.index', $viewData);
    }


    /**
     * Thread view — thread with all posts (chronological), author info, proposal data.
     */
    public function show($id)
    {
        $id = (int) $id;
        $thread = DB::table('forum_threads')
            ->join('users', 'forum_threads.author_id', '=', 'users.id')
            ->leftJoin('forum_categories', 'forum_threads.category_id', '=', 'forum_categories.id')
            ->select(
                'forum_threads.*',
                'users.fullname as author_name',
                'forum_categories.title as category_title',
                'forum_categories.color as category_color',
                'forum_categories.id as category_id'
            )
            ->where('forum_threads.id', $id)
            ->whereNull('forum_threads.deleted_at')
            ->first();

        if (!$thread) {
            abort(404);
        }

        // All posts for this thread, chronological, with author info and citizen status
        $posts = DB::table('forum_posts')
            ->join('users', 'forum_posts.author_id', '=', 'users.id')
            ->leftJoin('profile', 'forum_posts.author_id', '=', 'profile.userid')
            ->leftJoin('citizen', 'forum_posts.author_id', '=', 'citizen.userid')
            ->select(
                'forum_posts.*',
                'users.fullname as author_name',
                'profile.citizen as is_citizen',
                'citizen.avatar_link as author_avatar'
            )
            ->where('forum_posts.thread_id', $id)
            ->whereNull('forum_posts.deleted_at')
            ->orderBy('forum_posts.sequence')
            ->orderBy('forum_posts.created_at')
            ->get();

        // Check if a proposal is linked to this thread
        $proposal = DB::table('proposals')
            ->where('discussion', $id)
            ->first();

        $viewData = $this->getAuthContext();
        $viewData['thread'] = $thread;
        $viewData['posts'] = $posts;
        $viewData['proposal'] = $proposal;

        return view('forum.show', $viewData);
    }


    /**
     * Create a new thread with its first post.
     */
    public function storeThread(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Profile / 2FA challenge check
        $authCheck = $this->requireProfile();
        if ($authCheck) return $authCheck;

        $request->validate([
            'title'       => 'required|string|min:3|max:255',
            'content'     => 'required|string|min:3',
            'category_id' => 'required|integer|exists:forum_categories,id',
        ]);

        $uid = Auth::user()->id;
        $now = now();

        DB::beginTransaction();
        try {
            // Create thread
            $threadId = DB::table('forum_threads')->insertGetId([
                'category_id' => $request->input('category_id'),
                'author_id'   => $uid,
                'title'       => $request->input('title'),
                'pinned'      => 0,
                'locked'      => 0,
                'reply_count' => 0,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);

            // Create first post
            $postId = DB::table('forum_posts')->insertGetId([
                'thread_id'  => $threadId,
                'author_id'  => $uid,
                'content'    => $request->input('content'),
                'sequence'   => 1,
                'authorName' => Auth::user()->fullname,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Update thread with first/last post references
            DB::table('forum_threads')
                ->where('id', $threadId)
                ->update([
                    'first_post_id' => $postId,
                    'last_post_id'  => $postId,
                ]);

            // Increment category counters
            DB::table('forum_categories')
                ->where('id', $request->input('category_id'))
                ->increment('thread_count');

            DB::table('forum_categories')
                ->where('id', $request->input('category_id'))
                ->increment('post_count');

            DB::commit();

            Log::info("Forum thread #{$threadId} created by user {$uid}");

            return redirect()->route('forum.thread.show', ['id' => $threadId]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to create forum thread: " . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to create thread. Please try again.'])->withInput();
        }
    }


    /**
     * Create a post/reply in a thread.
     */
    public function storePost(Request $request, $threadId)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Profile / 2FA challenge check
        $authCheck = $this->requireProfile();
        if ($authCheck) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Profile verification required'], 403);
            }
            return $authCheck;
        }

        $request->validate([
            'content'   => 'required|string|min:3',
            'parent_id' => 'nullable|integer|exists:forum_posts,id',
        ]);

        $uid = Auth::user()->id;
        $now = now();

        // Verify thread exists and is not locked
        $thread = DB::table('forum_threads')
            ->where('id', $threadId)
            ->whereNull('deleted_at')
            ->first();

        if (!$thread) {
            $msg = 'Thread not found.';
            return $request->expectsJson()
                ? response()->json(['error' => $msg], 404)
                : back()->withErrors(['error' => $msg]);
        }

        if ($thread->locked) {
            $msg = 'This thread is locked.';
            return $request->expectsJson()
                ? response()->json(['error' => $msg], 403)
                : back()->withErrors(['error' => $msg]);
        }

        DB::beginTransaction();
        try {
            // Calculate next sequence number
            $maxSeq = DB::table('forum_posts')
                ->where('thread_id', $threadId)
                ->max('sequence') ?? 0;

            $postId = DB::table('forum_posts')->insertGetId([
                'thread_id'  => $threadId,
                'author_id'  => $uid,
                'content'    => $request->input('content'),
                'post_id'    => $request->input('parent_id'),
                'sequence'   => $maxSeq + 1,
                'authorName' => Auth::user()->fullname,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Update thread: reply count, last post, bump updated_at
            DB::table('forum_threads')
                ->where('id', $threadId)
                ->update([
                    'reply_count'  => DB::raw('COALESCE(reply_count, 0) + 1'),
                    'last_post_id' => $postId,
                    'updated_at'   => $now,
                ]);

            // Increment category post count
            DB::table('forum_categories')
                ->where('id', $thread->category_id)
                ->increment('post_count');

            DB::commit();

            Log::info("Forum post #{$postId} in thread #{$threadId} by user {$uid}");

            if ($request->expectsJson()) {
                // Return the new post data for AJAX rendering
                $post = DB::table('forum_posts')
                    ->join('users', 'forum_posts.author_id', '=', 'users.id')
                    ->leftJoin('profile', 'forum_posts.author_id', '=', 'profile.userid')
                    ->leftJoin('citizen', 'forum_posts.author_id', '=', 'citizen.userid')
                    ->select(
                        'forum_posts.*',
                        'users.fullname as author_name',
                        'profile.citizen as is_citizen',
                        'citizen.avatar_link as author_avatar'
                    )
                    ->where('forum_posts.id', $postId)
                    ->first();

                return response()->json([
                    'success' => true,
                    'post'    => $post,
                ]);
            }

            return redirect()->route('forum.thread.show', ['id' => $threadId]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to create forum post: " . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json(['error' => 'Failed to create post.'], 500);
            }
            return back()->withErrors(['error' => 'Failed to create post. Please try again.'])->withInput();
        }
    }


    /**
     * Threads filtered by category — pinned first, then by last activity.
     */
    public function categoryThreads($categoryId)
    {
        $categoryId = (int) $categoryId;
        $category = DB::table('forum_categories')
            ->where('id', $categoryId)
            ->first();

        if (!$category) {
            abort(404);
        }

        $categories = DB::table('forum_categories')
            ->select('id', 'parent_id', 'title', 'description', 'color', 'thread_count', 'post_count', 'accepts_threads', 'is_private')
            ->orderBy('weight')
            ->orderBy('title')
            ->get();

        $threads = DB::table('forum_threads')
            ->join('users', 'forum_threads.author_id', '=', 'users.id')
            ->leftJoin('proposals', 'proposals.discussion', '=', 'forum_threads.id')
            ->select(
                'forum_threads.*',
                'users.fullname as author_name',
                'proposals.id as proposal_id',
                'proposals.status as proposal_status',
                'proposals.tier as proposal_tier'
            )
            ->where('forum_threads.category_id', $categoryId)
            ->whereNull('forum_threads.deleted_at')
            ->orderByDesc('forum_threads.pinned')
            ->orderByDesc('forum_threads.updated_at')
            ->limit(30)
            ->get();

        $viewData = $this->getAuthContext();
        $viewData['category'] = $category;
        $viewData['categories'] = $categories;
        $viewData['threads'] = $threads;

        return view('forum.index', $viewData);
    }


    // ==================================================================================
    // Private helpers
    // ==================================================================================

    /**
     * Build common auth context data for views (mirrors CongressController pattern).
     */
    private function getAuthContext(): array
    {
        if (Auth::check()) {
            $uid = Auth::user()->id;
            $profile = Profile::where('userid', $uid)->first();

            return [
                'isCitizen'   => $profile->citizen ?? false,
                'isGP'        => $profile->general_public ?? false,
                'wallet_open' => $profile->civic_wallet_open ?? false,
            ];
        }

        return [
            'isCitizen'   => false,
            'isGP'        => false,
            'wallet_open' => false,
        ];
    }

    /**
     * Profile / 2FA challenge guard (same pattern as CongressController).
     * Returns a redirect if the user needs to complete profile setup or 2FA,
     * or null if everything is fine.
     */
    private function requireProfile()
    {
        $uid = Auth::user()->id;
        $profile = Profile::where('userid', $uid)->first();

        if (!$profile) {
            return redirect('/twofa');
        }

        if ($profile->openchallenge == 1 || is_null($profile->openchallenge)) {
            return redirect('/twofachallenge');
        }

        return null;
    }
}
