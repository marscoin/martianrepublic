<html lang="en" class="no-js">
<head>
    <title>Martian Republic - Congress</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald:400,300,700">
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@700&family=Orbitron:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/wallet/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-admin.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-flat.css">
    <link rel="stylesheet" href="/assets/wallet/css/voting/voting.css">
    <link rel="stylesheet" href="/assets/wallet/css/simplemde.min.css">
    <link rel="shortcut icon" href="/assets/favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" integrity="sha512-1cK78a1o+ht2JcaW6g8OXYwqpev9+6GqOkz9xmBN9iUUhIndKtxwILGWYOSibOKjLsEdjyjZvYDq/cZwNeak0w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="/assets/wallet/js/plugins/scan/qrcode-gen.min.js"></script>
</head>

<body class=" ">
    <div id="wrapper">
        <header class="navbar navbar-inverse" role="banner">
            <div class="container">
                <div class="navbar-header">
                    @include('wallet.header')
                </div> <!-- /.navbar-header -->
                <nav class="collapse navbar-collapse" role="navigation">
                    @include('wallet.navbarleft')
                    @include('wallet.navbarright')
                </nav>
            </div> <!-- /.container -->
        </header>
        @include('wallet.mainnav', array('active'=>'congress'))

        
        <div class="content">

            <div class="container">


<div class="row layout layout-stack-sm layout-main-left">

          <div class="col-sm-8 col-md-8 col-lg-9 layout-main">

            <div class="posts">

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

            

                    <ul id="myTab" class="nav nav-pills nav-stacked">
                        <li class="active">
                            <a href="#info" data-toggle="tab">
                            <i class="fa-solid fa-circle-info"></i>
                            </a>
                        </li>

                        <li class="">
                            <a href="#timeline" data-toggle="tab">
                            <i class="fa-solid fa-timeline"></i>
                            </a>
                        </li>

                        <li class="">
                            <a href="#comments" data-toggle="tab">
                            <i class="fa-solid fa-comment"></i>
                            </a>
                        </li>

                       
                    </ul>


                </div> <!-- /.post-aside -->

                <div class="post-main">
                <h3 class="post-title"><a href="#">{{ $proposal->title }}</a></h3>
                <h4 class="post-meta">Submitted by <a target="_blank" href="/citizen/id/{{ $proposal->public_address }}">{{ $proposal->author }}</a> in <a href="javascript:;">{{str_replace("poll", "Certified Poll", $proposal->category)}}</a></h4>
                <h5>Proposal: {{ strtoupper(substr(str_replace("https://ipfs.marscoin.org/ipfs/", "", $proposal->ipfs_hash), 1, 8)) }} <a  target="_blank" href="{{$proposal->ipfs_hash}}"><i class="fa-solid fa-link"></i></a></h5>
                  <div class="post-content">  
                    
                    <div id="myTabContent" class="tab-content stacked-content">  
                        <div class="tab-pane fade active in" id="info">  
                            <p>{{ $proposal->description }}</p>
                        </div>
                        
                        
                        <div class="tab-pane fade" id="timeline">
                            <h3 class="content-title"><u>Timeline</u></h3>
                            @forelse($activities as $activity)
                            <div class="feed-item feed-item-file">
                                        @if($activity->tag === 'PR')
                                            <div class="feed-icon"><i style="padding-top: 10px;
    font-size: 14px;" class="fa fa-link"></i></div><div class="feed-subject"><p>Proposal notarized</p></div>
                                        @elseif($activity->tag === 'PRY')
                                            <div class="feed-icon"><i class="fa fa-link"></i></div><div class="feed-subject"><p>Ballot cast</p></div>
                                        @elseif($activity->tag === 'PRN')
                                            <div class="feed-icon"><i class="fa fa-link"></i></div><div class="feed-subject"><p>Ballot cast</p></div>
                                        @elseif($activity->tag === 'PRA')
                                            <div class="feed-icon"><i class="fa fa-link"></i></div><div class="feed-subject"><p>Ballot cast</p></div>
                                        @endif
                                    <div class="feed-content">
                                        @if($activity->tag === 'PR')
                                            <p>{{ $activity->firstname }} {{ $activity->lastname }} successfully <strong>notarized</strong> a proposal</p>
                                        @endif
                                    </div>
                                    <div class="feed-actions">
                                        <a target="_blank" href="https://explore.marscoin.org/tx/{{ $activity->txid }}" class="pull-left"><i class="fa fa-lock"></i> {{ $activity->blockid }}</a> 
                                        <a target="_blank" href="https://explore.marscoin.org/tx/{{ $activity->txid }}" class="pull-right">
                                            <i class="fa fa-clock-o"></i>
                                            @if ($activity->mined)
                                                {{ \Carbon\Carbon::parse($activity->mined)->diffForHumans() }}
                                            @else
                                                Date not available
                                            @endif
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <p>No recent activities found.</p>
                            @endforelse
                        </div>
                       
                        <div class="tab-pane fade" id="comments">
                           




                        <a id="comments"></a>

<div class="heading-block">
  <h3>Comments</h3>
</div>

<ol class="comment-list">

  <li>
    <div class="comment">

      <div class="comment-avatar">
        <img alt="" src="../../global/img/avatars/avatar-4-md.jpg" class="avatar">
      </div> <!-- /.comment-avatar -->

      <div class="comment-meta">

        <span class="comment-author">
          <a href="javascript:;" class="url">Peter Landt</a>
        </span>

        <a href="javascript:;" class="comment-timestamp">
          April 3, 2013 at 6:47 am
        </a>

        -

        <a class="comment-reply-link" href="javascript:;">Reply</a>

      </div> <!-- /.comment-meta -->

      <div class="comment-body">
        <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium.</p>
      </div> <!-- /.comment-body -->

    </div> <!-- /.comment -->
  </li>

  <li>
    <div class="comment">

      <div class="comment-avatar">
        <img alt="" src="../../global/img/avatars/avatar-3-md.jpg" class="avatar">
      </div> <!-- /.comment-avatar -->

      <div class="comment-meta">

        <span class="comment-author">
          <a href="javascript:;" class="url">Adelle Charles</a>
        </span>

        <a href="javascript:;" class="comment-timestamp">
          April 3, 2013 at 6:47 am
        </a>

        -

        <a class="comment-reply-link" href="javascript:;">Reply</a>

      </div> <!-- /.comment-meta -->

      <div class="comment-body">
        <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium.</p>
      </div> <!-- /.comment-body -->

    </div> <!-- /.comment -->

    <ol class="comment-list">
      <li>
        <div class="comment">

          <div class="comment-avatar">
            <img alt="" src="../../global/img/avatars/avatar-5-md.jpg" class="avatar">
          </div> <!-- /.comment-avatar -->

          <div class="comment-meta">

            <span class="comment-author">
              <a href="javascript:;" class="url">Enda Nasution</a>
            </span>

            <a href="javascript:;" class="comment-timestamp">
              April 3, 2013 at 6:47 am
            </a>

            -

            <a class="comment-reply-link" href="javascript:;">Reply</a>

          </div> <!-- /.comment-meta -->

          <div class="comment-body">
            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium.</p>
          </div> <!-- /.comment-body -->

        </div> <!-- /.comment -->                    
      </li>

      <li>
        <div class="comment">

          <div class="comment-avatar">
            <img alt="" src="../../global/img/avatars/avatar-5-md.jpg" class="avatar">
          </div> <!-- /.comment-avatar -->

          <div class="comment-meta">

            <span class="comment-author">
              <a href="javascript:;" class="url">Enda Nasution</a>
            </span>

            <a href="javascript:;" class="comment-timestamp">
              April 3, 2013 at 6:47 am
            </a>

            -

            <a class="comment-reply-link" href="javascript:;">Reply</a>

          </div> <!-- /.comment-meta -->

          <div class="comment-body">
            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium.</p>
          </div> <!-- /.comment-body -->

        </div> <!-- /.comment -->  


        <ol class="comment-list">
          <li>
            <div class="comment">

              <div class="comment-avatar">
                <img alt="" src="../../global/img/avatars/avatar-5-md.jpg" class="avatar">
              </div> <!-- /.comment-avatar -->

              <div class="comment-meta">

                <span class="comment-author">
                  <a href="javascript:;" class="url">Enda Nasution</a>
                </span>

                <a href="javascript:;" class="comment-timestamp">
                  April 3, 2013 at 6:47 am
                </a>

                -

                <a class="comment-reply-link" href="javascript:;">Reply</a>

              </div> <!-- /.comment-meta -->

              <div class="comment-body">
                <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium.</p>
              </div> <!-- /.comment-body -->

            </div> <!-- /.comment -->                    
          </li>
        </ol>



      </li>
    </ol>
  </li>



  <li>
    <div class="comment">

      <div class="comment-avatar">
        <img alt="" src="../../global/img/avatars/avatar-4-md.jpg" class="avatar">
      </div> <!-- /.comment-avatar -->

      <div class="comment-meta">

        <span class="comment-author">
          <a href="javascript:;" class="url">Peter Landt</a>
        </span>

        <a href="javascript:;" class="comment-timestamp">
          April 3, 2013 at 6:47 am
        </a>

        -

        <a class="comment-reply-link" href="javascript:;">Reply</a>

      </div> <!-- /.comment-meta -->

      <div class="comment-body">
        <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium.</p>
      </div> <!-- /.comment-body -->

    </div> <!-- /.comment -->
  </li>

</ol>

<br class="xs-60">

<div class="heading-block">
  <h3>Post a Comment</h3>
</div>

  <div class="form-group">
    <div class="col-md-8 col-sm-12">
      <label>Message: <span>*</span></label>
      <textarea class="form-control" id="text" name="text" rows="6" cols="40"></textarea>
    </div>
  </div>

  <div class="form-group">
    <div class="col-md-8 col-sm-12">
      <button class="btn btn-primary" type="submit">Submit Comment</button>
    </div>
  </div>

 
</form>














                        </div>
                    </div>
                  </div> <!-- /.post-content -->

                </div> <!-- /.post-main -->

              </div> <!-- /.post --> 

            </div> <!-- /.posts -->

            <br class="xs-30">


            

            

          </div> <!-- /.col -->



          <div class="col-sm-4 col-md-4 col-lg-3 layout-sidebar">

            <hr class="visible-xs">
            
            <?php if($isCitizen){?>
                
                @if($proposal->txid)
                    <a data-toggle="modal" href="#ProposalModal" id="" style="width: 100%; text-align: center;" class="btn-lg btn-primary demo-element "><i class="fa-solid fa-check-to-slot"></i> Cast Your Vote</a>
                @else
                    <a data-toggle="modal" href="/congress/ballot/{{$proposal->id}}" style="width: 100%; text-align: center;"  id="" class="btn-lg btn-secondary demo-element ">Request Ballot</a>
                @endif
                    
            <?php }else{ ?>
                <p>To cast a vote, please <a href="/citizen/all">join the voter registry</a> first</p>
            <?php } ?>


            <br class="xs-180">
            
                  <h4>
                    Vote Breakdown
                  </h4>
                
                <div id="pie-chart" class="chart-holder-250"></div>

                @php
                $endTime = \Carbon\Carbon::parse($proposal->mined)->addDays($proposal->duration)->format('Y-m-d H:i:s');
                @endphp
                <x-countdown-timer :proposal-id="$proposal->id" :end-time="$endTime" :start-time="$proposal->mined" />

                

            <br class="xs-50">

            <div class="list-group">

                  <a href="javascript:;" class="list-group-item">
                      <h3 class="pull-right"><i class="fa fa-gavel text-primary"></i></h3>
                      <h4 class="list-group-item-heading">{{$proposal->threshold}}%</h4>
                      <p class="list-group-item-text">Required to pass</p>
                    </a>

                  <a href="javascript:;" class="list-group-item">
                    <h3 class="pull-right"><i class="fa fa-users  text-primary"></i></h3>
                    <h4 class="list-group-item-heading">{{$proposal->participation}}%</h4>
                    <p class="list-group-item-text">Citizen participation needed</p>
                  </a>

                  <a href="javascript:;" class="list-group-item">
                    <h3 class="pull-right"><i class="fa fa-calendar  text-primary"></i></h3>
                    <h4 class="list-group-item-heading">{{$proposal->participation}}</h4>
                    <p class="list-group-item-text">Vote duration in days</p>
                  </a>

                  <a href="javascript:;" class="list-group-item">
                    <h3 class="pull-right"><i class="fa fa-multiply  text-primary"></i></h3>
                    <h4 class="list-group-item-heading">{{$proposal->expiration}}</h4>
                    <p class="list-group-item-text">Automatic expiration in years</p>
                  </a>
                </div>
            
            

          </div> <!-- /.col -->

        </div>



        </div> <!-- /.container -->

</div> <!-- .content -->

</div> <!-- /#wrapper -->

<footer class="footer">
@include('footer')
</footer>

<script src="/assets/wallet/js/dist/my_bundle.js"></script>
<script src="/assets/wallet/js/libs/jquery-1.10.2.min.js"></script>
<script src="/assets/wallet/js/libs/bootstrap.min.js"></script>
<script src="/assets/wallet/js/plugins/dataTables/jquery.dataTables.js"></script>
<script src="/assets/wallet/js/plugins/dataTables/dataTables.bootstrap.js"></script>
<script src="/assets/wallet/js/mvpready-core.js"></script>
<script src="/assets/wallet/js/mvpready-admin.js"></script>
<script src="/assets/wallet/js/demos/table_demo.js"></script>
<script src="/assets/wallet/js/jquery-ui.min.js"></script>
<script src="/assets/wallet/js/simplemde.min.js"></script>
<script src="/assets/wallet/js/md5.min.js"></script>
<script src="/assets/wallet/js/sha256.js"></script>

<script src="/assets/wallet/js/plugins/flot/jquery.flot.js"></script>
<script src="/assets/wallet/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
<script src="/assets/wallet/js/plugins/flot/jquery.flot.pie.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });  

});
</script>
<script>
$(document).ready(function() {

    $.ajax({
        url: "/congress/vote/breakdown",
        type: "POST", // Specify the method explicitly
        data: { "proposalId": <?=$proposal->id?> },
        dataType: 'json', // Expect a JSON response
        success: function(data) {
            chartOptions = {		
                series: {
                    pie: {
                        show: true,  
                        innerRadius: 0, 
                        stroke: {
                            width: 4
                        }
                    }
                }, 
                legend: {
                    show: false,
                    position: 'ne'
                }, 
                tooltip: true,
                tooltipOpts: {
                    content: '%s: %y'
                },
                grid: {
                    hoverable: true
                },
                colors: mvpready_core.layoutColors
            }

            var chartData = [
                { label: "Yay", data: Math.floor(data.yayPercent + 1) }, 
                { label: "Nay", data: Math.floor(data.nayPercent + 1) }
                // { label: "Yay", data: Math.floor (Math.random() * 100 + 250) }, 
                // { label: "Nay", data: Math.floor (Math.random() * 100 + 350) }
            ];

            var holder = $('#pie-chart');
            if (holder.length) {
                $.plot(holder, chartData, chartOptions);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error fetching vote breakdown: ", textStatus, errorThrown);
        }
    });

});
</script>

</body>

</html>
