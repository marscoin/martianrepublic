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
                      <object id="photo" data="/assets/citizen/<?= $gp->address ?>/profile_pic.png" class="profile-avatar-img thumbnail" alt="Profile Image" style="max-height: 100px;"  type="image/png">
                      <img src="/assets/citizen/generic_profile.jpg" class="profile-avatar-img thumbnail">
                    </object>
                  </td>
                  <td class="valign-middle">
                      <a target="_blank" href="/citizen/id/<?=$gp->address?>" title=""><?=$gp->fullname?> </a>
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
            @livewire('block-display')
            <br>
            @livewire('martian-republic-stats')
            <br>
            @livewire('civic-status-feed')
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