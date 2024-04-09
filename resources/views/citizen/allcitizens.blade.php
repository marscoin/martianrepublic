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
              <?php foreach($everyCitizen as $ct){?>
                <tr>
                  <td>
                  <img src="{{ $ct->avatar_link }}"  onerror="this.onerror=null; this.src='https://martianrepublic.org/assets/citizen/generic_profile.jpg'" class="profile-avatar-img thumbnail" alt="Profile Image. Source {{ $ct->avatar_link }}">
                  </td>
                  <td class="valign-middle">
                      <a target="_blank" href="/citizen/id/<?=$ct->public_address?>" title=""><?=$ct->firstname?> <?=$ct->lastname?> </a>
                    <p><a target="_blank" href="https://explore.marscoin.org/tx/<?=$ct->txid?>"><?=$ct->public_address?></a></p>
                  </td>
                  <td class="valign-middle"><?=$ct->mined?></td>
                  <td class="file-info valign-middle">
                      <span class="label label-success demo-element public-status">Citizen</span><br>
                      <span class="label label-warning row-stat-badge">Endorsements: +<?=0 + $ct->endorse_cnt?></span>
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