<div>
    <h5 class="content-title"><u>Res Publica</u></h5>
    <div class="well">
        @if($activities->isEmpty())
            <p>No recent activity.</p>
        @else
            <ul class="icons-list text-md">
                @foreach($activities as $activity)
                    <li>
                        <i class="icon-li fa fa-location-arrow"></i>
                        <strong>{{ $activity->firstname }} {{ $activity->lastname }}</strong> {!! $activity->description !!}
                        <br>
                        <small>{{ $activity->mined->diffForHumans() }}</small>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
