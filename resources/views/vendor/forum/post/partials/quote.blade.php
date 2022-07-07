<div class="card mb-2" style="border-left-color: #eee; border-left-style: double; margin: 10px;">
    <div class="card-body">
        <div class="mb-2" style="font-style: italic;">
            <span class="float-end">
                <a href="{{ Forum::route('thread.show', $post) }}" class="text-muted">#{{ $post->sequence }}</a>
            </span>
            {{ $post->authorName }} <span class="text-muted">{{ $post->posted }}</span>
        </div>
        {!! \Illuminate\Support\Str::limit(Forum::render($post->content)) !!}
    </div>
</div>