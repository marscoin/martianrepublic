<h3 class="content-title"><u>All Proposals</u></h3>

    @foreach ($proposals as $proposal)
        <div class="feed-item feed-item-idea">

            <div class="feed-icon">
                <i class="fa fa-lightbulb-o"></i>
            </div> <!-- /.feed-icon -->
            <div class="feed-subject">
                <h5>Proposal #{{ $proposal->id }}</h5>
                <h3><a href="javascript:;">{{ $proposal->author }} </a> proposed: <a
                    href="https://ipfs.io/ipfs/{{{$proposal->ipfs_hash}}}">{{ $proposal->title }} </a></h3>
            </div> <!-- /.feed-subject -->



            <div class="feed-content">
                <ul class="icons-list">
                    <li>
                        <i class="icon-li fa fa-quote-left"></i>
                        <p style="font-size: 2rem">
                            {{ $proposal->description }}

                        </p>
                    </li>
                </ul>
                <span>Vote Threshold: {{$proposal->threshold}}%</span>
                <div class="progress progress-sm" style="width: 50%">
                    <div class="progress-bar progress-bar-primary" role="progressbar"
                        aria-valuenow={{$proposal->threshold}} aria-valuemin="0" aria-valuemax="100"
                        style="width: {{{$proposal->threshold}}}%">
                        <span class="sr-only">40% Complete (primary)</span>
                    </div>
                </div>
                <div>

                        <div id="accordion-help" class="panel-group accordion-simple">
                
                            <div class="panel">
                                <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion-help" href="#faq-general-1"><i class="fa accordion-caret"></i><i class="fa faq-accordion-caret"></i>Yes</a>
                                </h4>
                                </div><!-- .panel-heading -->
                
                                <div id="faq-general-1" class="panel-collapse collapse" style="height: 0px;">
                                <div class="panel-body">
                                    <p>{{$proposal->yes_vote_addr}}</p>
                                </div><!-- .panel-body -->
                                </div> <!-- ./panel-collapse -->
                            </div><!-- .panel -->
                
                            <div class="panel">
                                <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion-help" href="#faq-general-2"><i class="fa accordion-caret"></i><i class="fa faq-accordion-caret"></i>No</a>
                                </h4>
                                </div><!-- .panel-heading -->
                
                                <div id="faq-general-2" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <p>{{$proposal->no_vote_addr}}</p>
                                </div><!-- .panel-body -->
                                </div> <!-- ./panel-collapse -->
                            </div><!-- .panel -->
                
                            <div class="panel">
                                <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion-help" href="#faq-general-3"><i class="fa accordion-caret"></i><i class="fa faq-accordion-caret"></i>Null</a>
                                </h4>
                                </div><!-- .panel-heading -->
                
                                <div id="faq-general-3" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <p>{{$proposal->null_vote_addr}}</p>
                                </div><!-- .panel-body -->
                                </div> <!-- ./panel-collapse -->
                            </div><!-- .panel -->
                
                            </div> <!-- /.accordion -->

                </div>
            </div> <!-- /.feed-content -->


            <div class="feed-actions">
                
                <a href={{ $proposal->discussion }} class="pull-left discussion-link">
                    {{ $proposal->discussion }} <i class="fa fa-external-link"></i></a>

                <a href="javascript:;" class="pull-right"><i class="fa fa-clock-o"></i>
                    {{ $proposal->created_at }}</a>
            </div> <!-- /.feed-actions -->

        </div>
    @endforeach