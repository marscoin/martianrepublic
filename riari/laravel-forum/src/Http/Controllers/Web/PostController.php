<?php

namespace TeamTeaTime\Forum\Http\Controllers\Web;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View as ViewFactory;
use Illuminate\View\View;
use TeamTeaTime\Forum\Events\UserCreatingPost;
use TeamTeaTime\Forum\Events\UserEditingPost;
use TeamTeaTime\Forum\Events\UserViewingPost;
use TeamTeaTime\Forum\Http\Requests\CreatePost;
use TeamTeaTime\Forum\Http\Requests\DeletePost;
use TeamTeaTime\Forum\Http\Requests\RestorePost;
use TeamTeaTime\Forum\Http\Requests\UpdatePost;
use TeamTeaTime\Forum\Models\Post;
use TeamTeaTime\Forum\Models\Thread;
use TeamTeaTime\Forum\Support\Web\Forum;

use Illuminate\Support\Facades\Auth;
use App\Models\Profile;
use App\Includes\AppHelper;
use App\Models\HDWallet;

class PostController extends BaseController
{

    public function check($view)
    {
        if (Auth::check()) {
			$uid = Auth::user()->id;
			$profile = Profile::where('userid', '=', $uid)->first();
			$wallet = HDWallet::where('user_id', '=', $uid)->first();

			if (!$profile) {
				return redirect('/twofa');
			} else {
				if ($profile->openchallenge == 1 || is_null($profile->openchallenge)) {
					return redirect('/twofachallenge');
				}
			}

			$view->balance = 0; //for now, could move to stats helper function as well


            if ($wallet) {
				$view->public_address = $wallet->public_addr;
			} else {
				$view->balance = 0;
			}

			$view->isCitizen = $profile->citizen;
			$view->isGP  = $profile->general_public;
			$view->wallet_open = $profile->wallet_open;

			return $view;


		}else{
            return view('auth.login');
        }
    }


    public function show(Request $request, Thread $thread, string $postSlug, Post $post): View
    {
        if (! $thread->category->isAccessibleTo($request->user())) {
            abort(404);
        }

        if ($thread->category->is_private) {
            $this->authorize('view', $thread);
        }

        if ($request->user() !== null) {
            UserViewingPost::dispatch($request->user(), $post);
        }

        return $this->check(ViewFactory::make('forum::post.show', compact('thread', 'post')));
    }

    public function create(Request $request, Thread $thread): View
    {
        $this->authorize('reply', $thread);

        UserCreatingPost::dispatch($request->user(), $thread);

        $post = $request->has('post') ? $thread->posts->find($request->input('post')) : null;

        return $this->check(ViewFactory::make('forum::post.create', compact('thread', 'post')));
    }

    public function store(CreatePost $request, Thread $thread): RedirectResponse
    {
        $this->authorize('reply', $thread);

        $post = $request->fulfill();

        Forum::alert('success', 'general.reply_added');

        return $this->check(ViewFactory::make('forum::post.edit', compact('category', 'thread', 'post')));
    }

    public function edit(Request $request, Thread $thread, $threadSlug, Post $post): View
    {
        if ($post->trashed()) {
            return abort(404);
        }

        $this->authorize('edit', $post);

        UserEditingPost::dispatch($request->user(), $post);

        $thread = $post->thread;
        $category = $post->thread->category;

        return $this->check(ViewFactory::make('forum::post.confirm-restore', ['category' => $thread->category, 'thread' => $thread, 'post' => $post]));
    }

    public function update(UpdatePost $request, Thread $thread, $threadSlug, Post $post): RedirectResponse
    {
        $this->authorize('edit', $post);

        $post = $request->fulfill();

        Forum::alert('success', 'posts.updated');

        return new RedirectResponse(Forum::route('thread.show', $post));
    }

    public function confirmDelete(Request $request, Thread $thread, $threadSlug, Post $post): View
    {
        return ViewFactory::make('forum::post.confirm-delete', ['category' => $thread->category, 'thread' => $thread, 'post' => $post]);
    }

    public function confirmRestore(Request $request, Thread $thread, $threadSlug, Post $post): View
    {
        return ViewFactory::make('forum::post.confirm-restore', ['category' => $thread->category, 'thread' => $thread, 'post' => $post]);
    }

    public function delete(DeletePost $request): RedirectResponse
    {
        $post = $request->fulfill();

        if ($post === null) {
            return $this->invalidSelectionResponse();
        }

        Forum::alert('success', 'posts.deleted', 1);

        return new RedirectResponse(Forum::route('thread.show', $post->thread));
    }

    public function restore(RestorePost $request): RedirectResponse
    {
        $post = $request->fulfill();

        if ($post === null) {
            return $this->invalidSelectionResponse();
        }

        Forum::alert('success', 'posts.updated', 1);

        return new RedirectResponse(Forum::route('thread.show', $post));
    }
}
