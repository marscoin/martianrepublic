<li>
  <div class="comment">
    {{-- Reply structure here --}}
    <div class="comment-avatar">
    <img src="{{ $reply->citizen->avatar_link }}"  onerror="this.onerror=null; this.src='https://martianrepublic.org/assets/citizen/generic_profile.jpg'" class="avatar">
    </div>
    <div class="comment-meta">
      <span class="comment-author">
        <a href="javascript:;">{{ $reply->authorName }}</a>
      </span>
      <a href="javascript:;" class="comment-timestamp">
        {{ $reply->created_at->format('F j, Y at h:i a') }}
      </a>
      <!-- The Reply link can be handled to open a reply form or similar -->
      <a class="comment-reply-link" href="javascript:;">Reply</a>
    </div>
    <div class="comment-body">
      <p>{{ $reply->content }}</p>
    </div>
    {{-- Check and include replies to this reply --}}
    @if ($reply->replies && $reply->replies->isNotEmpty())
      <ol class="comment-list">
        @foreach ($reply->replies as $childReply)
          @include('congress.reply', ['reply' => $childReply])
        @endforeach
      </ol>
    @endif
  </div>
</li>
