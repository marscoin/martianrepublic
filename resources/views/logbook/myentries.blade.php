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
                        <td class="valign-middle"><?= substr($pub->ipfs_hash, 0, 18) . '...' ?> <a target="_blank" href="https://ipfs.marscoin.org/ipfs/<?=$pub->ipfs_hash?>"> <i class="fa fa-globe"> <i class="fa fa-external-link"></a></td>
                        <td class="valign-middle"><?=$pub->created_at?></td>
                        <td class="valign-middle"><a target="_blank" href="<?=$pub->notarization?>" ><?=substr($pub->notarization, 0, 6)?>...</a></td>
                        <?php if($pub->notarization){ ?>
                        <td class="file-info valign-middle">
                            <span class="label label-default demo-element public-status">Notarized</span>
                        </td>
                        <?php }else{ ?>
                            <td class="file-info valign-middle">
                            <a data-toggle="modal" href="#notarizemeModal" rel="<?=$pub->ipfs_hash?>" class="btn btn-success demo-element notarizemeModalBtn">Notarize...</a>
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