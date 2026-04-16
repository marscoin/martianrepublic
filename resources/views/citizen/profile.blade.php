{{-- Civic Profile: The Civic Hall --}}
<div class="row">

    {{-- Left: Avatar + Navigation --}}
    <div class="col-md-3 col-sm-5">

        <div style="text-align: center; margin-bottom: 20px;">
            <img src="{{ $citcache->avatar_link }}" onerror="this.onerror=null; this.src='https://martianrepublic.org/assets/citizen/generic_profile.jpg'"
                 style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 3px solid var(--mr-border-bright, rgba(255,255,255,0.12));" alt="Profile">
        </div>

        <div style="display: flex; flex-direction: column; gap: 3px;">
            <a href="#notary" data-toggle="tab" class="list-group-item" style="display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; background: var(--mr-surface, #12121e); border: 1px solid var(--mr-border, rgba(255,255,255,0.06)); border-radius: 6px; text-decoration: none; transition: all 0.2s;">
                <span style="font-family: 'JetBrains Mono', monospace; font-size: 10px; letter-spacing: 0.5px; color: var(--mr-text-dim, #8a8998);">
                    <i class="fa fa-building-columns" style="width: 16px; color: var(--mr-cyan, #00e4ff); margin-right: 6px;"></i> Activity Feed
                </span>
                <span class="badge" style="background: var(--mr-mars, #c84125); font-family: 'JetBrains Mono', monospace; font-size: 9px; border-radius: 3px; padding: 2px 6px;">{{$recentActivityCount}}</span>
            </a>
            <a href="#research" data-toggle="tab" class="list-group-item" style="display: flex; align-items: center; padding: 10px 14px; background: var(--mr-surface, #12121e); border: 1px solid var(--mr-border, rgba(255,255,255,0.06)); border-radius: 6px; text-decoration: none; transition: all 0.2s;">
                <span style="font-family: 'JetBrains Mono', monospace; font-size: 10px; letter-spacing: 0.5px; color: var(--mr-text-dim, #8a8998);">
                    <i class="fa fa-flask" style="width: 16px; color: var(--mr-cyan); margin-right: 6px;"></i> Research
                </span>
            </a>
            <a href="#signed" data-toggle="tab" class="list-group-item" style="display: flex; align-items: center; padding: 10px 14px; background: var(--mr-surface, #12121e); border: 1px solid var(--mr-border, rgba(255,255,255,0.06)); border-radius: 6px; text-decoration: none; transition: all 0.2s;">
                <span style="font-family: 'JetBrains Mono', monospace; font-size: 10px; letter-spacing: 0.5px; color: var(--mr-text-dim, #8a8998);">
                    <i class="fa fa-signature" style="width: 16px; color: var(--mr-cyan); margin-right: 6px;"></i> Signed Messages
                </span>
            </a>
            <a href="#endorsed" data-toggle="tab" class="list-group-item" style="display: flex; align-items: center; padding: 10px 14px; background: var(--mr-surface, #12121e); border: 1px solid var(--mr-border, rgba(255,255,255,0.06)); border-radius: 6px; text-decoration: none; transition: all 0.2s;">
                <span style="font-family: 'JetBrains Mono', monospace; font-size: 10px; letter-spacing: 0.5px; color: var(--mr-text-dim, #8a8998);">
                    <i class="fa fa-handshake" style="width: 16px; color: var(--mr-green, #34d399); margin-right: 6px;"></i> Endorsed By
                </span>
            </a>
            <a href="#digid" data-toggle="tab" class="list-group-item" style="display: flex; align-items: center; padding: 10px 14px; background: var(--mr-surface, #12121e); border: 1px solid var(--mr-border, rgba(255,255,255,0.06)); border-radius: 6px; text-decoration: none; transition: all 0.2s;">
                <span style="font-family: 'JetBrains Mono', monospace; font-size: 10px; letter-spacing: 0.5px; color: var(--mr-text-dim, #8a8998);">
                    <i class="fa fa-id-card" style="width: 16px; color: var(--mr-mars, #c84125); margin-right: 6px;"></i> Digital ID
                </span>
            </a>
        </div>

    </div>

    {{-- Center: Profile Info + Tab Content --}}
    <div class="col-md-6 col-sm-7">

        {{-- Identity Header --}}
        <div style="margin-bottom: 20px;">
            <h2 style="font-family: 'Orbitron', sans-serif; font-size: 20px; font-weight: 700; letter-spacing: 1px; margin: 0 0 6px;"><?=$citcache->firstName?> <?=$citcache->lastName?></h2>
            <p style="font-family: 'JetBrains Mono', monospace; font-size: 12px; color: var(--mr-text-dim, #8a8998); margin: 0 0 14px;"><?=$citcache->shortbio?></p>

            <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 14px;">
                @if($isGP && $mePublic)
                <a target="_blank" href="https://explore.marscoin.org/tx/<?=$mePublic['txid']?>" style="display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; background: rgba(0,228,255,0.08); border: 1px solid rgba(0,228,255,0.2); border-radius: 4px; font-family: 'JetBrains Mono', monospace; font-size: 9px; letter-spacing: 1px; text-transform: uppercase; text-decoration: none; color: var(--mr-cyan, #00e4ff);">
                    <i class="fa fa-check-circle"></i> General Public
                </a>
                @endif
                @if(!is_null($meCitizen))
                <a target="_blank" href="https://explore.marscoin.org/tx/<?=$meCitizen['txid']?>" style="display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; background: rgba(52,211,153,0.08); border: 1px solid rgba(52,211,153,0.2); border-radius: 4px; font-family: 'JetBrains Mono', monospace; font-size: 9px; letter-spacing: 1px; text-transform: uppercase; text-decoration: none; color: var(--mr-green, #34d399);">
                    <i class="fa fa-shield-halved"></i> MCR Citizen
                </a>
                @endif
            </div>

            <div style="font-family: 'JetBrains Mono', monospace; font-size: 10px; color: var(--mr-text-faint, #5a5968); line-height: 2;">
                @if($isGP && $mePublic)
                <div><i class="fa fa-users" style="width: 16px; color: var(--mr-cyan); margin-right: 6px;"></i> Joined the Republic on <?=date('M jS, Y', strtotime($mePublic['mined']))?></div>
                @endif
                @if(!is_null($meCitizen))
                <div><i class="fa fa-id-card" style="width: 16px; color: var(--mr-green); margin-right: 6px;"></i> Gained Citizenship on <?=date('M jS, Y', strtotime($meCitizen['mined']))?></div>
                @endif
                <div><i class="fa fa-rocket" style="width: 16px; color: var(--mr-mars); margin-right: 6px;"></i> Awaiting Starship flight</div>
            </div>
        </div>

        <div style="height: 1px; background: var(--mr-border, rgba(255,255,255,0.06)); margin-bottom: 20px;"></div>

        {{-- Tab Content --}}
        <div id="myTabContent" class="tab-content stacked-content">

            @livewire('civic-activity-feed', ['userId' => Auth::id()])

            @livewire('research-activity')

            {{-- Signed Messages Tab --}}
            <div class="tab-pane fade" id="signed">
                <div style="font-family: 'Orbitron', sans-serif; font-size: 12px; font-weight: 600; letter-spacing: 1.5px; text-transform: uppercase; color: #fff; margin-bottom: 16px;">
                    <i class="fa fa-signature" style="color: var(--mr-cyan); margin-right: 6px;"></i> Publish to Mars Feed
                </div>
                <div style="margin-bottom: 20px;">
                    <textarea id="signedpublishpost" class="form-control" rows="3" placeholder="What would you like to publish to the blockchain?..." tabindex="1" style="margin-bottom: 10px;"></textarea>
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div>
                            <img id="signedpublishprogress" src="/assets/citizen/loading.gif" alt="Processing..." style="display: none; height: 24px;">
                            <a target="_blank" id="signedpublishhash" href="" title="" style="font-family: 'JetBrains Mono', monospace; font-size: 10px;"></a>
                        </div>
                        <a id="signedpublishbtn" class="btn btn-primary" tabindex="2" style="padding: 8px 20px !important;">
                            <i class="fa fa-paper-plane" style="margin-right: 6px;"></i> Sign & Publish
                        </a>
                    </div>
                </div>

                {{-- Signed message history --}}
                <?php foreach($feed as $f){
                    if($f->tag != 'SP') continue;
                ?>
                <div style="padding: 14px 16px; background: var(--mr-dark, #0c0c16); border: 1px solid var(--mr-border, rgba(255,255,255,0.06)); border-radius: 8px; margin-bottom: 8px;">
                    <div style="font-family: 'JetBrains Mono', monospace; font-size: 9px; letter-spacing: 1px; text-transform: uppercase; color: var(--mr-text-faint, #5a5968); margin-bottom: 8px;">
                        Signed Public Message
                    </div>
                    <blockquote style="border-left: 2px solid var(--mr-cyan, #00e4ff); padding-left: 12px; margin: 0 0 10px; font-size: 13px; color: var(--mr-text, #e0dfe6);">
                        <?=str_replace('\"', "'", str_replace('\n', "\n", $f->message))?>
                    </blockquote>
                    <div style="display: flex; justify-content: space-between; font-family: 'JetBrains Mono', monospace; font-size: 9px; color: var(--mr-text-faint);">
                        <a target="_blank" href="https://explore.marscoin.org/tx/<?=$f->txid?>" style="text-decoration: none;"><i class="fa fa-cube" style="margin-right: 4px;"></i> Block <?=$f->blockid?></a>
                        <span><i class="fa fa-clock" style="margin-right: 4px;"></i> <?=$f->mined?></span>
                    </div>
                </div>
                <?php } ?>
            </div>

            {{-- Endorsed By Tab --}}
            <div class="tab-pane fade" id="endorsed">
                <div style="font-family: 'Orbitron', sans-serif; font-size: 12px; font-weight: 600; letter-spacing: 1.5px; text-transform: uppercase; color: #fff; margin-bottom: 16px;">
                    <i class="fa fa-handshake" style="color: var(--mr-green); margin-right: 6px;"></i> Public Endorsements
                </div>

                <?php foreach($endorsed as $e){ ?>
                <div style="padding: 14px 16px; background: var(--mr-dark, #0c0c16); border: 1px solid rgba(52,211,153,0.1); border-radius: 8px; margin-bottom: 8px;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                        <div style="width: 28px; height: 28px; border-radius: 6px; background: rgba(52,211,153,0.12); display: flex; align-items: center; justify-content: center;">
                            <i class="fa fa-thumbs-up" style="font-size: 12px; color: var(--mr-green, #34d399);"></i>
                        </div>
                        <div>
                            <div style="font-family: 'JetBrains Mono', monospace; font-size: 10px; color: var(--mr-green);">Endorsed by citizen</div>
                            <a target="_blank" href="https://explore.marscoin.org/tx/<?=$e->txid?>" style="font-family: 'JetBrains Mono', monospace; font-size: 10px; text-decoration: none;"><?=$e->address?></a>
                        </div>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-family: 'JetBrains Mono', monospace; font-size: 9px; color: var(--mr-text-faint);">
                        <span><i class="fa fa-cube" style="margin-right: 4px;"></i> Block <?=$e->blockid?></span>
                        <span><i class="fa fa-clock" style="margin-right: 4px;"></i> <?=$e->mined?></span>
                    </div>
                </div>
                <?php }
                if(count($endorsed) <= 0){ ?>
                <div style="text-align: center; padding: 32px; color: var(--mr-text-faint); font-family: 'JetBrains Mono', monospace; font-size: 11px;">
                    <i class="fa fa-handshake" style="font-size: 24px; display: block; margin-bottom: 12px; opacity: 0.3;"></i>
                    No endorsements received yet. Connect with citizens to build your reputation.
                </div>
                <?php } ?>
            </div>

            {{-- Digital ID Tab --}}
            <div class="tab-pane fade" id="digid">
                <div style="font-family: 'Orbitron', sans-serif; font-size: 12px; font-weight: 600; letter-spacing: 1.5px; text-transform: uppercase; color: #fff; margin-bottom: 16px;">
                    <i class="fa fa-id-card" style="color: var(--mr-mars); margin-right: 6px;"></i> Digital Identity Documents
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px;">
                    <a target="_blank" href="/citizen/printout" style="display: flex; flex-direction: column; align-items: center; gap: 8px; padding: 20px 16px; background: var(--mr-dark, #0c0c16); border: 1px solid var(--mr-border, rgba(255,255,255,0.06)); border-radius: 8px; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.borderColor='rgba(255,255,255,0.15)'" onmouseout="this.style.borderColor='var(--mr-border)'">
                        <i class="fa fa-file-lines" style="font-size: 24px; color: var(--mr-text-dim);"></i>
                        <span style="font-family: 'JetBrains Mono', monospace; font-size: 10px; color: var(--mr-text-dim);">Basic ID</span>
                    </a>
                    <a target="_blank" href="/citizen/printout2" style="display: flex; flex-direction: column; align-items: center; gap: 8px; padding: 20px 16px; background: var(--mr-dark, #0c0c16); border: 1px solid var(--mr-border, rgba(255,255,255,0.06)); border-radius: 8px; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.borderColor='rgba(0,228,255,0.3)'" onmouseout="this.style.borderColor='var(--mr-border)'">
                        <i class="fa fa-shuttle-space" style="font-size: 24px; color: var(--mr-cyan);"></i>
                        <span style="font-family: 'JetBrains Mono', monospace; font-size: 10px; color: var(--mr-text-dim);">Space Theme</span>
                    </a>
                    <a target="_blank" href="/citizen/printout3" style="display: flex; flex-direction: column; align-items: center; gap: 8px; padding: 20px 16px; background: var(--mr-dark, #0c0c16); border: 1px solid var(--mr-border, rgba(255,255,255,0.06)); border-radius: 8px; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.borderColor='rgba(200,65,37,0.3)'" onmouseout="this.style.borderColor='var(--mr-border)'">
                        <i class="fa fa-globe" style="font-size: 24px; color: var(--mr-mars);"></i>
                        <span style="font-family: 'JetBrains Mono', monospace; font-size: 10px; color: var(--mr-text-dim);">Mars Theme</span>
                    </a>
                </div>
            </div>

            {{-- Settings Tab --}}
            <div class="tab-pane fade" id="settings">
                <div style="text-align: center; padding: 40px; color: var(--mr-text-faint); font-family: 'JetBrains Mono', monospace; font-size: 11px;">
                    <i class="fa fa-gear" style="font-size: 24px; display: block; margin-bottom: 12px; opacity: 0.3;"></i>
                    Citizen settings and preferences will be available in a future update.
                </div>
            </div>

        </div>

    </div>

    {{-- Right: Stats + Feed --}}
    <div class="col-md-3">
        @livewire('citizen-stats')
        <div style="margin-top: 20px;">
            @livewire('blockchain-activity-feed')
        </div>
    </div>

</div>
