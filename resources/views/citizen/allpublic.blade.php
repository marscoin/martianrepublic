<?php 
use App\Includes\AppHelper;
?>
<h3 class="content-title"><u>General Public</u></h3>
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
                    <?php foreach($everyPublic as $gp){?>
                    <tr>
                        <td>
                            <object id="photo" data="/assets/citizen/<?= $gp->address ?>/profile_pic.png" class="profile-avatar-img thumbnail" alt="Profile Image" style="max-height: 100px;"  type="image/png">
								<img src="/assets/citizen/generic_profile.jpg" class="profile-avatar-img thumbnail">
							</object>
                        </td>
                        <td class="valign-middle">
                            <a href="javascript:;" title=""><?= $gp->fullname ?> </a>
                            <p><a target="_blank"
                                    href="https://explore.marscoin.org/tx/<?= $gp->txid ?>"><?= $gp->address ?></a></p>

                            <?php if($isCitizen && $gp->address != $public_address && !$gp->citizen){ ?>
                            <a data-toggle="modal" href="#endorseModal" data-endorse="{{{$gp->userid}}}"  data-name="{{{$gp->fullname}}}" data-address="{{{$gp->address}}}"
                              class="btn-sm btn-primary demo-element endorse-btn">Endorse for Citizenship</a>
                            <?php } ?>
                        </td>
                        <td class="valign-middle"><?= $gp->mined ?></td>
                        <td class="file-info valign-middle">
                            <span class="label label-default demo-element public-status">General Public</span>
                        </td>
                    </tr>
                    <?php } ?>
                    

                </tbody>
            </table>

        </div>
    </div>

    <div class="col-md-3">
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

<!--Modal Start -->


<div id="endorseModal" class="modal fade dynamic-vote-modal">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              <h3 class="modal-title" id="endorse-title"></h3>
          </div> <!-- /.modal-header -->
          <div class="modal-body">
              <div class="modal-body-box">
                  <p class="modal-category"> </p>
              </div>
              <div class="modal-body-box">
                  <h5> User Address </h5>
                  <p class="modal-description" id="endorse-address"></p>
              </div>
            
              <div class="modal-body-box">
                  <h5>Cost of Endorsement: </h5>
                  <p class="modal-cost" id="endorse-cost"></p>

                  <hr>
                  <p>*All endorsements are permanent records on the Marscoin blockchain. A member of the public requires 10% of existing citizens or a maximum of 5 endorsements to attain citizenship status.</p>
              </div>
              

              <div class="modal-message" id="confirm-success-message" style="display: none">
                  <span id="modal-message-error" style="color:red; font-weight: 600"> </span>
                  <span id="modal-message-success" style="font-weight: 600; display: none;"> <i class="fa fa-check-circle"></i> Successfully Endorsed <h3></h3></span>
              </div>
              <div class="modal-message" style="display: flex; align-items: center">
                    <a  rel="noreferrer noopener"  target="_blank"  class="transaction-hash-link" href="">
                        <h5 class="transaction-hash" id="confirm-transaction-hash">
                        </h5>
                    </a>
                </div>
            </div>
          <div class="modal-footer">
              <button id="confirm-endorse-btn" type="submit" data-confirm data-endorse class="btn btn-primary submit-endorse">Confirm Endorsement</button>
              <img src="/assets/citizen/loading.gif" alt="enter image description here" style="display: none" id="confirm-loading">
          </div> 
      </div>
  </div>
</div>


