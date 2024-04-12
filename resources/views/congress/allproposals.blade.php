@foreach ($proposals as $proposal)
    <div class="feed-item feed-item-idea">
        <div class="feed-icon">
            <i class="fa fa-lightbulb-o"></i>
        </div>
        <div class="feed-subject">
            <h5 style="font-size: 30px;"><a href="{{{$proposal->ipfs_hash}}}">Proposal #{{ strtoupper(substr(str_replace("https://ipfs.marscoin.org/ipfs/", "", $proposal->ipfs_hash), 1, 8)) }}</a></h5>
            <h5>Category: {{str_replace("poll", "Certified Poll", $proposal->category)}}</h5>
            <hr style="border-top: 1px dotted #ccc;">
            <h3><a target="_blank" href="/citizen/id/{{ $proposal->public_address }}">{{ $proposal->author }}</a> @if($proposal->category =='poll') asked: @else proposed: @endif <br><a target="_blank" href="/forum/t/{{ $proposal->discussion }}">{{ $proposal->title }} </a></h3>
        </div>
        <div class="feed-content">
            <ul class="icons-list">
                <li>
                    <i class="icon-li fa fa-quote-left"></i>
                    <p style="font-size: 2rem">
                        {{ $proposal->description }}
                    </p>
                </li>
            </ul>
            <div>Final Vote Participation: Pending</div>
            <div>Final Vote Threshold: Pending</div>
            <div>Vote Duration: {{$proposal->duration}} days</div>
            <div>
                Result: Pending
            </div>
        </div>
        <div class="feed-actions">
            <a href='/forum/t/{{ $proposal->discussion }}' class="pull-left discussion-link">
                Discussion for this proposal: <i class="fa fa-external-link"></i>
            </a>
            <a href="https://explore.marscoin.org/tx/{{ $proposal->txid }}" class="pull-right">
                <i class="fa fa-clock-o"></i>
                {{ $proposal->created_at }} <i class="fa fa-check-square"></i>Notarized: {{ substr($proposal->txid, 0, 16) }}...
            </a>
        </div>
    </div>
@endforeach
@foreach ($oldproposals as $proposal)
    <div class="feed-item feed-item-idea">
        <div class="feed-icon">
            <i class="fa fa-lightbulb-o"></i>
        </div>
        <div class="feed-subject">
            <h5 style="font-size: 30px;"><a href="{{{$proposal->ipfs_hash}}}">Archived Proposal #{{ strtoupper(substr(str_replace("https://ipfs.marscoin.org/ipfs/", "", $proposal->ipfs_hash), 1, 8)) }}</a></h5>
            <h5>Category: {{str_replace("poll", "Certified Poll", $proposal->category)}}</h5>
            <hr style="border-top: 1px dotted #ccc;">
            <h3><a target="_blank" href="/citizen/id/{{ $proposal->public_address }}">{{ $proposal->author }}</a> @if($proposal->category =='poll') asked: @else proposed: @endif <br><a target="_blank" href="/forum/t/{{ $proposal->discussion }}">{{ $proposal->title }} </a></h3>
        </div>
        <div class="feed-content">
            <ul class="icons-list">
                <li>
                    <i class="icon-li fa fa-quote-left"></i>
                    <p style="font-size: 2rem">
                        {{ $proposal->description }}
                    </p>
                </li>
            </ul>
            <div>Final Vote Participation: Pending</div>
            <div>Final Vote Threshold: Pending</div>
            <div>Vote Duration: {{$proposal->duration}} days</div>
            <div>
                Result: Pending
            </div>
        </div>
        <div class="feed-actions">
            <a href='/forum/t/{{ $proposal->discussion }}' class="pull-left discussion-link">
                Discussion for this proposal: <i class="fa fa-external-link"></i>
            </a>
            <a href="https://explore.marscoin.org/tx/{{ $proposal->txid }}" class="pull-right">
                <i class="fa fa-clock-o"></i>
                {{ $proposal->created_at }} <i class="fa fa-check-square"></i>Notarized: {{ substr($proposal->txid, 0, 16) }}...
            </a>
        </div>
    </div>
@endforeach