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
                                  <span class="label label-warning row-stat-badge">Endorsements: + 0</span>
                              </td>
                            </tr>
                            <?php } ?>

                            <tr>
                              <td>
                                <img id="photo" src="/assets/citizen/generic_profile.jpg" class="profile-avatar-img thumbnail" alt="Profile Image">
                               </td>
                              <td class="valign-middle"><a href="javascript:;" title="">Richard Travis</a>
                              <p><a target="_blank" href="https://explore.marscoin.org/address/MGCxvRiRrScBWbCSX1QhtG1axMR5ku3XcK">MGCxvRiRrScBWbCSX1QhtG1axMR5ku3XcK</a></p>
                              <p>(Test)</p>
                              </td>
                              <td class="valign-middle">Feb 12, 2022. 12:28</td>
                              <td class="file-info valign-middle">
                                <span class="label label-success demo-element public-status">Citizen</span>
                              </td>
                            </tr>    

                                <tr>
                              <td>
                        
                                      <img id="photo" src="/assets/citizen/generic_profile.jpg" class="profile-avatar-img thumbnail" alt="Profile Image">
                       
                              </td>
                              <td class="valign-middle"><a href="javascript:;" title="">Paolo Mayer</a>
                              <p><a target="_blank" href="https://explore.marscoin.org/address/MGCxvRiRrScBWbCSX1QhtG1axMR5ku3XcK">MGCxvRiRrScBWbCSX1QhtG1axMR5ku3XcK</a></p>
                              <p>(Test)</p>
                              </td>
                              <td class="valign-middle">Feb 12, 2022. 12:28</td>
                              <td class="file-info valign-middle">
                                <span class="label label-success demo-element public-status">Citizen</span>
                              </td>
                            </tr>  

                            <tr>
                              <td>
                             
                                      <img id="photo" src="/assets/citizen/generic_profile.jpg" class="profile-avatar-img thumbnail" alt="Profile Image">
                        
                              </td>
                              <td class="valign-middle"><a href="javascript:;" title="">Pyotr Korshunov</a>
                              <p><a target="_blank" href="https://explore.marscoin.org/address/MGCxvRiRrScBWbCSX1QhtG1axMR5ku3XcK">MGCxvRiRrScBWbCSX1QhtG1axMR5ku3XcK</a></p>
                              <p>(Test)</p>
                              </td>
                              <td class="valign-middle">Feb 12, 2022. 12:28</td>
                              <td class="file-info valign-middle">
                                <span class="label label-success demo-element public-status">Citizen</span>
                              </td>
                            </tr>                      
                           
                        </tbody>
                       </table>

                      </div> 
        </div> 

  <div class="col-md-3">
            <h5 class="content-title"><u>Martian Society Stats</u></h5>

            <div class="list-group">  

              <a href="javascript:;" class="list-group-item">
                  <h3 class="pull-right"><i class="fa fa-eye text-primary"></i></h3>
                  <h4 class="list-group-item-heading">38,847</h4>
                  <p class="list-group-item-text">Martians</p>                  
                </a>

              <a href="javascript:;" class="list-group-item">
                <h3 class="pull-right"><i class="fa fa-facebook-square  text-primary"></i></h3>
                <h4 class="list-group-item-heading">3,482</h4>
                <p class="list-group-item-text">Citizens</p>
              </a>

              <a href="javascript:;" class="list-group-item">
                <h3 class="pull-right"><i class="fa fa-twitter-square  text-primary"></i></h3>
                <h4 class="list-group-item-heading">5</h4>
                <p class="list-group-item-text">Open Proposals</p>
              </a>
            </div> <!-- /.list-group -->

            <br>

            <h5 class="content-title"><u>Activity</u></h5>

            <div class="well">


              <ul class="icons-list text-md">

                <li>
                  <i class="icon-li fa fa-location-arrow"></i>

                  <strong>Jennifer Rowlings</strong> joined. 
                  <br>
                  <small>about 4 hours ago</small>
                </li>

                <li>
                  <i class="icon-li fa fa-location-arrow"></i>

                  <strong>Paul Anderson</strong> joined. 
                  <br>
                  <small>about 23 hours ago</small>
                </li>

                <li>
                  <i class="icon-li fa fa-location-arrow"></i>

                  <strong>John Smith</strong> joined. 
                  <br>
                  <small>2 days ago</small>
                </li>
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