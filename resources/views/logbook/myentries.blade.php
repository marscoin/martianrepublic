<h3 class="content-title"><u>My Logbook Entries</u></h3>

<div class="row">
    <div class="col-md-9">
        <div class="table-responsive">
            <table class="table table-striped table-bordered thumbnail-table">
                <thead>
                    <tr>
                        <th>Planetary Link</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach( $myPublications as $pub){?>
                    <tr>
                        <td class="valign-middle"><?=$pub->ipfs_hash?></td>

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