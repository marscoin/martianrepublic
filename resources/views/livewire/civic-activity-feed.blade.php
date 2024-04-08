<div class="tab-pane fade active in" id="notary">
    <h4 class="content-title"><u>Blockchain Notarized Public Activity Feed</u></h4>

    @forelse($activities as $activity)
                @if($activity->tag === 'ED')
                    <div class="feed-item feed-item-file"><div class="feed-icon"><i class="fa fa-link"></i></div><div class="feed-subject"><p>Endorsement of Citizen</p></div>
                @elseif($activity->tag === 'SP')
                    <div class="feed-item feed-item-file"><div class="feed-icon"><i class="fa fa-link"></i></div><div class="feed-subject"><p>Signed Public Message</p></div>
                @elseif($activity->tag === 'GP')
                    <div class="feed-item feed-item-file"><div class="feed-icon"><i class="fa fa-drivers-license"></i></div><div class="feed-subject"><p><a href="javascript:;">{{ $citcache->firstname }} {{ $citcache->lastname }} </a> successfully <strong>notarized</strong> his <a href="https://explore.marscoin.org/tx/{{ $activity->txid }}">General Martian Public</a> application</p></div>
                @elseif($activity->tag === 'CT')
                    <div class="feed-item feed-item-question"><div class="feed-icon"><i class="fa fa-legal"></i></div><div class="feed-subject"><p><a href="javascript:;">{{ $citcache->firstname }} {{ $citcache->lastname }} </a> pledged allegiance to <a href="javascript:;">The Martian Congressional Republic</a></p></div>
                @elseif($activity->tag === 'LB')
                    <div class="feed-item feed-item-file"><div class="feed-icon"><i class="fa fa-link"></i></div><div class="feed-subject"><p>Logbook Publication</p></div>
                @endif
            <div class="feed-content">
                @if($activity->tag === 'ED')
                    <p>{{ $citcache->firstname }} {{ $citcache->lastname }} successfully <strong>notarized</strong> an endorsement for {{ $activity->message }}</p>
                @elseif($activity->tag === 'SP')
                    <p><blockquote>{{ str_replace('\"', "'", str_replace('\n', "\n", $activity->message)) }}</blockquote></p>
                @elseif($activity->tag === 'GP')
                    <ul class="icons-list">
                        <li>
                            <i class="icon-li fa fa-file-text-o"></i>
                            <a href="{{ $activity->embedded_link }}">Data Set</a> - (Basic Biographic Data)
                        </li>

                        <li>
                            <i class="icon-li fa fa-file-text-o"></i>
                            <a href="{{$citcache->avatar_link}}">Profile Picture</a> - (Basic Biometric Identifier)
                        </li>

                        <li>
                            <i class="icon-li fa fa-file-text-o"></i>
                            <a href="{{$citcache->liveness_link}}">Liveness Video</a> - (Basic Proof of Humanity)
                        </li>
                    </ul>

                @elseif($activity->tag === 'CT')
                    <ul class="icons-list">
                    <li>
                        <i class="icon-li fa fa-quote-left"></i>
                        I herewith declare that I, {{ $citcache->firstname }} {{ $citcache->lastName }} , am human and a member of the Martian Republic.
                    </li>
                    </ul>
                @endif
            </div>
            <div class="feed-actions">
                <a target="_blank" href="https://explore.marscoin.org/tx/{{ $activity->txid }}" class="pull-left"><i class="fa fa-lock"></i> {{ $activity->blockid }}</a> 
                <a target="_blank" href="https://explore.marscoin.org/tx/{{ $activity->txid }}" class="pull-right"><i class="fa fa-clock-o"></i> {{ $activity->mined->diffForHumans() }}</a>
            </div>
        </div>
    @empty
        <p>No recent activities found.</p>
    @endforelse
</div>
