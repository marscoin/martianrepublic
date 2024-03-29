<!-- <h3 class="content-title"><u>Proof of Humanity - Join the General Martian Public</u></h3> -->

  <div class="row">

    <div class="col-md-3 col-sm-5">

      <div class="profile-avatar">
        <img src="/assets/citizen/<?=$public_address?>/profile_pic.png" class="profile-avatar-img thumbnail" alt="Profile Image. Source <?=$user['data']->data->picture?>">
      </div> <!-- /.profile-avatar -->



      <div class="nav nav-stacked list-group">  

        <a href="#notary" data-toggle="tab" class="list-group-item">
          <i class="fa fa-bank text-primary"></i> &nbsp;&nbsp;Notarized Activity Feed

          <i class="fa fa-chevron-right list-group-chevron"></i>
          <span class="badge">2</span>
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

        <a href="#settings" data-toggle="tab" class="list-group-item">
          <i class="fa fa-cog text-primary"></i> &nbsp;&nbsp;Settings

          <i class="fa fa-chevron-right list-group-chevron"></i>
        </a> 
      </div> <!-- /.list-group -->



    </div> <!-- /.col -->



    <div class="col-md-6 col-sm-7">

      <h3><?=$user['data']->data->firstName?> <?=$user['data']->data->lastName?> </h3>

      <h5 class="text-muted"><?=$user['data']->data->shortbio?> </h5>

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
      <br><br>


      <div id="myTabContent" class="tab-content stacked-content">

        <div class="tab-pane fade active in" id="notary">


          <h4 class="content-title"><u>Blockchain Notarized Public Activity Feed</u></h4>


     <?php foreach($feed as $f){?>

          <div class="feed-item feed-item-file">
            <div class="feed-icon">
              <i class="fa fa-link"></i>
            </div> <!-- /.feed-icon -->
            <div class="feed-subject">
              <?php if($f->tag == 'ED'){?>
              <p>Endorsement of Citizen</p>
              <?php } ?>
              <?php if($f->tag == 'SP'){?>
              <p>Signed Public Message</p>
              <?php } ?>
            </div> <!-- /.feed-subject -->
            <div class="feed-content">
              <?php if($f->tag == 'ED'){?>
                <p><?=$user['data']->data->firstName?> <?=$user['data']->data->lastName?> </a> successfully <strong>notarized</strong> an endorsement for <?=$f->message?></p>
              <?php } ?>
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


            <div class="feed-item feed-item-file">

              <div class="feed-icon">
                <i class="fa fa-drivers-license"></i>
              </div> <!-- /.feed-icon -->

              <div class="feed-subject">
                <p><a href="javascript:;"><?=$user['data']->data->firstName?> <?=$user['data']->data->lastName?> </a> successfully <strong>notarized</strong> his <a href="javascript:;">General Martian Public</a> application</p>
              </div> <!-- /.feed-subject -->

              <div class="feed-content">
                <ul class="icons-list">
                  <li>
                    <i class="icon-li fa fa-file-text-o"></i>
                    <a href="#">Data Set</a> - (Basic Biographic Data)
                  </li>

                  <li>
                    <i class="icon-li fa fa-file-text-o"></i>
                    <a href="<?=$user['data']->data->picture?>">Profile Picture</a> - (Basic Biometric Identifier)
                  </li>

                  <li>
                    <i class="icon-li fa fa-file-text-o"></i>
                    <a href="<?=$user['data']->data->video?>">Liveness Video</a> - (Basic Proof of Humanity)
                  </li>
                </ul>
              </div> <!-- /.feed-content -->

              <div class="feed-actions">
                <a href="javascript:;" class="pull-left"><i class="fa  fa-lock"></i> 123</a> 

                <a href="javascript:;" class="pull-right"><i class="fa fa-clock-o"></i> timestamp</a>
              </div> <!-- /.feed-actions -->

            </div> <!-- /.feed-item -->


            <div class="feed-item feed-item-question">

              <div class="feed-icon">
                <i class="fa fa-legal"></i>
              </div> <!-- /.feed-icon -->

              <div class="feed-subject">
                <p><a href="javascript:;"><?=$user['data']->data->firstName?> <?=$user['data']->data->lastName?> </a> pledged allegiance to <a href="javascript:;">The Martian Congressional Republic</a></p>
              </div> <!-- /.feed-subject -->

              <div class="feed-content">
                <ul class="icons-list">
                  <li>
                    <i class="icon-li fa fa-quote-left"></i>
                    I herewith declare that I, <?=$user['data']->data->firstName?> <?=$user['data']->data->lastName?> , am human and a member of the Martian Republic.
                  </li>
                </ul>
              </div> <!-- /.feed-content -->

              <div class="feed-actions">
                <a href="javascript:;" class="pull-left"><i class="fa  fa-lock"></i> 123</a>

                <a href="javascript:;" class="pull-right"><i class="fa fa-clock-o"></i> timestamp</a>
              </div> <!-- /.feed-actions -->

            </div> <!-- /.feed-item -->


           </div> <!-- /.tab-pane -->

            <div class="tab-pane fade" id="research">
            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium.</p>
            </div> <!-- /.tab-pane -->

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

        <h5 class="content-title"><u>MCR Citizen Stats</u></h5>

        <div class="list-group">  

          <a href="javascript:;" class="list-group-item">
              <h3 class="pull-right"><i class="fa fa-thumbs-o-up text-primary"></i></h3>
              <h4 class="list-group-item-heading"><?=count($endorsed)?></h4>
              <p class="list-group-item-text">Endorsements</p>                  
            </a>

          <a href="javascript:;" class="list-group-item">
            <h3 class="pull-right"><i class="fa fa-files-o   text-primary"></i></h3>
            <h4 class="list-group-item-heading">0</h4>
            <p class="list-group-item-text">Proposals Initiated</p>
          </a>

          <a href="javascript:;" class="list-group-item">
            <h3 class="pull-right"><i class="fa  fa-legal  text-primary"></i></h3>
            <h4 class="list-group-item-heading">0</h4>
            <p class="list-group-item-text">Ballots cast</p>
          </a>
        </div> <!-- /.list-group -->

        <br>

        <h5 class="content-title"><u>Blockchain Activity (Example)</u></h5>

        <div class="well">


          <ul class="icons-list text-md">

            <li>
              <i class="icon-li fa fa-location-arrow"></i>

              <strong>Roberta "Bobby" Draper</strong> notarized 6 files. 
              <br>
              <small>about 4 hours ago</small>
            </li>

            <li>
              <i class="icon-li fa fa-location-arrow"></i>

              <strong>Theresa Yao</strong> published a research document: <a href="javascript:;">Open Access Chip Fablab</a>. 
              <br>
              <small>about 6 hours ago</small>
            </li>

            <li>
              <i class="icon-li fa fa-location-arrow"></i>

              <strong>Joe Miller</strong> joined the general Martian public. 
              <br>
              <small>7 hours ago</small>
            </li>
          </ul>

        </div> <!-- /.well -->

      </div> <!-- /.col -->

    </div> <!-- /.row -->
