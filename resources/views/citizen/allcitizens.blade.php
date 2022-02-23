<?php 
use App\Includes\AppHelper;
?>
<h3 class="content-title"><u>Citizens</u></h3>
<div class="row">
  <div class="col-md-9">
        <div class="table-responsive">
          <table class="table table-striped table-bordered thumbnail-table">
                        <thead>
                            <tr>
                                <th style="width: 150px">Profile Picture</th>
                                <th>Public Address</th>
                                <th>Since</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                          <?php foreach($everyCitizen as $gp){?>
                            <tr>
                              <td>
                                 <img id="photo" src="/assets/citizen/<?=$gp->address?>/profile_pic.png" class="profile-avatar-img thumbnail" alt="Profile Image">
                              </td>
                              <td class="valign-middle">
                                 <a href="javascript:;" title=""><?=$gp->fullname?> </a>
                                <p><a target="_blank" href="https://explore.marscoin.org/tx/<?=$gp->txid?>"><?=$gp->address?></a></p>
                              </td>
                              <td class="valign-middle"><?=$gp->mined?></td>
                              <td class="file-info valign-middle">
                                  <span class="label label-success demo-element public-status">Citizen</span><br>
                                  <span class="label label-warning row-stat-badge">Endorsements: +<?=0 + $gp->endorse_cnt?></span>
                              </td>
                            </tr>
                            <?php } ?>
                    
                           
                        </tbody>
                       </table>

                      </div> 
        </div> 

  <div class="col-md-3">
            <h5 class="content-title"><u>Martian Society Stats</u></h5>

            <div class="list-group">  

            <a href="javascript:;" class="list-group-item">
                <h3 class="pull-right"><i class="fa fa-globe text-primary"></i></h3>
                <h4 class="list-group-item-heading"><?=count($everyPublic)?></h4>
                <p class="list-group-item-text">Martians</p>
            </a>

            <a href="javascript:;" class="list-group-item">
                <h3 class="pull-right"><i class="fa fa-user  text-primary"></i></h3>
                <h4 class="list-group-item-heading"><?=count($everyCitizen)?></h4>
                <p class="list-group-item-text">Citizens</p>
            </a>

            <a href="javascript:;" class="list-group-item">
                <h3 class="pull-right"><i class="fa fa-pencil  text-primary"></i></h3>
                <h4 class="list-group-item-heading">0</h4>
                <p class="list-group-item-text">Open Proposals</p>
            </a>

            <a href="javascript:;" class="list-group-item">
                <h3 class="pull-right"><i class="fa fa-legal  text-primary"></i></h3>
                <h4 class="list-group-item-heading">0</h4>
                <p class="list-group-item-text">Open Bills</p>
            </a>
            </div> <!-- /.list-group -->

            <br>

            <h5 class="content-title"><u>Activity</u></h5>

            <div class="well">


              <ul class="icons-list text-md">

              <?php foreach($activity as $a){ ?>

                <li>
                    <?php if($a->tag == "GP"){ ?>
                        <i class="icon-li fa fa-address-card"></i>
                        <strong><?=$a->fullname?></strong> joined the Republic.
                    <?php } else if($a->tag == "CT"){ ?>
                        <i class="icon-li fa fa-rocket"></i>
                        <strong><?=$a->fullname?></strong> became citizen.
                    <?php } else if($a->tag == "ED"){ ?>
                        <i class="icon-li fa fa-thumbs-up"></i>
                        <strong><?=$a->fullname?></strong> endorsed someone.
                    <?php } else if($a->tag == "PR"){ ?>
                        <i class="icon-li fa fa-pencil"></i>
                        <strong><?=$a->fullname?></strong> published proposal.
                    <?php } ?>
                    <br>
                    <small>about <?=AppHelper::time_elapsed_string($a->mined)?></small>
                </li>

            <?php } ?>
               
              </ul>

            </div> <!-- /.well -->

            <div class="row">

                <div class="col-md-6 col-sm-6">
                  <div class="thumbnail">
                    <div class="thumbnail-view">
                      <a href="/assets/citizen/mars_flag_q1.png" class="thumbnail-view-hover ui-lightbox"></a>
                            <img src="/assets/citizen/mars_flag_q1.png" style="width: 100%" alt="Gallery Image">
                        </div>
                        </div>          

                </div> <!-- /.col -->


                <div class="col-md-6 col-sm-6">


                  <div class="thumbnail">
                    <div class="thumbnail-view">
                      <a href="/assets/citizen/mars_flag2.png" class="thumbnail-view-hover ui-lightbox"></a>
                            <img src="/assets/citizen/mars_flag2.png" style="width: 100%" alt="Gallery Image">
                        </div>
                        </div>        

                </div> <!-- /.col -->

            </div>
            
            <div class="row">

                <div class="col-md-6 col-sm-6">

                  <div class="thumbnail">
                    <div class="thumbnail-view">
                      <a href="/assets/citizen/mars_flag5.jpg" class="thumbnail-view-hover ui-lightbox"></a>
                            <img src="/assets/citizen/mars_flag5.jpg" style="width: 100%" alt="Gallery Image">
                        </div>
                        </div>          

                  </div> <!-- /.col -->
                  <div class="col-md-6 col-sm-6">

                      <div class="thumbnail">
                        <div class="thumbnail-view">
                          <a href="/assets/citizen/mars_flag5_q2.png" class="thumbnail-view-hover ui-lightbox"></a>
                                <img src="/assets/citizen/mars_flag5_q2.png" style="width: 100%" alt="Gallery Image">
                            </div>
                            </div>          

                      </div> <!-- /.col -->


            </div>

              </div>

</div> <!-- /.col -->