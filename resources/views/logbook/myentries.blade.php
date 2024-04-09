<h3 class="content-title"><u>My Logbook Entries</u></h3>

<div class="row">
    <div class="col-md-10">
        <div class="table-responsive">
            <table class="table table-striped table-bordered thumbnail-table datatable myentries">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Planetary Link</th>
                        <th>Published</th>
                        <th>Notarized</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach( $myPublications as $pub){?>
                    <tr>
                        <td class="valign-middle"><?=$pub->title?></td>
                        <td class="valign-middle"><a target="_blank" href="https://ipfs.marscoin.org/ipfs/<?=$pub->ipfs_hash?>"> <?= substr($pub->ipfs_hash, 0, 18) . '...' ?></a></td>
                        <td class="valign-middle"><?=$pub->created_at?></td>
                        <td class="valign-middle"><a target="_blank" href="https://explore1.marscoin.org/tx/<?=$pub->notarization?>" ><?=substr($pub->notarization, 0, 12)?>...</a></td>
                        <?php if($pub->notarization){ ?>
                        <td class="file-info valign-middle">
                            <span class="label label-default demo-element public-status">Notarized</span>
                        </td>
                        <?php }else{ ?>
                            <td class="file-info valign-middle">


                            <ul class="nav nav-pills">
                            <li class="dropdown">
                                <a href="javascript:;" id="myTabDrop2" class="dropdown-toggle" data-toggle="dropdown">
                                Actions <b class="caret"></b>
                                </a>

                                <ul class="dropdown-menu" role="menu" aria-labelledby="myTabDrop1">
                                <li><a data-toggle="modal" href="#notarizemeModal"  class="notarizemeModalBtn" rel="<?=$pub->ipfs_hash?>" tabindex="-1" data-toggle="tab">Notarize </a></li>
                                <li><a class="unpinModalBtn" rel="<?=$pub->ipfs_hash?>" href="#confirmDeleteModal" data-toggle="modal">Delete</a></li>
                                </ul>
                            </li>
                            </td>
                        <?php } ?>
                    </tr>
                    <?php }  ?>
                    

                </tbody>
            </table>

        </div>
    </div>
</div>


<div id="notarizemeModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title">Notarize</h3>
            </div>
            <div class="modal-body">
                <div class="modal-body-box">
                    <h5> Document: </h5>
                    <p class="modal-document" style="font-size: 18px; font-weight: 800;"></p>
                    <h5> Fee: </h5>
                    <p class="modal-fee" style="font-size: 18px; font-weight: 800;"></p> MARS
                </div>
                <div class="modal-message" style="display: none">
                    <i class="fa fa-times-circle"></i>
                    <span id="modal-message-error" style="color:red; font-weight: 600"> </span>
                    <span id="modal-message-success" style="font-weight: 600"> </span>
                    <a  rel="noreferrer noopener"  target="_blank"  class="transaction-hash-link" href=""> </a>
                </div>
            </div>
            <h5 class="transaction-hash" style="text-align: center;"></h5>
            <div class="modal-footer">
                <button id="submit-notarization" type="submit" class="btn btn-success">Notarize Publication</button>
                <img src="https://i.stack.imgur.com/FhHRx.gif" alt="" style="display: none" id="loading">
            </div>
        </div> 
    </div>
</div>

<div id="confirmDeleteModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this publication? This action cannot be undone.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
      </div>
    </div>
  </div>
</div>