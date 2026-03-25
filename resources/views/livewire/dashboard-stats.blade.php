<div wire:poll.15s="loadData">
    @if ($balance >= 5000)
        <div style="padding: 10px 14px; background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); border-radius: 6px; margin-bottom: 16px; font-size: 12px; color: #ef4444;">
            <i class="fa fa-exclamation-triangle"></i> Balance exceeds 5,000 MARS. Store safely offline.
        </div>
    @endif

    {{-- Balance --}}
    <div style="margin-bottom: 20px;">
        <div style="font-family: 'JetBrains Mono', monospace; font-size: 10px; letter-spacing: 2px; text-transform: uppercase; color: var(--mr-text-dim, #8a8998); margin-bottom: 4px;">Balance</div>
        <div wire:init="loadData" style="font-family: 'Orbitron', sans-serif; font-size: 26px; font-weight: 700; color: #fff;">
            @if (!$isLoaded)
                <span style="color: var(--mr-text-dim);"><i class="fa fa-spinner fa-spin"></i></span>
            @else
                {{ number_format($balance, 4) }}
                <span style="font-size: 12px; font-weight: 400; color: var(--mr-text-dim, #8a8998);">MARS</span>
            @endif
        </div>
    </div>

    {{-- Send / Receive stats --}}
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1px; background: var(--mr-border, rgba(255,255,255,0.06)); border-radius: 6px; overflow: hidden; margin-bottom: 20px;">
        <div style="background: var(--mr-dark, #0c0c16); padding: 14px;">
            <div style="font-family: 'JetBrains Mono', monospace; font-size: 9px; letter-spacing: 1.5px; text-transform: uppercase; color: var(--mr-green, #34d399); margin-bottom: 4px;">
                <i class="fa fa-arrow-down" style="margin-right: 4px;"></i> Received
            </div>
            <div style="font-family: 'Orbitron', sans-serif; font-size: 14px; font-weight: 600; color: #fff;">
                @if (!$isLoaded)
                    <i class="fa fa-spinner fa-spin" style="color: var(--mr-text-dim);"></i>
                @else
                    {{ number_format($received, 4) }}
                @endif
            </div>
        </div>
        <div style="background: var(--mr-dark, #0c0c16); padding: 14px;">
            <div style="font-family: 'JetBrains Mono', monospace; font-size: 9px; letter-spacing: 1.5px; text-transform: uppercase; color: var(--mr-mars, #c84125); margin-bottom: 4px;">
                <i class="fa fa-arrow-up" style="margin-right: 4px;"></i> Sent
            </div>
            <div style="font-family: 'Orbitron', sans-serif; font-size: 14px; font-weight: 600; color: #fff;">
                @if (!$isLoaded)
                    <i class="fa fa-spinner fa-spin" style="color: var(--mr-text-dim);"></i>
                @else
                    {{ number_format($sent, 4) }}
                @endif
            </div>
        </div>
    </div>

    {{-- Republic Status --}}
    <div style="display: flex; flex-direction: column; gap: 0;">
        <a href="/forum" style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid var(--mr-border, rgba(255,255,255,0.06)); text-decoration: none;">
            <span style="font-size: 13px; color: var(--mr-text-dim, #8a8998);"><i class="fa fa-comments-o" style="width: 20px; color: var(--mr-text-faint);"></i> Forum</span>
            <span style="font-family: 'JetBrains Mono', monospace; font-size: 13px; color: #fff;">{{ $forum_count }}</span>
        </a>
        <a href="/congress/voting" style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid var(--mr-border, rgba(255,255,255,0.06)); text-decoration: none;">
            <span style="font-size: 13px; color: var(--mr-text-dim, #8a8998);"><i class="fa fa-gavel" style="width: 20px; color: var(--mr-text-faint);"></i> Proposals</span>
            <span style="font-family: 'JetBrains Mono', monospace; font-size: 13px; color: #fff;">{{ $proposal_count }}</span>
        </a>
        <a href="/citizen/all" style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; text-decoration: none;">
            <span style="font-size: 13px; color: var(--mr-text-dim, #8a8998);"><i class="fa fa-id-badge" style="width: 20px; color: var(--mr-text-faint);"></i> Status</span>
            <span style="font-family: 'JetBrains Mono', monospace; font-size: 13px; color: {{ $citizen_status === 'CT' ? 'var(--mr-green, #34d399)' : 'var(--mr-text-dim)' }};">{{ $citizen_status }}</span>
        </a>
    </div>
</div>
