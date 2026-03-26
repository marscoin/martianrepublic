<?php use App\Includes\AppHelper; ?>

{{-- General Public: Awaiting Citizenship --}}
<div class="row">
    <div class="col-md-8">
        @php
            $citizenCount = count($everyCitizen ?? []);
            // Bootstrap: 0 citizens → 0 endorsements needed (first pioneer auto-qualifies)
            // Then: 1 per 10 citizens (rounded up), capped at 5
            $endorsementThreshold = $citizenCount === 0 ? 0 : min(5, max(1, (int) ceil($citizenCount * 0.1)));
        @endphp
        <div style="font-family: 'Orbitron', sans-serif; font-size: 13px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: #fff; margin-bottom: 6px;">
            <i class="fa fa-users" style="color: var(--mr-cyan, #00e4ff); margin-right: 8px;"></i> General Public
            <span style="font-family: 'JetBrains Mono', monospace; font-size: 10px; font-weight: 400; color: var(--mr-text-dim); margin-left: 8px;">
                {{ count($stillGP) }} awaiting · {{ count($graduated) }} graduated
            </span>
        </div>
        <div style="font-family: 'JetBrains Mono', monospace; font-size: 9px; color: var(--mr-text-faint, #5a5968); margin-bottom: 16px; line-height: 1.6; padding: 10px 14px; background: var(--mr-dark, #0c0c16); border-radius: 6px; border: 1px solid var(--mr-border, rgba(255,255,255,0.06));">
            <i class="fa fa-scale-balanced" style="color: var(--mr-cyan); margin-right: 4px;"></i>
            Citizenship requires <strong style="color: #fff;">{{ $endorsementThreshold }}</strong> endorsement{{ $endorsementThreshold > 1 ? 's' : '' }}
            <span style="color: var(--mr-text-faint);">(1 per 10 citizens, cap 5)</span>
            · <strong style="color: #fff;">{{ $citizenCount }}</strong> active citizen{{ $citizenCount !== 1 ? 's' : '' }}
        </div>

        <div style="display: flex; flex-direction: column; gap: 10px;">
            <?php
            // Sort: eligible for citizenship first, then by endorsement count desc, then recent
            usort($everyPublic, function($a, $b) use ($endorsementThreshold) {
                $aEligible = ($a->endorse_cnt ?? 0) >= $endorsementThreshold ? 1 : 0;
                $bEligible = ($b->endorse_cnt ?? 0) >= $endorsementThreshold ? 1 : 0;
                if ($aEligible !== $bEligible) return $bEligible - $aEligible; // eligible first
                $aCount = $a->endorse_cnt ?? 0;
                $bCount = $b->endorse_cnt ?? 0;
                if ($aCount !== $bCount) return $bCount - $aCount; // most endorsements first
                return strtotime($b->mined ?? '0') - strtotime($a->mined ?? '0'); // newest first
            });

            // Separate: still-GP vs graduated-to-citizen
            $stillGP = array_filter($everyPublic, fn($gp) => !$gp->citizen);
            $graduated = array_filter($everyPublic, fn($gp) => $gp->citizen);

            foreach($stillGP as $gp){

                // Get real endorsement count from Feed table (Profile.endorse_cnt may be stale)
                $feedEndorsements = \App\Models\Feed::where('tag', 'ED')
                    ->where('message', $gp->public_address)
                    ->count();
                $endorseCount = max($gp->endorse_cnt ?? 0, $feedEndorsements);
                $threshold = $endorsementThreshold;
                $isEligible = $endorseCount >= $threshold && $threshold > 0;
                $progress = $threshold > 0 ? min(100, ($endorseCount / $threshold) * 100) : 100;
            ?>
            <div style="padding: 16px 18px; background: <?=$isEligible ? 'linear-gradient(135deg, rgba(52,211,153,0.04), var(--mr-surface, #12121e))' : 'var(--mr-surface, #12121e)'?>; border: 1px solid <?=$isEligible ? 'rgba(52,211,153,0.25)' : 'var(--mr-border, rgba(255,255,255,0.06))'?>; border-radius: 10px; transition: all 0.2s;" onmouseover="this.style.borderColor='<?=$isEligible ? 'var(--mr-green)' : 'rgba(0,228,255,0.2)'?>'" onmouseout="this.style.borderColor='<?=$isEligible ? 'rgba(52,211,153,0.25)' : 'var(--mr-border)'?>'">
                <div style="display: flex; align-items: center; gap: 14px;">
                    <a href="/citizen/id/<?=$gp->public_address?>">
                        <img src="{{ $gp->avatar_link }}" onerror="this.onerror=null; this.src='https://martianrepublic.org/assets/citizen/generic_profile.jpg'"
                             style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(0,228,255,0.2); flex-shrink: 0;">
                    </a>
                    <div style="flex: 1; min-width: 0;">
                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 4px;">
                            <a href="/citizen/id/<?=$gp->public_address?>" style="font-family: 'Orbitron', sans-serif; font-size: 12px; font-weight: 600; color: #fff; text-decoration: none;">
                                <?=$gp->fullname ?: $gp->firstname . ' ' . $gp->lastname?>
                            </a>
                            @if($isEligible)
                            <span style="font-family: 'JetBrains Mono', monospace; font-size: 8px; letter-spacing: 1px; text-transform: uppercase; padding: 3px 8px; border-radius: 3px; background: rgba(52,211,153,0.15); color: var(--mr-green, #34d399); border: 1px solid rgba(52,211,153,0.3); flex-shrink: 0; animation: civicPulse 2s infinite;">
                                <i class="fa fa-star" style="margin-right: 3px;"></i> Eligible
                            </span>
                            @else
                            <span style="font-family: 'JetBrains Mono', monospace; font-size: 8px; letter-spacing: 1px; text-transform: uppercase; padding: 3px 8px; border-radius: 3px; background: rgba(0,228,255,0.1); color: var(--mr-cyan, #00e4ff); border: 1px solid rgba(0,228,255,0.2); flex-shrink: 0;">
                                General Public
                            </span>
                            @endif
                        </div>
                        <div style="font-family: 'JetBrains Mono', monospace; font-size: 9px; color: var(--mr-text-faint, #5a5968); margin-bottom: 8px;">
                            <a target="_blank" href="https://explore.marscoin.org/tx/<?=$gp->txid?>" style="text-decoration: none; color: var(--mr-text-faint);">
                                <?=substr($gp->public_address, 0, 16)?>...
                            </a>
                            · <?php echo $gp->mined ? date('M Y', strtotime($gp->mined)) : 'pending'; ?>
                        </div>

                        {{-- Endorsement Progress Bar --}}
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="flex: 1; height: 4px; background: var(--mr-dark, #0c0c16); border-radius: 2px; overflow: hidden; border: 1px solid var(--mr-border);">
                                <div style="height: 100%; width: <?=$progress?>%; background: <?=$progress >= 100 ? 'var(--mr-green, #34d399)' : 'var(--mr-cyan, #00e4ff)'?>; border-radius: 2px; transition: width 0.5s;"></div>
                            </div>
                            <span style="font-family: 'JetBrains Mono', monospace; font-size: 9px; color: <?=$progress >= 100 ? 'var(--mr-green)' : 'var(--mr-text-dim)'?>; flex-shrink: 0;">
                                <?=$endorseCount?>/<?=$threshold?> endorsements
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Endorse Button (only for citizens, not self, not already citizen) --}}
                <?php if($isCitizen && $gp->public_address != $public_address && !$gp->citizen){ ?>
                <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--mr-border, rgba(255,255,255,0.04)); text-align: right;">
                    <a data-toggle="modal" href="#endorseModal" data-endorse="{{$gp->userid}}" data-name="{{$gp->firstname}} {{$gp->lastname}}" data-address="{{$gp->public_address}}"
                       class="endorse-btn" style="display: inline-flex; align-items: center; gap: 6px; padding: 7px 16px; background: rgba(52,211,153,0.1); border: 1px solid rgba(52,211,153,0.25); border-radius: 6px; font-family: 'JetBrains Mono', monospace; font-size: 9px; letter-spacing: 1px; text-transform: uppercase; color: var(--mr-green, #34d399); text-decoration: none; cursor: pointer; transition: all 0.2s;"
                       onmouseover="this.style.background='rgba(52,211,153,0.2)';this.style.borderColor='var(--mr-green)'" onmouseout="this.style.background='rgba(52,211,153,0.1)';this.style.borderColor='rgba(52,211,153,0.25)'">
                        <i class="fa fa-handshake"></i> Endorse for Citizenship
                    </a>
                </div>
                <?php } ?>
            </div>
            <?php } ?>

            <?php if(count($stillGP) === 0){ ?>
            <div style="text-align: center; padding: 32px; color: var(--mr-text-faint); font-family: 'JetBrains Mono', monospace; font-size: 11px; background: var(--mr-surface); border-radius: 10px; border: 1px solid var(--mr-border);">
                <i class="fa fa-check-double" style="font-size: 24px; display: block; margin-bottom: 12px; color: var(--mr-green); opacity: 0.5;"></i>
                All registered members have been promoted to citizenship!
            </div>
            <?php } ?>

            {{-- Graduated Members (now citizens) --}}
            <?php if(count($graduated) > 0){ ?>
            <div style="margin-top: 24px;">
                <div style="font-family: 'JetBrains Mono', monospace; font-size: 9px; letter-spacing: 2px; text-transform: uppercase; color: var(--mr-text-faint, #5a5968); margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid var(--mr-border);">
                    <i class="fa fa-graduation-cap" style="margin-right: 4px;"></i> Graduated to Citizenship ({{ count($graduated) }})
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                    <?php foreach($graduated as $grad){ ?>
                    <a href="/citizen/id/<?=$grad->public_address?>" style="display: inline-flex; align-items: center; gap: 6px; padding: 5px 10px; background: var(--mr-dark, #0c0c16); border: 1px solid rgba(52,211,153,0.1); border-radius: 6px; text-decoration: none; font-family: 'JetBrains Mono', monospace; font-size: 9px; color: var(--mr-text-dim); transition: border-color 0.2s;" onmouseover="this.style.borderColor='rgba(52,211,153,0.3)'" onmouseout="this.style.borderColor='rgba(52,211,153,0.1)'">
                        <img src="{{ $grad->avatar_link }}" onerror="this.onerror=null; this.src='https://martianrepublic.org/assets/citizen/generic_profile.jpg'" style="width: 18px; height: 18px; border-radius: 50%; object-fit: cover;">
                        <?=$grad->firstname?> <?=$grad->lastname?>
                        <i class="fa fa-check-circle" style="color: var(--mr-green, #34d399); font-size: 8px;"></i>
                    </a>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>

    {{-- Right Sidebar --}}
    <div class="col-md-4">
        <div style="margin-bottom: 20px;">
            @livewire('martian-republic-stats')
        </div>
        @livewire('civic-status-feed')
        <div style="margin-top: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
            <img src="/assets/citizen/mars_flag_q1.png" style="width: 100%; border-radius: 6px; border: 1px solid var(--mr-border);" alt="Mars Flag">
            <img src="/assets/citizen/mars_flag2.png" style="width: 100%; border-radius: 6px; border: 1px solid var(--mr-border);" alt="Mars Flag">
        </div>
    </div>
</div>

{{-- Endorsement Modal --}}
<div id="endorseModal" class="modal fade dynamic-vote-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="endorse-title"></h3>
            </div>
            <div class="modal-body">
                <div style="padding: 16px; background: var(--mr-dark, #0c0c16); border-radius: 8px; margin-bottom: 16px;">
                    <div style="font-family: 'JetBrains Mono', monospace; font-size: 9px; letter-spacing: 1.5px; text-transform: uppercase; color: var(--mr-text-dim); margin-bottom: 6px;">Civic Address</div>
                    <p class="modal-description" id="endorse-address" style="font-family: 'JetBrains Mono', monospace; font-size: 12px; color: var(--mr-cyan); word-break: break-all; margin: 0;"></p>
                </div>
                <div style="padding: 16px; background: var(--mr-dark, #0c0c16); border-radius: 8px; margin-bottom: 16px;">
                    <div style="font-family: 'JetBrains Mono', monospace; font-size: 9px; letter-spacing: 1.5px; text-transform: uppercase; color: var(--mr-text-dim); margin-bottom: 6px;">Network Fee</div>
                    <p class="modal-cost" id="endorse-cost" style="font-family: 'Orbitron', sans-serif; font-size: 16px; font-weight: 600; color: #fff; margin: 0;"></p>
                </div>
                <div style="font-family: 'JetBrains Mono', monospace; font-size: 9px; color: var(--mr-text-faint); line-height: 1.8; padding: 0 4px;">
                    <i class="fa fa-info-circle" style="color: var(--mr-cyan); margin-right: 4px;"></i>
                    Endorsements are permanent on-chain records. A member needs 10% of existing citizens (max 5) to attain citizenship.
                </div>

                <div class="modal-message" id="confirm-success-message" style="display: none; margin-top: 16px;">
                    <span id="modal-message-error" style="color: var(--mr-mars); font-family: 'JetBrains Mono', monospace; font-size: 12px;"></span>
                    <span id="modal-message-success" style="font-family: 'JetBrains Mono', monospace; font-size: 12px; color: var(--mr-green); display: none;">
                        <i class="fa fa-check-circle"></i> Successfully Endorsed
                    </span>
                </div>
                <div class="modal-message" style="display: flex; align-items: center; margin-top: 8px;">
                    <a rel="noreferrer noopener" target="_blank" class="transaction-hash-link" href="" style="font-family: 'JetBrains Mono', monospace; font-size: 10px;">
                        <span class="transaction-hash" id="confirm-transaction-hash"></span>
                    </a>
                </div>
            </div>
            <div class="modal-footer" style="text-align: center; padding: 16px 24px;">
                <button id="confirm-endorse-btn" type="submit" data-confirm data-endorse class="btn btn-primary submit-endorse" style="padding: 10px 28px !important;">
                    <i class="fa fa-handshake" style="margin-right: 6px;"></i> Confirm Endorsement
                </button>
                <img src="/assets/citizen/loading.gif" alt="Processing..." style="display: none; height: 28px; margin-left: 10px;" id="confirm-loading">
            </div>
        </div>
    </div>
</div>
