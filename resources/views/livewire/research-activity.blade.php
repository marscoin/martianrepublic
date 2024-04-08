<div class="tab-pane fade active in" id="research">
    <h4 class="content-title"><u>Public Research Activity</u></h4>

    @forelse($activities as $activity)
                @if($activity->tag === 'LB')
                    <div class="feed-item feed-item-file"><div class="feed-icon"> <i class="fa fa-cloud-upload"></i> </div><div class="feed-subject"><p><a href="javascript:;">Nikita Williams</a> posted a <strong>logbook entry</strong></p></div>
                @endif
            <div class="feed-content">
                @if($activity->tag === 'LB')
                    <ul class="icons-list">
                        <li>
                            <i class="icon-li fa fa-file-text-o"></i>
                            <a href="https://explore.marscoin.org/tx/{{ $activity->embedded_link }}">IPFS Link</a>
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
        <p>No recent entries found.</p>
    @endforelse
</div>