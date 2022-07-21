<h3 class="content-title"><u>My Logbook Entries</u></h3>

<div class="row">
    <div class="col-md-10">
        <div class="table-responsive">
            <table class="table table-striped table-bordered thumbnail-table">
                <thead>
                    <tr>
                        <th>Planetary Link</th>
                        <th>Publicized</th>
                        <th>Notarized</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach( $myPublications as $pub){?>
                    <tr>
                        <td class="valign-middle"><?=$pub->ipfs_hash?> <a target="_blank" href="https://ipfs.marscoin.org/ipfs/<?=$pub->ipfs_hash?>"> <i class="fa fa-globe"> <i class="fa fa-external-link"></a></td>
                        <td class="valign-middle"><?=$pub->created_at?></td>
                        <td class="valign-middle"><?=$pub->notarization?></td>
                        <?php if($pub->notarization){ ?>
                        <td class="file-info valign-middle">
                            <span class="label label-default demo-element public-status">Notarized</span>
                        </td>
                        <?php }else{ ?>
                            <td class="file-info valign-middle">
                                <a href="#" id="pub_<?=$pub->ipfs_hash?>" class="btn btn-success notarizeme">Notarize...</a>
                            </td>
                        <?php } ?>
                    </tr>
                    <?php }  ?>
                    

                </tbody>
            </table>

        </div>
    </div>
</div>