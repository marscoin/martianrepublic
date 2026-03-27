{{-- New Entry Form --}}
<div class="chronicle-section">
    <div class="chronicle-section-title"><i class="fa-solid fa-pen-nib" style="color:var(--mr-mars); margin-right:8px;"></i> Create a Log Entry</div>

    <form class="form account-form" method="POST" action="/logbook/createentry">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div style="margin-bottom: 24px;">
                    <label class="chronicle-label">Title <span class="required">*</span></label>
                    <input type="text" id="title" name="title" class="chronicle-input" placeholder="Enter a descriptive title for your research entry" data-required="true">
                </div>

                <div>
                    <label class="chronicle-label">Entry <span class="required">*</span></label>
                    <textarea type="description" data-required="true" data-minlength="5" name="description" id="description"
                        cols="10" rows="180" style="min-height: 686px;" class="form-control"></textarea>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="chronicle-section" style="margin-top: 0; background: var(--mr-dark); border-color: var(--mr-border);">
                    <div class="chronicle-section-title" style="font-size:10px;"><i class="fa-solid fa-paperclip" style="color:var(--mr-cyan); margin-right:6px;"></i> Attachments</div>

                    {{-- Modern drag-and-drop upload zone --}}
                    <div id="drop-zone" style="border: 2px dashed var(--mr-border-bright, rgba(255,255,255,0.12)); border-radius: 10px; padding: 28px 16px; text-align: center; cursor: pointer; transition: all 0.3s ease; background: var(--mr-surface, #12121e);"
                         ondragover="event.preventDefault(); this.style.borderColor='var(--mr-cyan)'; this.style.background='rgba(0,228,255,0.04)';"
                         ondragleave="this.style.borderColor='var(--mr-border-bright)'; this.style.background='var(--mr-surface)';"
                         ondrop="handleDrop(event)"
                         onclick="document.getElementById('file-input-hidden').click()">
                        <i class="fa-solid fa-cloud-arrow-up" style="font-size: 28px; color: var(--mr-text-dim); margin-bottom: 10px; display: block;"></i>
                        <div style="font-family: 'JetBrains Mono', monospace; font-size: 11px; color: var(--mr-text-dim); margin-bottom: 4px;">
                            Drag &amp; drop files here
                        </div>
                        <div style="font-size: 12px; color: var(--mr-text-faint);">
                            or <span style="color: var(--mr-cyan); cursor: pointer;">browse</span>
                        </div>
                    </div>
                    <input type="file" id="file-input-hidden" name="filenames[]" multiple accept="image/*,.pdf,.txt,.md,.csv,.json" style="display:none;" onchange="handleFiles(this.files)">

                    {{-- File list --}}
                    <div id="file-list" style="margin-top: 10px;"></div>

                    <div style="margin-top:12px; font-size:11px; color:var(--mr-text-dim); line-height:1.6;">
                        <i class="fa-solid fa-info-circle" style="color:var(--mr-cyan); margin-right:4px;"></i>
                        Up to 3 files, max 3MB each. Images, documents, and data files accepted.
                    </div>
                </div>

                <div class="chronicle-section" style="background: var(--mr-dark); border-color: var(--mr-border); border-left: 3px solid var(--mr-cyan); border-radius: 0 8px 8px 0;">
                    <div style="font-family:'Orbitron',sans-serif; font-size:10px; letter-spacing:2px; text-transform:uppercase; color:var(--mr-cyan); margin-bottom:10px;">How It Works</div>
                    <div style="font-size:13px; line-height:1.8; color:var(--mr-text-dim);">
                        Your entry and attachments are published to the <strong style="color:var(--mr-text);">Interplanetary File System</strong> (IPFS). You can then optionally <strong style="color:var(--mr-text);">notarize</strong> the entry on the Marscoin blockchain as permanent intellectual property.
                    </div>
                </div>
            </div>
        </div>

        <div style="margin-top:8px;">
            <span id="form-message" style="color:var(--mr-red); font-weight: 600; font-family:'JetBrains Mono',monospace; font-size:12px;"> </span>
        </div>

        <div style="margin-top:24px; padding-top:24px; border-top:1px solid var(--mr-border);">
            <a data-toggle="modal" href="#" id="saveLogLocalBtn" class="btn-publish">
                <i class="fa-solid fa-satellite-dish"></i> Publish to IPFS
            </a>
        </div>

        <input type="hidden" id="ipfs_path" value="">

        {{-- Log Modal --}}
        <div id="logModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h3 class="modal-title">Publish Entry</h3>
                    </div>
                    <div class="modal-body">
                        <div class="modal-message" style="display: none">
                            <i class="fa fa-times-circle"></i>
                            <span id="modal-message-error"> </span>
                            <span id="modal-message-success"> </span>
                        </div>
                    </div>
                    <h5 class="transaction-hash" style="text-align: center;"></h5>
                    <div class="modal-footer">
                        <button id="submit-log" type="submit" class="btn btn-primary">Submit log entry</button>
                        <img src="https://i.stack.imgur.com/FhHRx.gif" alt="" style="display: none" id="loading">
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// Modern file upload handler (replaces FilePond)
var uploadedFiles = [];

function handleDrop(e) {
    e.preventDefault();
    e.target.closest('#drop-zone').style.borderColor = 'var(--mr-border-bright)';
    e.target.closest('#drop-zone').style.background = 'var(--mr-surface)';
    handleFiles(e.dataTransfer.files);
}

function handleFiles(files) {
    var maxFiles = 3;
    var maxSize = 3 * 1024 * 1024; // 3MB

    for (var i = 0; i < files.length; i++) {
        if (uploadedFiles.length >= maxFiles) {
            alert('Maximum ' + maxFiles + ' files allowed');
            break;
        }
        if (files[i].size > maxSize) {
            alert(files[i].name + ' exceeds 3MB limit');
            continue;
        }
        uploadedFiles.push(files[i]);
    }
    renderFileList();
}

function removeFile(index) {
    uploadedFiles.splice(index, 1);
    renderFileList();
}

function renderFileList() {
    var html = '';
    uploadedFiles.forEach(function(file, i) {
        var size = (file.size / 1024).toFixed(1) + ' KB';
        if (file.size > 1048576) size = (file.size / 1048576).toFixed(1) + ' MB';
        var icon = file.type.startsWith('image/') ? 'fa-image' : 'fa-file';

        html += '<div style="display:flex; align-items:center; gap:10px; padding:8px 12px; background:var(--mr-surface); border:1px solid var(--mr-border); border-radius:6px; margin-bottom:6px;">';
        html += '<i class="fa-solid ' + icon + '" style="color:var(--mr-cyan); font-size:14px;"></i>';
        html += '<div style="flex:1; min-width:0;">';
        html += '<div style="font-family:\'JetBrains Mono\',monospace; font-size:11px; color:var(--mr-text); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">' + file.name + '</div>';
        html += '<div style="font-size:10px; color:var(--mr-text-dim);">' + size + '</div>';
        html += '</div>';
        html += '<button type="button" onclick="removeFile(' + i + ')" style="background:none; border:none; color:var(--mr-text-dim); cursor:pointer; padding:4px;" title="Remove"><i class="fa-solid fa-xmark"></i></button>';
        html += '</div>';
    });
    document.getElementById('file-list').innerHTML = html;
}

// Make uploadedFiles accessible globally for the publish flow
window.getUploadedFiles = function() { return uploadedFiles; };
</script>
