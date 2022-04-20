<?php 
use App\Includes\AppHelper;
?>
<h3 class="content-title"><u>Applicants</u></h3>
<div class="row">
    <div class="col-md-9">
        <div class="table-responsive">
            <table class="table table-striped table-bordered thumbnail-table">
                <thead>
                    <tr>
                        <th style="width: 150px">Profile Picture</th>
                        <th>Not Blockchain Notarized Yet</th>
                        <th>Since</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($everyApplicant as $apps){?>
                    <tr>
                        <td>
                            <img id="photo" src="/assets/citizen/<?= $apps->address ?>/profile_pic.png" class="profile-avatar-img thumbnail" alt="Profile Image" style="max-height: 100px;">
                        </td>
                        <td class="valign-middle">
                            <a href="javascript:;" title=""><?= $apps->fullname ?> </a>
                            <p><a target="_blank"  href="#><?= $apps->address ?></a></p>

                            <?php if($isCitizen && $apps->address != $public_address){ ?>
                            <a data-toggle="modal" href="#endorseModal" data-endorse="{{{$apps->userid}}}"  data-name="{{{$apps->fullname}}}" data-address="{{{$apps->address}}}"
                              class="btn-sm btn-primary demo-element endorse-btn">Donate Marscoin</a>
                            <?php } ?>
                        </td>
                        <td class="valign-middle">n/a</td>
                        <td class="file-info valign-middle">
                            <span class="label label-default demo-element public-status">Applicant</span>
                        </td>
                    </tr>
                    <?php } ?>
                    

                </tbody>
            </table>

        </div>
    </div>

    <div class="col-md-3">
        <h5 class="content-title"><u>Martian Republic Stats</u></h5>

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
                    <?php } else if($a->tag == "SP"){ ?>
                        <i class="icon-li fa fa-quote-left"></i>
                        <strong><?=$a->fullname?></strong> signed public message.
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


