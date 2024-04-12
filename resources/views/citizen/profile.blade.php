  <div class="row">

    <div class="col-md-3 col-sm-5">

      <div class="profile-avatar">
        <img src="{{ $citcache->avatar_link }}"  onerror="this.onerror=null; this.src='https://martianrepublic.org/assets/citizen/generic_profile.jpg'" class="profile-avatar-img thumbnail" alt="Profile Image. Source {{ $citcache->avatar_link }}">
      </div> 

      <div class="nav nav-stacked list-group">  

        <a href="#notary" data-toggle="tab" class="list-group-item">
          <i class="fa fa-bank text-primary"></i> &nbsp;&nbsp;Notarized Activity Feed

          <i class="fa fa-chevron-right list-group-chevron"></i>
          <span class="badge">{{$recentActivityCount}}</span>
        </a> 

        <a href="#research" data-toggle="tab" class="list-group-item">
          <i class="fa fa-book text-primary"></i> &nbsp;&nbsp;Research Projects

          <i class="fa fa-chevron-right list-group-chevron"></i>
          
        </a> 

        <a href="#signed" data-toggle="tab" class="list-group-item">
          <i class="fa fa-envelope text-primary"></i> &nbsp;&nbsp;Signed Messages

          <i class="fa fa-chevron-right list-group-chevron"></i>
        </a> 

        <a href="#endorsed" data-toggle="tab" class="list-group-item">
          <i class="fa fa-group text-primary"></i> &nbsp;&nbsp;Endorsed By

          <i class="fa fa-chevron-right list-group-chevron"></i>
        </a> 

        <a href="#digid" data-toggle="tab" class="list-group-item">
          <i class="fa fa-drivers-license text-primary"></i> &nbsp;&nbsp;Digital ID

          <i class="fa fa-chevron-right list-group-chevron"></i>
        </a> 

       
      </div> <!-- /.list-group -->



    </div> <!-- /.col -->



    <div class="col-md-6 col-sm-7">

      <h3><?=$citcache->firstName?> <?=$citcache->lastName?> </h3>

      <h5 class="text-muted"><?=$citcache->shortbio?> </h5>

      <hr>

      <p>
        @if($isGP)
        <a target="_blank" href="https://explore.marscoin.org/tx/<?=$mePublic['txid']?>" class="btn btn-primary">Member of the General Martian Public</a>
        &nbsp;&nbsp;
        @endif
        @if(!is_null($meCitizen))
        <a target="_blank" href="https://explore.marscoin.org/tx/<?=$meCitizen['txid']?>" class="btn btn-success">MCR Citizen</a>
        @endif
      </p>

      <hr>
      <ul class="icons-list">
        @if($isGP)
            <li><i class="icon-li fa fa-users"></i> Joined as Member of the Martian Republic on <?=date( 'M, jS, Y', strtotime($mePublic['mined']) )?></li>
        @endif
        @if(!is_null($meCitizen))
            <li><i class="icon-li fa fa-drivers-license"></i> Gained Citizenship status on <?=date( 'M, jS, Y', strtotime($meCitizen['mined']) )?></li>
        @endif
            <li><i class="icon-li fa fa-globe"></i> Awaiting Starship flight</li>
      </ul>
      <hr>

      <div id="myTabContent" class="tab-content stacked-content">

          @livewire('civic-activity-feed', ['userId' => Auth::id()])

          @livewire('research-activity')

          <div class="tab-pane fade" id="signed">
          
                <h4 class="content-title"><u>Public Message</u></h4>
                <div class="share-widget clearfix">

                <textarea id="signedpublishpost" class="form-control share-widget-textarea" rows="3" placeholder="What would you like to publish?..." tabindex="1"></textarea>

                <div class="share-widget-actions">
                  <div class="share-widget-types pull-left">
                  <img id="signedpublishprogress" src="/assets/citizen/loading.gif" alt="enter image description here" style="display: none" id="loading">
                  <a target="_blank" id="signedpublishhash" href="" title=""></a>
                    <!-- <a id="signedpublishpic" href="javascript:;" class="fa fa-picture-o ui-tooltip" title="" data-original-title="Post an Image"><i></i></a>
                    <a id="signedpublishvid"href="javascript:;" class="fa fa-video-camera ui-tooltip" title="" data-original-title="Upload a Video"><i></i></a> -->

                  </div>	

                <div class="pull-right">
                  <a id="signedpublishbtn" class="btn btn-primary btn-sm" tabindex="2">Sign and Publish</a>
                </div>

                </div> <!-- /.share-widget-actions -->

              </div>



                <?php foreach($feed as $f){
                  if($f->tag != 'SP')
                    continue;
                ?>

                    <div class="feed-item feed-item-file">
                      <div class="feed-icon">
                        <i class="fa fa-link"></i>
                      </div> <!-- /.feed-icon -->
                      <div class="feed-subject">
                        <?php if($f->tag == 'SP'){?>
                        <p>Signed Public Message</p>
                        <?php } ?>
                      </div> <!-- /.feed-subject -->
                      <div class="feed-content">
                        <?php if($f->tag == 'SP'){?>
                          <p><blockquote><?=str_replace('\"', "'", str_replace('\n', "\n", $f->message))?></blockquote></p>
                        <?php } ?>
                      </div> <!-- /.feed-content -->
                      <div class="feed-actions">
                        <a target="_blank" href="https://explore.marscoin.org/tx/<?=$f->txid?>" class="pull-left"><i class="fa  fa-lock"></i> <?=$f->blockid?></a> 
                        <a target="_blank" href="https://explore.marscoin.org/tx/<?=$f->txid?>" class="pull-right"><i class="fa fa-clock-o"></i> <?=$f->mined?></a>
                      </div> 
                    </div> 


                      <?php } ?>




          </div> <!-- /.tab-pane -->

            <div class="tab-pane fade" id="endorsed">

            <h4 class="content-title"><u>Public Endorsements</u></h4>
                    
            <?php foreach($endorsed as $e){?>

                <div class="feed-item feed-item-idea">
                  <div class="feed-icon">
                    <i class="fa fa-thumbs-up"></i>
                  </div> <!-- /.feed-icon -->
                  <div class="feed-subject">
                    <?php if($e->tag == 'ED'){?>
                    <p>Your were endorsed by another citizen</p>
                    <?php } ?>
                  </div> <!-- /.feed-subject -->
                  <div class="feed-content">
                      <p><b><a target="_blank" href="https://explore.marscoin.org/tx/<?=$e->txid?>"><?=$e->address?></b> </a> successfully <strong>notarized</strong> an endorsement for you on the blockchain.</p>
                  </div> <!-- /.feed-content -->
                  <div class="feed-actions">
                    <a href="javascript:;" class="pull-left"><i class="fa  fa-lock"></i> <?=$e->blockid?></a> 
                    <a href="javascript:;" class="pull-right"><i class="fa fa-clock-o"></i> <?=$e->mined?></a>
                  </div> <!-- /.feed-actions -->
                </div> <!-- /.feed-item -->


                <?php } 
                if(count($endorsed) <= 0){
                ?>
                <p>You haven't received any endorsements yet by other citizens.</p>
                <?php } ?>
                    
            </div> <!-- /.tab-pane -->

            <div class="tab-pane fade" id="digid">
              <h4 class="content-title"><u>Printable Digital ID</u></h4>
              <p> <a class="btn btn-secondary btn-lg btn-block " target="_blank" href="/citizen/printout"><i class="fa  fa-drivers-license"></i> Basic Design</a></p>
              <p> <a class="btn btn-secondary btn-lg btn-block " target="_blank" href="/citizen/printout2"><i class="fa  fa-drivers-license"></i> Space Theme</a></p>
              <p> <a class="btn btn-secondary btn-lg btn-block " target="_blank" href="/citizen/printout3"><i class="fa  fa-drivers-license"></i> Mars Theme</a></p>
            </div> <!-- /.tab-pane -->

            <div class="tab-pane fade" id="settings">
              <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium.</p>
            </div> <!-- /.tab-pane -->

          </div> <!-- /.tab-content -->


        <br class="visible-xs">            
        <br class="visible-xs">
        
      </div> <!-- /.col -->


      <div class="col-md-3">

        @livewire('citizen-stats')

        <br>

        @livewire('blockchain-activity-feed')

      </div> <!-- /.col -->

    </div> <!-- /.row -->
