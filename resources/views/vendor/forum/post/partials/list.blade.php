<div @if (! $post->trashed())id="post-{{ $post->sequence }}"@endif
    class="post card mb-2 {{ $post->trashed() || $thread->trashed() ? 'deleted' : '' }}"
    :class="{ 'border-primary': selectedPosts.includes({{ $post->id }}) }" style="margin-top: 10px;">
    <div class="card-header">
        @if (! isset($single) || ! $single)
            <span class="float-end">
                <!-- <a href="{{ Forum::route('thread.show', $post) }}">#{{ $post->sequence }}</a> -->
                @if ($post->sequence != 1)
                    @can ('deletePosts', $post->thread)
                        @can ('delete', $post)
                            <input type="checkbox" name="posts[]" :value="{{ $post->id }}" v-model="selectedPosts">
                        @endcan
                    @endcan
                @endif
            </span>
        @endif
        {{ $post->author->fullname }} | 

        <span class="text-muted">
            @include ('forum::partials.timestamp', ['carbon' => $post->created_at])
            @if ($post->hasBeenUpdated())
                ({{ trans('forum::general.last_updated') }} @include ('forum::partials.timestamp', ['carbon' => $post->updated_at]))
            @endif
            |
        </span>
        <span class="text-muted">
            <a href="{{ Forum::route('post.show', $post) }}" class="card-link text-muted">{{ trans('forum::general.permalink') }}</a>
        </span>
    </div>
    <div class="card-body">
        @if ($post->parent !== null)
            @include ('forum::post.partials.quote', ['post' => $post->parent])
        @endif

        @if ($post->trashed())
            @can ('viewTrashedPosts')
                {!! Forum::render($post->content) !!}
                <br>
            @endcan
            <span class="badge rounded-pill bg-danger">{{ trans('forum::general.deleted') }}</span>
        @else
            {!! Forum::render($post->content) !!}
        @endif

        @if (! isset($single) || ! $single)
            <div class="text-end" style="margin-top: 5px;">
                @if (! $post->trashed())
                    @if ($post->sequence != 1)
                        @can ('deletePosts', $post->thread)
                            @can ('delete', $post)
                                <a href="{{ Forum::route('post.confirm-delete', $post) }}" class="card-link text-danger">{{ trans('forum::general.delete') }}</a>
                            @endcan
                        @endcan
                    @endif
                    <br>
                    @can ('edit', $post)
                        <a href="{{ Forum::route('post.edit', $post) }}" class="card-link">{{ trans('forum::general.edit') }}</a>
                    @endcan
                    @can ('reply', $post->thread)
                       <a style="float: left;" href="{{ Forum::route('post.create', $post) }}" class="card-link">{{ trans('forum::general.reply') }}</a>
                    @endcan
                @else
                    @can ('restorePosts', $post->thread)
                        @can ('restore', $post)
                            <a href="{{ Forum::route('post.confirm-restore', $post) }}" class="card-link">{{ trans('forum::general.restore') }}</a>
                        @endcan
                    @endcan
                @endif
            </div>
        @endif
    </div>
</div>
