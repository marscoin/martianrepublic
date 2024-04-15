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
                    <th>Public Address</th>
                    <th>Since</th>
                    <th>Status</th>
                    <th>Missing Fields</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                
                foreach ($everyApplicant as &$apps) {
                    $missing_fields_count = 0;
                    if (empty($apps->firstname)) $missing_fields_count++;
                    if (empty($apps->lastname)) $missing_fields_count++;
                    if (empty($apps->displayname)) $missing_fields_count++;
                    if (empty($apps->shortbio)) $missing_fields_count++;
                    if (empty($apps->avatar_link)) $missing_fields_count++;
                    if (empty($apps->liveness_link)) $missing_fields_count++;
                    
                    $apps->missing_fields_count = $missing_fields_count;
                }
                
                // Sort the array based on the missing_fields_count property
                usort($everyApplicant, function($a, $b) {
                    return $a->missing_fields_count <=> $b->missing_fields_count;
                });
                
                
                foreach($everyApplicant as $apps) { 
                    $missing_fields = [];
                    if (empty($apps->firstname)) $missing_fields[] = "Firstname";
                    if (empty($apps->lastname)) $missing_fields[] = "Lastname";
                    if (empty($apps->displayname)) $missing_fields[] = "Nickname";
                    if (empty($apps->shortbio)) $missing_fields[] = "Bio";
                    if (empty($apps->avatar_link)) $missing_fields[] = "Picture";
                    if (empty($apps->liveness_link)) $missing_fields[] = "Video";
                    ?>
                    <tr>
                        <td>
                            <img src="<?= $apps->avatar_link ?>" onerror="this.onerror=null; this.src='https://martianrepublic.org/assets/citizen/generic_profile.jpg'" class="profile-avatar-img thumbnail" alt="Profile Image" style="max-height: 100px;">
                        </td>
                        <td class="valign-middle">
                            <div class="btn-group" role="group" aria-label="User Actions">
                                <a href="javascript:;" title="<?= $apps->fullname ?>"><?= $apps->fullname ?></a>
                                <p><a target="_blank" href="#"><?= $apps->address ?></a></p>

                                <a data-toggle="modal" href="#donateModal" data-donate-id="<?= $apps->userid ?>"  data-donate-for="<?= $apps->fullname ?>" data-donate-to="<?= $apps->address ?>"
                                class="btn btn-sm btn-success">Donate Marscoin</a>

                                <?php if ($isCitizen && empty($missing_fields)) { ?>
                                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Reject
                                    </button>
                                    <ul class="dropdown-menu">
                                       <li> <a class="dropdown-item" href="javascript:;" onclick="rejectApplication('<?= $apps->userid ?>', 'avatar_link')">Missing Personal Image</a></li>
                                       <li> <a class="dropdown-item" href="javascript:;" onclick="rejectApplication('<?= $apps->userid ?>', 'liveness_link')">Incomplete Video</a></li>
                                       <li><a class="dropdown-item" href="javascript:;" onclick="rejectApplication('<?= $apps->userid ?>', 'duplicate')">Duplicate Entry</a></li>
                                </ul>
                                <?php } ?>
                            </div>
                        </td>
                        <td class="valign-middle"><?= $apps->created_at ?></td>
                        <td class="file-info valign-middle">
                            <span class="label label-secondary demo-element public-status">Applicant</span>
                        </td>
                        <td class="valign-middle">
                            <?php
                            if (empty($missing_fields)) {
                                echo "All complete, awaiting notarization";
                            } else {
                                echo implode(', ', $missing_fields);
                            }
                            ?>
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

</div> 


<div id="donateModal" class="modal modal-styled fade dynamic-vote-modal" data-keyboard="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              <h3 class="modal-title" id="donate-title">Donate Marscoin</h3>
          </div>
          <div class="modal-body">
                <div class="row">
                    <div class="form-group" >
                        <label class="control-label col-md-2">For:</label>
                        <div class="col-md-10" style="margin-bottom: 15px;">
                        <input type="text" id="donate-for" class="form-control" disabled placeholder="Name">
                        </div> 
                    </div>
                    <div class="form-group" >
                        <label class="control-label col-md-2">To:</label>
                        <div class="col-md-10" style="margin-bottom: 15px;">
                        <input type="text" id="donate-to" class="form-control" disabled placeholder="Address">
                        </div> 
                    </div>
                    <div class="form-group" >
                        <label class="control-label col-md-2">Amount:</label>
                        <div class="col-md-10" style="margin-bottom: 15px;">
                        <input type="text" id="donate-amount" class="form-control" placeholder="0.1 MARS">
                        </div> 
                    </div>
                    <input type="hidden" id="donate-id" class="form-control">
                </div>
              

              <div class="modal-message" id="donate-success-message" style="display: none">
                  <span id="modal-message-error" style="color:red; font-weight: 600"> </span>
                  <span id="modal-message-success" style="font-weight: 600;"> <i class="fa fa-check-circle"></i> Successfully Donated <h3></h3></span>
              </div>
              <div class="modal-message" style="display: flex; align-items: center">
                    <a  rel="noreferrer noopener"  target="_blank"  class="transaction-hash-link" href="">
                        <h5 class="transaction-hash" id="donate-transaction-hash">
                        </h5>
                    </a>
                </div>
            </div>
          <div class="modal-footer">
          <small style="float: left; margin-top: -15px; margin-left: -15px;max-width: 270px; text-align: left;">* Marscoin donations are made on a voluntary basis (0.1 MARS paid as network fee).</small>
              <button id="confirm-donate-btn" type="submit" data-confirm data-donate class="btn btn-primary">Confirm Donation</button>
              <img src="/assets/citizen/loading.gif" alt="enter image description here" style="display: none; float: left;" id="donate-loading">
          </div> 
      </div>
  </div>
</div>


