{{-- My Publications --}}
<div class="chronicle-section">
    <div class="chronicle-section-title"><i class="fa-solid fa-user-pen" style="color:var(--mr-mars); margin-right:8px;"></i> My Publications</div>

    @if(count($myPublications) === 0)
    <div class="empty-state" style="background:var(--mr-dark); border-color:var(--mr-border);">
        <i class="fa-solid fa-book"></i>
        <h3>No Logbook Entries Yet</h3>
        <p>Start documenting your research and experiments. Your entries are stored permanently on IPFS and can be notarized on the blockchain.</p>
        <button class="btn-create-first" onclick="$('.chronicle-filter[data-tab=New-Entry]').click();">
            <i class="fa-solid fa-pen-nib"></i> Create First Entry
        </button>
    </div>
    @else
    <div class="table-responsive">
        <table class="table dataTable myentries" style="width:100%;">
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
                    <td style="font-weight:600; color:#fff;"><?=$pub->title?></td>
                    <td>
                        <a target="_blank" href="https://ipfs.marscoin.org/ipfs/<?=$pub->ipfs_hash?>">
                            <i class="fa-solid fa-arrow-up-right-from-square" style="margin-right:4px; font-size:9px;"></i>
                            <?= substr($pub->ipfs_hash, 0, 18) . '...' ?>
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
                                <?=substr($pub->notarization, 0, 12)?>...
                            </a>
                        <?php }else{ ?>
                            <span style="color:var(--mr-text-dim); font-family:'JetBrains Mono',monospace; font-size:11px;">&mdash;</span>
                        <?php } ?>
                    </td>
                    <?php if($pub->notarization){ ?>
                    <td>
                        <span class="badge-notarized">
                            <i class="fa-solid fa-shield-check" style="font-size:9px;"></i> Notarized
                        </span>
                    </td>
                    <?php }else{ ?>
                    <td>
                        <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                            <a data-toggle="modal" href="#notarizemeModal" class="btn-notarize notarizemeModalBtn" rel="<?=$pub->ipfs_hash?>">
                                <i class="fa-solid fa-stamp" style="font-size:10px;"></i> Notarize
                            </a>
                            <a class="btn-delete unpinModalBtn" rel="<?=$pub->ipfs_hash?>" href="#confirmDeleteModal" data-toggle="modal">
                                <i class="fa-solid fa-trash-can" style="font-size:10px;"></i> Delete
                            </a>
                        </div>
                    </td>
                    <?php } ?>
                </tr>
                <?php }  ?>
            </tbody>
        </table>
    </div>
    @endif
</div>

{{-- Notarize Modal --}}
<div id="notarizemeModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title"><i class="fa-solid fa-stamp" style="color:var(--mr-mars); margin-right:8px;"></i> Notarize Publication</h3>
            </div>
            <div class="modal-body">
                <div class="modal-body-box">
                    <h5>Document CID</h5>
                    <p class="modal-document"></p>
                </div>
                <div class="modal-body-box">
                    <h5>Transaction Fee</h5>
                    <p style="color:var(--mr-amber) !important;">0.1 MARS</p>
                </div>
                <div class="modal-message" style="display: none; margin-top:12px; padding:12px; background:var(--mr-dark); border-radius:6px;">
                    <i class="fa fa-times-circle"></i>
                    <span id="modal-message-error"> </span>
                    <span id="modal-message-success"> </span>
                    <a rel="noreferrer noopener" target="_blank" class="transaction-hash-link" href=""> </a>
                </div>
            </div>
            <h5 class="transaction-hash" style="text-align: center;"></h5>
            <div class="modal-footer">
                <button id="submit-notarization" type="submit" class="btn btn-success">
                    <i class="fa-solid fa-stamp" style="margin-right:6px;"></i> Notarize Publication
                </button>
                <img src="https://i.stack.imgur.com/FhHRx.gif" alt="" style="display: none; height:24px;" id="loading">
            </div>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="confirmDeleteModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="confirmDeleteModalLabel">
                    <i class="fa-solid fa-triangle-exclamation" style="color:var(--mr-red); margin-right:8px;"></i> Confirm Deletion
                </h5>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this publication? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="fa-solid fa-trash-can" style="margin-right:6px;"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>
