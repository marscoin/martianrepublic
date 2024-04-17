<li>
  <div class="comment">
    {{-- Reply structure here --}}
    <div class="comment-avatar">
      <img src="{{ asset('path/to/avatars/'.$reply->author_id.'-md.jpg') }}" class="avatar">
    </div>
    <div class="comment-meta">
      {{-- ... meta information for reply --}}
    </div>
    <div class="comment-body">
      <p>{{ $reply->content }}</p>
    </div>
  </div>
</li>