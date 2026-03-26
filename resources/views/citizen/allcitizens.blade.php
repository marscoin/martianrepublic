<?php use App\Includes\AppHelper; ?>

{{-- All Citizens: Civic Hall Registry --}}
<div class="row">
    <div class="col-md-8">
        <div style="font-family: 'Orbitron', sans-serif; font-size: 13px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: #fff; margin-bottom: 16px;">
            <i class="fa fa-shield-halved" style="color: var(--mr-green, #34d399); margin-right: 8px;"></i> Registered Citizens
            <span style="font-family: 'JetBrains Mono', monospace; font-size: 10px; font-weight: 400; color: var(--mr-text-dim); margin-left: 8px;">{{ count($everyCitizen) }} total</span>
        </div>

        {{-- Citizen Cards Grid --}}
        <div style="display: flex; flex-direction: column; gap: 8px;">
            <?php foreach($everyCitizen as $ct){ ?>
            <a href="/citizen/id/<?=$ct->public_address?>" style="display: flex; align-items: center; gap: 14px; padding: 14px 18px; background: var(--mr-surface, #12121e); border: 1px solid var(--mr-border, rgba(255,255,255,0.06)); border-radius: 8px; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.borderColor='rgba(52,211,153,0.25)';this.style.transform='translateX(4px)'" onmouseout="this.style.borderColor='var(--mr-border)';this.style.transform='none'">
                <img src="{{ $ct->avatar_link }}" onerror="this.onerror=null; this.src='https://martianrepublic.org/assets/citizen/generic_profile.jpg'"
                     style="width: 44px; height: 44px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(52,211,153,0.25); flex-shrink: 0;">
                <div style="flex: 1; min-width: 0;">
                    <div style="font-family: 'Orbitron', sans-serif; font-size: 12px; font-weight: 600; color: #fff; margin-bottom: 2px;">
                        <?=$ct->firstname?> <?=$ct->lastname?>
                    </div>
                    <div style="font-family: 'JetBrains Mono', monospace; font-size: 9px; color: var(--mr-text-faint, #5a5968);">
                        <?=substr($ct->public_address, 0, 16)?>... · <?php echo $ct->mined ? date('M Y', strtotime($ct->mined)) : 'pending'; ?>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 8px; flex-shrink: 0;">
                    <span style="font-family: 'JetBrains Mono', monospace; font-size: 8px; letter-spacing: 1px; text-transform: uppercase; padding: 3px 8px; border-radius: 3px; background: rgba(52,211,153,0.12); color: var(--mr-green, #34d399); border: 1px solid rgba(52,211,153,0.25);">
                        <i class="fa fa-check-circle" style="margin-right: 3px;"></i> Citizen
                    </span>
                    @if($ct->endorse_cnt > 0)
                    <span style="font-family: 'JetBrains Mono', monospace; font-size: 8px; padding: 3px 6px; border-radius: 3px; background: rgba(245,158,11,0.1); color: #f59e0b; border: 1px solid rgba(245,158,11,0.2);">
                        +<?=0 + $ct->endorse_cnt?>
                    </span>
                    @endif
                </div>
            </a>
            <?php } ?>
        </div>
    </div>

    {{-- Right Sidebar --}}
    <div class="col-md-4">
        <div style="margin-bottom: 20px;">
            @livewire('block-display')
        </div>
        <div style="margin-bottom: 20px;">
            @livewire('martian-republic-stats')
        </div>
        @livewire('civic-status-feed')

        {{-- Mars Flag Gallery --}}
        <div style="margin-top: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
            <img src="/assets/citizen/mars_flag_q1.png" style="width: 100%; border-radius: 6px; border: 1px solid var(--mr-border);" alt="Mars Flag">
            <img src="/assets/citizen/mars_flag2.png" style="width: 100%; border-radius: 6px; border: 1px solid var(--mr-border);" alt="Mars Flag">
            <img src="/assets/citizen/mars_flag5.jpg" style="width: 100%; border-radius: 6px; border: 1px solid var(--mr-border);" alt="Mars Flag">
            <img src="/assets/citizen/mars_flag5_q2.png" style="width: 100%; border-radius: 6px; border: 1px solid var(--mr-border);" alt="Mars Flag">
        </div>
    </div>
</div>
