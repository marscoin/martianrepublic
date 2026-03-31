<?php

namespace App\Http\Controllers;

use App\Models\Posts;
use App\Models\Threads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ForumApiController extends Controller
{
    public function getThreadsByCategory($categoryId)
    {
        $threads = $this->fetchThreads($categoryId);

        return response()->json(['threads' => $threads]);
    }

    public function getThreadComments($threadId)
    {
        // Assume $threadId is passed correctly to the function
        $comments = $this->fetchCommentsByThread($threadId);

        return response()->json(['comments' => $comments]);
    }

    public function getAllCategoriesWithThreads()
    {
        $userId = Auth::id();

        $categories = DB::table('forum_categories')->get();

        // Fetch all threads in a single query instead of N+1
        $threads = DB::table('forum_threads')
            ->leftJoin('users', 'forum_threads.author_id', '=', 'users.id')
            ->leftJoin('profile', 'users.id', '=', 'profile.userid')
            ->leftJoin('user_blocks as ub', function ($join) use ($userId) {
                $join->on('forum_threads.author_id', '=', 'ub.blocked_user_id')
                    ->where('ub.user_id', '=', $userId);
            })
            ->select(
                'forum_threads.id',
                'forum_threads.category_id',
                'forum_threads.title',
                'forum_threads.created_at',
                'forum_threads.reply_count',
                'users.fullname as author_name',
                DB::raw('IF(ub.blocked_user_id IS NOT NULL, true, false) as is_blocked')
            )
            ->orderBy('forum_threads.created_at', 'desc')
            ->get()
            ->groupBy('category_id');

        foreach ($categories as $category) {
            $category->threads = $threads->get($category->id, collect());
        }

        return response()->json(['categories' => $categories]);
    }

    public function createThread(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:forum_categories,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $thread = new Threads;
        $thread->category_id = $request->category_id;
        $thread->author_id = Auth::id();
        $thread->title = $request->title;
        $thread->save();

        $post = new Posts;
        $post->thread_id = $thread->id;
        $post->author_id = Auth::id();
        $post->content = $request->content;
        $post->save();

        $thread->first_post_id = $post->id;
        $thread->last_post_id = $post->id;
        $thread->reply_count = 0;
        $thread->save();

        return response()->json([
            'message' => 'Thread created successfully',
            'thread_id' => $thread->id,
            'post_id' => $post->id,
        ], 201);
    }

    public function createComment(Request $request, $threadId)
    {
        $request->validate([
            'content' => 'required|string',
            'post_id' => 'nullable|exists:forum_posts,id',
        ]);

        $thread = Threads::findOrFail($threadId);

        $post = new Posts;
        $post->thread_id = $threadId;
        $post->author_id = Auth::id();
        $post->content = $request->content;
        $post->post_id = $request->post_id; // This will be null for top-level comments
        $post->save();

        $thread->last_post_id = $post->id;
        $thread->reply_count += 1;
        $thread->save();

        return response()->json([
            'message' => 'Comment created successfully',
            'post_id' => $post->id,
        ], 201);
    }

    private function fetchThreads($categoryId)
    {
        $userId = Auth::id();

        $threads = DB::table('forum_threads')
            ->where('forum_threads.category_id', $categoryId)
            ->leftJoin('users', 'forum_threads.author_id', '=', 'users.id')
            ->leftJoin('profile', 'users.id', '=', 'profile.userid')
            ->leftJoin('user_blocks as ub', function ($join) use ($userId) {
                $join->on('forum_threads.author_id', '=', 'ub.blocked_user_id')
                    ->where('ub.user_id', '=', $userId);
            })
            ->select(
                'forum_threads.id',
                'forum_threads.title',
                'forum_threads.created_at',
                'forum_threads.reply_count',
                'users.fullname as author_name',
                DB::raw('IF(ub.blocked_user_id IS NOT NULL, true, false) as is_blocked')
            )
            ->orderBy('forum_threads.created_at', 'desc')
            ->get();

        return $threads;
    }

    private function fetchCommentsByThread($threadId)
    {
        $userId = Auth::id();

        $query = '
            WITH RECURSIVE CommentTree AS (
                SELECT
                    p.id,
                    p.thread_id,
                    p.author_id,
                    p.content,
                    p.post_id as pid,
                    p.created_at,
                    CHAR_LENGTH(p.content) as char_length_sum
                FROM
                    forum_posts p
                WHERE
                    p.thread_id = ? AND p.post_id IS NULL

                UNION ALL

                SELECT
                    p.id,
                    p.thread_id,
                    p.author_id,
                    p.content,
                    p.post_id,
                    p.created_at,
                    ct.char_length_sum + CHAR_LENGTH(p.content)
                FROM
                    forum_posts p
                INNER JOIN
                    CommentTree ct ON p.post_id = ct.id
            )
            SELECT
                ct.id,
                ct.thread_id,
                ct.author_id,
                u.fullname,
                ct.content,
                ct.created_at,
                ct.pid,
                CHAR_LENGTH(ct.content) as char_length_sum,
                IF(ub.blocked_user_id IS NOT NULL, true, false) as is_blocked
            FROM
                CommentTree ct
            LEFT JOIN users u ON ct.author_id = u.id
            LEFT JOIN profile pr ON ct.author_id = pr.userid
            LEFT JOIN user_blocks ub ON ub.blocked_user_id = ct.author_id AND ub.user_id = ?
            ORDER BY
                ct.pid ASC,
                ct.created_at ASC;
        ';

        $comments = DB::select($query, [$threadId, $userId]);
        $commentsCollection = collect($comments);

        return response()->json(['comments' => $commentsCollection]);
    }
}
