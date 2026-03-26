<?php use App\Includes\AppHelper; ?>

{{-- Applicants: Processing at the Spaceport --}}
<div class="row">
    <div class="col-md-8">
        <div style="font-family: 'Orbitron', sans-serif; font-size: 13px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: #fff; margin-bottom: 16px;">
            <i class="fa fa-clipboard-list" style="color: #f59e0b; margin-right: 8px;"></i> Applicants
            <span style="font-family: 'JetBrains Mono', monospace; font-size: 10px; font-weight: 400; color: var(--mr-text-dim); margin-left: 8px;">{{ count($everyApplicant) }} pending</span>
        </div>

        <div style="display: flex; flex-direction: column; gap: 10px;">
            <?php
            foreach ($everyApplicant as &$apps) {
                $missing_fields_count = 0;
                $total_fields = 6;
                if (empty($apps->firstname)) $missing_fields_count++;
                if (empty($apps->lastname)) $missing_fields_count++;
                if (empty($apps->displayname)) $missing_fields_count++;
                if (empty($apps->shortbio)) $missing_fields_count++;
                if (empty($apps->avatar_link)) $missing_fields_count++;
                if (empty($apps->liveness_link)) $missing_fields_count++;
                $apps->missing_fields_count = $missing_fields_count;
            }

            usort($everyApplicant, function($a, $b) {
                if ($a->missing_fields_count !== $b->missing_fields_count) {
                    return $a->missing_fields_count <=> $b->missing_fields_count;
                }
                return strtotime($b->created_at ?? '0') <=> strtotime($a->created_at ?? '0');
            });

            foreach($everyApplicant as $apps) {
                $missing_fields = [];
                if (empty($apps->firstname)) $missing_fields[] = "Name";
                if (empty($apps->lastname)) $missing_fields[] = "Surname";
                if (empty($apps->displayname)) $missing_fields[] = "Nickname";
                if (empty($apps->shortbio)) $missing_fields[] = "Bio";
                if (empty($apps->avatar_link)) $missing_fields[] = "Photo";
                if (empty($apps->liveness_link)) $missing_fields[] = "Video";
                $completedFields = $total_fields - count($missing_fields);
                $completionPct = ($completedFields / $total_fields) * 100;
                $isComplete = empty($missing_fields);
            ?>
            <div style="padding: 16px 18px; background: var(--mr-surface, #12121e); border: 1px solid <?=$isComplete ? 'rgba(52,211,153,0.15)' : 'var(--mr-border, rgba(255,255,255,0.06))'?>; border-radius: 10px;">
                <div style="display: flex; align-items: center; gap: 14px;">
                    <img src="<?=$apps->avatar_link?>" onerror="this.onerror=null; this.src='https://martianrepublic.org/assets/citizen/generic_profile.jpg'"
                         style="width: 44px; height: 44px; border-radius: 50%; object-fit: cover; border: 2px solid <?=$isComplete ? 'rgba(52,211,153,0.25)' : 'rgba(245,158,11,0.25)'?>; flex-shrink: 0;">
                    <div style="flex: 1; min-width: 0;">
                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 4px;">
                            <span style="font-family: 'Orbitron', sans-serif; font-size: 12px; font-weight: 600; color: #fff;">
                                <?=$apps->fullname ?: 'Anonymous Applicant'?>
                            </span>
                            @if($isComplete)
                                <span style="font-family: 'JetBrains Mono', monospace; font-size: 8px; letter-spacing: 1px; text-transform: uppercase; padding: 3px 8px; border-radius: 3px; background: rgba(52,211,153,0.12); color: var(--mr-green, #34d399); border: 1px solid rgba(52,211,153,0.25);">
                                    <i class="fa fa-check" style="margin-right: 3px;"></i> Complete
                                </span>
                            @else
                                <span style="font-family: 'JetBrains Mono', monospace; font-size: 8px; letter-spacing: 1px; text-transform: uppercase; padding: 3px 8px; border-radius: 3px; background: rgba(245,158,11,0.1); color: #f59e0b; border: 1px solid rgba(245,158,11,0.2);">
                                    <?=$completedFields?>/<?=$total_fields?> Fields
                                </span>
                            @endif
                        </div>

                        {{-- Completion Progress --}}
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
                            <div style="flex: 1; height: 3px; background: var(--mr-dark, #0c0c16); border-radius: 2px; overflow: hidden;">
                                <div style="height: 100%; width: <?=$completionPct?>%; background: <?=$isComplete ? 'var(--mr-green, #34d399)' : '#f59e0b'?>; border-radius: 2px;"></div>
                            </div>
                        </div>

                        {{-- Missing fields --}}
                        @if(!$isComplete)
                        <div style="display: flex; gap: 4px; flex-wrap: wrap;">
                            @foreach($missing_fields as $mf)
                            <span style="font-family: 'JetBrains Mono', monospace; font-size: 8px; padding: 2px 6px; border-radius: 3px; background: rgba(239,68,68,0.08); color: #ef4444; border: 1px solid rgba(239,68,68,0.15);">
                                <i class="fa fa-xmark" style="margin-right: 2px; font-size: 7px;"></i> {{ $mf }}
                            </span>
                            @endforeach
                        </div>
                        @else
                        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                            <span style="font-family: 'JetBrains Mono', monospace; font-size: 9px; color: var(--mr-text-faint);">
                                <?=$apps->address ? substr($apps->address, 0, 16) . '...' : ''?> · <?=$apps->created_at ? date('M j, Y', strtotime($apps->created_at)) : ''?>
                            </span>
                            <span style="font-family: 'JetBrains Mono', monospace; font-size: 8px; padding: 2px 8px; border-radius: 3px; background: rgba(0,228,255,0.08); color: var(--mr-cyan, #00e4ff); border: 1px solid rgba(0,228,255,0.15); animation: civicPulse 2s infinite;">
                                <i class="fa fa-pen-to-square" style="margin-right: 3px;"></i> Awaiting on-chain notarization
                            </span>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Citizen Actions --}}
                @if($isCitizen)
                <div style="display: flex; align-items: center; gap: 8px; margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--mr-border, rgba(255,255,255,0.04));">
                    <a data-toggle="modal" href="#donateModal" data-donate-id="<?=$apps->userid?>" data-donate-for="<?=$apps->fullname?>" data-donate-to="<?=$apps->address?>"
                       style="display: inline-flex; align-items: center; gap: 5px; padding: 5px 12px; background: rgba(52,211,153,0.08); border: 1px solid rgba(52,211,153,0.2); border-radius: 5px; font-family: 'JetBrains Mono', monospace; font-size: 9px; color: var(--mr-green); text-decoration: none; transition: all 0.2s;">
                        <i class="fa fa-gift"></i> Donate
                    </a>
                    @if($isComplete)
                    <div class="btn-group">
                        <button class="btn btn-sm dropdown-toggle" type="button" data-toggle="dropdown" style="padding: 5px 12px; background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2); border-radius: 5px; font-family: 'JetBrains Mono', monospace; font-size: 9px; color: #ef4444;">
                            <i class="fa fa-flag"></i> Reject <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" style="background: var(--mr-surface); border-color: var(--mr-border-bright);">
                            <li><a class="dropdown-item" href="javascript:;" onclick="rejectApplication('<?=$apps->userid?>', 'avatar_link')" style="color: var(--mr-text-dim); font-size: 12px; padding: 6px 14px;">Missing Image</a></li>
                            <li><a class="dropdown-item" href="javascript:;" onclick="rejectApplication('<?=$apps->userid?>', 'liveness_link')" style="color: var(--mr-text-dim); font-size: 12px; padding: 6px 14px;">Incomplete Video</a></li>
                            <li><a class="dropdown-item" href="javascript:;" onclick="rejectApplication('<?=$apps->userid?>', 'duplicate')" style="color: var(--mr-text-dim); font-size: 12px; padding: 6px 14px;">Duplicate Entry</a></li>
                        </ul>
                    </div>
                    @endif
                </div>
                @endif
            </div>
            <?php } ?>

            <?php if(count($everyApplicant) === 0){ ?>
            <div style="text-align: center; padding: 40px; color: var(--mr-text-faint); font-family: 'JetBrains Mono', monospace; font-size: 11px;">
                <i class="fa fa-clipboard-list" style="font-size: 24px; display: block; margin-bottom: 12px; opacity: 0.3;"></i>
                No pending applicants.
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
            <img src="/assets/citizen/mars_flag5.jpg" style="width: 100%; border-radius: 6px; border: 1px solid var(--mr-border);" alt="Mars Flag">
            <img src="/assets/citizen/mars_flag5_q2.png" style="width: 100%; border-radius: 6px; border: 1px solid var(--mr-border);" alt="Mars Flag">
        </div>
    </div>
</div>

{{-- Donation Modal --}}
<div id="donateModal" class="modal modal-styled fade dynamic-vote-modal" data-keyboard="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="donate-title"><i class="fa fa-gift" style="margin-right: 8px; color: var(--mr-green);"></i> Donate Marscoin</h3>
            </div>
            <div class="modal-body">
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div style="padding: 12px 16px; background: var(--mr-dark, #0c0c16); border-radius: 8px;">
                        <div style="font-family: 'JetBrains Mono', monospace; font-size: 9px; letter-spacing: 1.5px; text-transform: uppercase; color: var(--mr-text-dim); margin-bottom: 4px;">For</div>
                        <input type="text" id="donate-for" class="form-control" disabled placeholder="Name" style="padding: 8px 12px !important;">
                    </div>
                    <div style="padding: 12px 16px; background: var(--mr-dark, #0c0c16); border-radius: 8px;">
                        <div style="font-family: 'JetBrains Mono', monospace; font-size: 9px; letter-spacing: 1.5px; text-transform: uppercase; color: var(--mr-text-dim); margin-bottom: 4px;">To Address</div>
                        <input type="text" id="donate-to" class="form-control" disabled placeholder="Address" style="padding: 8px 12px !important; font-size: 11px !important;">
                    </div>
                    <div style="padding: 12px 16px; background: var(--mr-dark, #0c0c16); border-radius: 8px;">
                        <div style="font-family: 'JetBrains Mono', monospace; font-size: 9px; letter-spacing: 1.5px; text-transform: uppercase; color: var(--mr-text-dim); margin-bottom: 4px;">Amount (MARS)</div>
                        <input type="text" id="donate-amount" class="form-control" placeholder="0.1 MARS" style="padding: 8px 12px !important;">
                    </div>
                    <input type="hidden" id="donate-id" class="form-control">
                </div>

                <div class="modal-message" id="donate-success-message" style="display: none; margin-top: 16px;">
                    <span id="modal-message-error" style="color: var(--mr-mars); font-family: 'JetBrains Mono', monospace; font-size: 12px;"></span>
                    <span id="modal-message-success" style="font-family: 'JetBrains Mono', monospace; font-size: 12px; color: var(--mr-green); display: none;">
                        <i class="fa fa-check-circle"></i> Successfully Donated
                    </span>
                </div>
                <div class="modal-message" style="display: flex; align-items: center; margin-top: 8px;">
                    <a rel="noreferrer noopener" target="_blank" class="transaction-hash-link" href="" style="font-family: 'JetBrains Mono', monospace; font-size: 10px;">
                        <span class="transaction-hash" id="donate-transaction-hash"></span>
                    </a>
                </div>
            </div>
            <div class="modal-footer" style="text-align: center; padding: 16px 24px;">
                <div style="font-family: 'JetBrains Mono', monospace; font-size: 8px; color: var(--mr-text-faint); margin-bottom: 10px;">
                    <i class="fa fa-info-circle" style="margin-right: 4px;"></i> Voluntary donation (0.1 MARS network fee)
                </div>
                <button id="confirm-donate-btn" type="submit" data-confirm data-donate class="btn btn-primary" style="padding: 10px 28px !important;">
                    <i class="fa fa-gift" style="margin-right: 6px;"></i> Confirm Donation
                </button>
                <img src="/assets/citizen/loading.gif" alt="Processing..." style="display: none; height: 28px; margin-left: 10px;" id="donate-loading">
            </div>
        </div>
    </div>
</div>
