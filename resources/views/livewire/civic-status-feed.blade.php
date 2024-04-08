<div>
    <h5 class="content-title"><u>Activity</u></h5>
    <div class="well">
        @if($activities->isEmpty())
            <p>No recent activities.</p>
        @else
            <ul class="icons-list text-md">
                @foreach($activities as $activity)
                    <li>
                        <i class="icon-li fa 
                        {{ $activity->tag == 'ED' ? 'fa-thumbs-up' : 
                        ($activity->tag == 'CT' ? 'fa-rocket' : 'fa-address-card') }}"></i>
                        {!! $activity->displayMessage !!}
                        <br>
                        <small>about {{ \Carbon\Carbon::parse($activity->mined)->diffForHumans() }}</small>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
