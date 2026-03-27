{{-- All Publications --}}
<div class="chronicle-section">
    <div class="chronicle-section-title"><i class="fa-solid fa-globe" style="color:var(--mr-mars); margin-right:8px;"></i> All Publications</div>

    @if(count($allPublications) === 0)
    <div class="empty-state" style="background:var(--mr-dark); border-color:var(--mr-border);">
        <i class="fa-solid fa-globe"></i>
        <h3>No Public Entries Yet</h3>
        <p>Be the first to document research for the Martian Republic. Entries are stored permanently on IPFS and can be notarized on the Marscoin blockchain.</p>
    </div>
    @else
    <div class="table-responsive">
        <table class="table dataTable allentries" style="width:100%;">
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
                    <td style="font-weight:600; color:#fff;"><?=$pub->title?></td>
                    <td>
                        <a target="_blank" href="https://ipfs.marscoin.org/ipfs/<?=$pub->ipfs_hash?>">
                            <i class="fa-solid fa-arrow-up-right-from-square" style="margin-right:4px; font-size:9px;"></i>
                            <?= substr($pub->ipfs_hash, 0, 12) . '...' ?>
                        </a>
                    </td>
                    <td>
                        <span style="font-family:'JetBrains Mono',monospace; font-size:11px; color:var(--mr-text-dim);">
                            <?=$pub->created_at?>
                        </span>
                    </td>
                    <td>
                        <?php if($pub->notarization){ ?>
                            <a target="_blank" href="https://explore1.marscoin.org/tx/<?=$pub->notarization?>">
                                <i class="fa-solid fa-link" style="margin-right:4px; font-size:9px;"></i>
                                <?=substr($pub->notarization, 0, 12) . '...' ?>
                            </a>
                        <?php }else{ ?>
                            <span style="color:var(--mr-text-dim); font-family:'JetBrains Mono',monospace; font-size:11px;">&mdash;</span>
                        <?php } ?>
                    </td>
                    <td>
                        <span style="font-family:'JetBrains Mono',monospace; font-size:11px; color:var(--mr-text-dim);">
                            <?=$pub->notarized_at ?: '&mdash;' ?>
                        </span>
                    </td>
                    <td>
                        <?php if($pub->notarized_at){ ?>
                            <span class="badge-notarized">
                                <i class="fa-solid fa-shield-check" style="font-size:9px;"></i> Notarized
                            </span>
                        <?php }else{ ?>
                            <span class="badge-pending">
                                <i class="fa-solid fa-clock" style="font-size:9px;"></i> Pending
                            </span>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    @endif
</div>
