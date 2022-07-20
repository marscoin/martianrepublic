<h3 class="content-title"><u>Public Logbook Entries</u></h3>

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
                    <?php foreach( $allPublications as $pub){?>
                    <tr>
                        <td class="valign-middle"><?=$pub->ipfs_hash?></td>

                        <td class="file-info valign-middle">
                            <span class="label label-default demo-element public-status"><?=$pub->notarization?></span>
                        </td>
                    </tr>
                    <?php } ?>
                    

                </tbody>
            </table>

        </div>
    </div>
</div>