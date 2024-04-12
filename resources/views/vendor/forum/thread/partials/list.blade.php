<div style="padding-left: 50px; margin-bottom: 20px;" class="list-group-item {{ $thread->pinned ? 'pinned' : '' }} {{ $thread->locked ? 'locked' : '' }} {{ $thread->trashed() ? 'deleted' : '' }}" :class="{ 'border-primary': selectedThreads.includes({{ $thread->id }}) }" >
    <div class="row align-items-center text-left">
        <div class="col-sm text-md-start">
            <span class="lead">
                <a href="{{ Forum::route('thread.show', $thread) }}" @if (isset($category))style="color: #333333; font-weight: 600; font-family: 'Oswald';"@endif>{{ $thread->title }}</a>
            </span>
            <br>
            {{ $thread->authorName }} <span class="text-muted">@include ('forum::partials.timestamp', ['carbon' => $thread->created_at])</span>

            @if (! isset($category))
                <br>
                <a href="{{ Forum::route('category.show', $thread->category) }}" style="color: #333333; font-family: 'Oswald';">{{ $thread->category->title }}</a>
            @endif
        </div>
        <div class="col-sm text-md-end" style="float:right;     padding-right: 50px;">
            @if ($thread->pinned)
                <div class="badge rounded-pill bg-info">{{ trans('forum::threads.pinned') }}</div>
            @endif
            @if ($thread->locked)
                <div class="badge rounded-pill bg-warning">{{ trans('forum::threads.locked') }}</div>
            @endif
            @if ($thread->userReadStatus !== null && ! $thread->trashed())
                <div class="badge rounded-pill bg-success">{{ trans($thread->userReadStatus) }}</div>
            @endif
            @if ($thread->trashed())
                <div class="badge rounded-pill bg-danger">{{ trans('forum::general.deleted') }}</div>
            @endif
            <div class="badge rounded-pill bg-primary" @if (isset($category))style="background: #6685a4;"@endif>
                {{ trans('forum::general.replies') }}: 
                {{ $thread->reply_count }}
                </div>
        </div>

        @if ($thread->lastPost)
            <div class="col-sm text-md-end text-muted" style="float: right; margin-top: -50px;  margin-right: -80px;">
                <a href="{{ Forum::route('thread.show', $thread->lastPost) }}">{{ trans('forum::posts.view') }} &raquo;</a>
                <br>
                {{ $thread->lastPost->authorName }}
                <span class="text-muted">@include ('forum::partials.timestamp', ['carbon' => $thread->lastPost->created_at])</span>
            </div>
        @endif

        <!-- @if (isset($category) && isset($selectableThreadIds) && in_array($thread->id, $selectableThreadIds))
            <div class="col-sm" style="flex: 0;" style="float:right">
                <input type="checkbox" name="threads[]" :value="{{ $thread->id }}" v-model="selectedThreads">
            </div>
        @endif -->
    </div>
</div>