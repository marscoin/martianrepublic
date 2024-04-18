<h3 class="content-title"><u>Passed Proposals</u></h3>
<div class="row">
    <div class="col-md-9">

@if ($passed->isEmpty())
    <div class="alert alert-info">
        No proposals have passed yet. Consider launching a new proposal!
    </div>
@else
    @foreach ($passed as $proposal)
        <div class="post">
            <div class="post-aside">
                @php
                    $createdAt = \Carbon\Carbon::parse($proposal->mined);
                @endphp
                <div class="post-date">
                    <span class="post-date-day">{{ $proposal->id }}</span>
                    <span class="post-date-month">#</span>
                    <span class="post-date-year">Bill</span>
                </div>
                <a href="/forum/t/{{ $proposal->discussion }}" class="post-comment">
                {{$proposal->post_count}}
                </a>
            </div> 
            <div class="post-main">
                <h3 class="post-title"><a href="#">{{ $proposal->title }}</a></h3>
                <h4 class="post-meta">Submitted by <a target="_blank" href="/citizen/id/{{ $proposal->public_address }}">{{ $proposal->author }}</a> in <a href="javascript:;">{{str_replace("poll", "Certified Poll", $proposal->category)}}</a></h4>
                <h5>Summary: Voting lasted {{$proposal->duration}} days. It started on {{ $createdAt->format('F j, Y') }} and ended  {{ $createdAt->format('F j, Y') }}. A total of {{$proposal->total_votes}} votes was cast exceeding a necessary participation threshold of {{$proposal->threshold}}% of the current number of citizens. The motion carried with {{round($proposal->yay_percent,2)}}% of the vote in favor. Notarized: {{ strtoupper(substr(str_replace("https://ipfs.marscoin.org/ipfs/", "", $proposal->ipfs_hash), 1, 8)) }} <a  target="_blank" href="{{$proposal->ipfs_hash}}"><i class="fa-solid fa-link"></i></a> </h5>
                <div class="post-content">      
                <p>{{substr($proposal->description, 0, 400)}}<a href="/congress/proposal/{{$proposal->id}}">Read More...</a></p>
                    <div class="row">
                        <div class="col-sm-4" style="padding-top: 14px;">
                            <a href="#" class="btn btn-success"><i class="fa-regular fa-square-check"></i> Passed</a>
                        </div>
                        <div class="col-sm-8" style="padding-top: 14px;"> 
                            <div class="pull-right">
                                <a href="javascript:;" class="btn btn-success"><i class="fa fa-thumbs-up"></i> {{$proposal->yays}}</a>
                                <a href="javascript:;" class="btn btn-danger"><i class="fa fa-thumbs-down"></i> {{$proposal->nays}}</a>
                            </div>
                        
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    @endforeach
    @endif
    </div>

    <div class="col-md-3">
    <p>The <b>Martian Congressional Republic</b> consists of known <a href="/citizen/all">citizens</a> who discuss public matters ("res publica") in an open and transparent way. They vote on changes - including the very code that runs this application ("<b>The Constitution</b>") - in an equally transparent yet fully anonymous way. Every vote is cryptographically secured and can be audited by everyone. </p> 

    <p>Fair, transparent, immutable and auditable votes are the outcome. Our congressional archive lists all passed and failed proposals, bills, amendments and references all discussions. The Martian Congressional Republic embodies democracy as a living organism shared directly by all members of the Republic - allowing better ideas to win out and move Martian civilization forward. </p>

    </div>
</div>
