<h3 class="content-title"><u>Public Logbook Entries</u></h3>

<div class="row">
    <div class="col-md-10">
        <div class="table-responsive">
            <table class="table table-striped table-bordered thumbnail-table datatable allentries">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Planetary Link</th>
                        <th>Published</th>
                        <th>TxId</th>
                        <th>Notarized</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach( $allPublications as $pub){?>
                    <tr>
                    <td class="valign-middle"><?=$pub->title?></td>
                    <td class="valign-middle"><a href="https://ipfs.marscoin.org/ipfs/<?=$pub->ipfs_hash?>"><?= substr($pub->ipfs_hash, 0, 12) . '...' ?></td>
                        <td class="valign-middle"><?=$pub->created_at?></td>
                        <td class="valign-middle"><a href="https://explore1.marscoin.org/tx/"><?=substr($pub->notarization, 0, 12) . '...' ?></a></td>
                        <td class="valign-middle"><?=$pub->notarized_at?></td>
                        <td class="file-info valign-middle">
                            <span class="label label-default demo-element public-status">
                                <?= $pub->notarized_at ? 'Notarized' : 'Pending' ?>
                            </span>
                        </td>
                    </tr>
                    <?php } ?>
                    

                </tbody>
            </table>

        </div>
    </div>
</div>