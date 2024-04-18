<h3 class="content-title"><u>Closed Proposals</u></h3>
<div class="row">
  <div class="col-md-9">

@if ($proposals->isEmpty())
    <div class="alert alert-info">
        Currently no active proposals underway. Consider launching a new proposal!
    </div>
@else
    @foreach ($closed as $proposal)
        <div class="post" style="border-bottom: 1px dotted #ccc;margin-bottom: 50px;padding-bottom: 50px;">
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
                <h3 class="post-title"><a href="/congress/proposal/{{$proposal->id}}">{{ $proposal->title }}</a></h3>
                <h4 class="post-meta">Submitted by <a target="_blank" href="/citizen/id/{{ $proposal->public_address }}">{{ $proposal->author }}</a> in <a href="javascript:;">{{str_replace("poll", "Certified Poll", $proposal->category)}}</a> [<a  target="_blank" href="{{$proposal->ipfs_hash}}">{{ strtoupper(substr(str_replace("https://ipfs.marscoin.org/ipfs/", "", $proposal->ipfs_hash), 1, 8)) }}</a>]</h4>
                <div class="post-content">      
                <p>{{substr($proposal->description, 0, 400)}}<a href="/congress/proposal/{{$proposal->id}}">Read More...</a></p>
                    <div class="row">
                        <div class="col-sm-4" style="padding-top: 14px;">
                        <a href="#" class="btn btn-tertiary"><i class="fa-solid fa-triangle-exclamation"></i> Closed ({{$proposal->closed_reason}})</a>
                        </div>
                        <div class="col-sm-12">
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    @endforeach
    @endif
    
    <hr>

    <nav>
        <ul class="pagination pull-right">
        <li>
            <a href="#" aria-label="Previous">
            <span aria-hidden="true">«</span>
            </a>
        </li>
        <li class="active"><a href="#">1</a></li>
        <li><a href="#">2</a></li>
        <li><a href="#">3</a></li>
        <li><a href="#">4</a></li>
        <li><a href="#">5</a></li>
        <li>
            <a href="#" aria-label="Next">
            <span aria-hidden="true">»</span>
            </a>
        </li>
        </ul>
    </nav>


</div>



    <div class="col-md-3">
        <p>The <b>Martian Congressional Republic</b> consists of known <a href="/citizen/all">citizens</a> who discuss public matters ("res publica") in an open and transparent way. They vote on changes - including the very code that runs this application ("<b>The Constitution</b>") - in an equally transparent yet fully anonymous way. Every vote is cryptographically secured and can be audited by everyone. </p> 

        <p>Fair, transparent, immutable and auditable votes are the outcome. Our congressional archive lists all passed and failed proposals, bills, amendments and references all discussions. The Martian Congressional Republic embodies democracy as a living organism shared directly by all members of the Republic - allowing better ideas to win out and move Martian civilization forward. </p>

    </div>
</div>