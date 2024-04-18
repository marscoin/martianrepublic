<div class="col-sm-8 col-md-8 col-lg-9">
    <div class="posts">
        @foreach ($proposals as $proposal)
        <div class="post">
            <div class="post-aside">
                @php
                    $createdAt = \Carbon\Carbon::parse($proposal->mined);
                @endphp
                <div class="post-date">
                    <span class="post-date-day">{{ $createdAt->format('d') }}</span>
                    <span class="post-date-month">{{ $createdAt->format('M') }}</span>
                    <span class="post-date-year">{{ $createdAt->format('Y') }}</span>
                </div>
                <a href="/forum/t/{{ $proposal->discussion }}" class="post-comment">
                13
                </a>
            </div> 
            <div class="post-main">
                <h3 class="post-title"><a href="/congress/proposal/{{$proposal->id}}">#{{ strtoupper(substr(str_replace("https://ipfs.marscoin.org/ipfs/", "", $proposal->ipfs_hash), 1, 8)) }} Proposal: {{ $proposal->title }}</a></h3>
                <h4 class="post-meta">Submitted by <a target="_blank" href="/citizen/id/{{ $proposal->public_address }}">{{ $proposal->author }}</a> in <a href="javascript:;">{{str_replace("poll", "Certified Poll", $proposal->category)}}</a></h4>
                <div class="post-content">      
                <p>Integer eu velit vel sapien elementum suscipit in euismod justo. Etiam nec odio porta dui facilisis congue vel et neque. Duis a aliquam ante, a viverra mauris. Sed ipsum nibh, consectetur eu metus vitae, convallis ornare tellus. Maecenas nec est pharetra, ornare leo eget, pellentesque urna. Maecenas sit amet maximus orci. Praesent ullamcorper arcu sed purus tristique fringilla. Nam libero libero, porta id placerat nec, malesuada nec lacus...<a href="/congress/proposal/{{$proposal->id}}">Read More...</a></p>
                    <div class="row">
                        <div class="col-sm-4" style="padding-top: 14px;">
                        <a href="#" class="btn btn-success">Voting in Progress</a>
                        </div>
                        <div class="col-sm-4">
                        @php
                            $endTime = \Carbon\Carbon::parse($proposal->mined)->addDays($proposal->duration)->format('Y-m-d H:i:s');
                        @endphp
                        <x-countdown-timer :proposal-id="$proposal->id" :end-time="$endTime" :start-time="$proposal->mined" />
                        </div>
                        <div class="col-sm-4">
                        @livewire('voting-progress', ['proposalId' => $proposal->id])
                        </div>
                    </div>
                </div> 
            </div>
        </div>
        @endforeach 
        @foreach ($proposals as $proposal)
        <div class="post">
            <div class="post-aside">
                @php
                    $createdAt = \Carbon\Carbon::parse($proposal->mined);
                @endphp
                <div class="post-date">
                    <span class="post-date-day">{{ $createdAt->format('d') }}</span>
                    <span class="post-date-month">{{ $createdAt->format('M') }}</span>
                    <span class="post-date-year">{{ $createdAt->format('Y') }}</span>
                </div>
                <a href="/forum/t/{{ $proposal->discussion }}" class="post-comment">
                13
                </a>
            </div> 
            <div class="post-main">
                <h3 class="post-title"><a href="/congress/proposal/{{$proposal->id}}">#{{ strtoupper(substr(str_replace("https://ipfs.marscoin.org/ipfs/", "", $proposal->ipfs_hash), 1, 8)) }} Proposal: {{ $proposal->title }}</a></h3>
                <h4 class="post-meta">Submitted by <a target="_blank" href="/citizen/id/{{ $proposal->public_address }}">{{ $proposal->author }}</a> in <a href="javascript:;">{{str_replace("poll", "Certified Poll", $proposal->category)}}</a></h4>
                <div class="post-content">      
                <p>Integer eu velit vel sapien elementum suscipit in euismod justo. Etiam nec odio porta dui facilisis congue vel et neque. Duis a aliquam ante, a viverra mauris. Sed ipsum nibh, consectetur eu metus vitae, convallis ornare tellus. Maecenas nec est pharetra, ornare leo eget, pellentesque urna. Maecenas sit amet maximus orci. Praesent ullamcorper arcu sed purus tristique fringilla. Nam libero libero, porta id placerat nec, malesuada nec lacus...<a href="/congress/proposal/{{$proposal->id}}">Read More...</a></p>
                    <div class="row">
                        <div class="col-sm-4" style="padding-top: 14px;">
                        <a href="#" class="btn btn-success">Voting in Progress</a>
                        </div>
                        <div class="col-sm-4">
                        @php
                            $endTime = \Carbon\Carbon::parse($proposal->mined)->addDays($proposal->duration)->format('Y-m-d H:i:s');
                        @endphp
                        <x-countdown-timer :proposal-id="$proposal->id" :end-time="$endTime" :start-time="$proposal->mined" />
                        </div>
                        <div class="col-sm-4">
                        @livewire('voting-progress', ['proposalId' => $proposal->id])
                        </div>
                    </div>
                </div> 
            </div>
        </div>
        @endforeach 

    </div>

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

