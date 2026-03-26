<div wire:poll.30s="loadCoinCount">
    {{-- Your holdings vs circulation --}}
    <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 16px;">
        <div style="position: relative; width: 52px; height: 52px; flex-shrink: 0;">
            {{-- Ring chart showing your % of circulation --}}
            @php
                $percent = ($coincount > 0 && $balance > 0) ? min(($balance / $coincount) * 100, 100) : 0;
                $dashArray = 2 * 3.14159 * 20;
                $dashOffset = $dashArray * (1 - $percent / 100);
            @endphp
            <svg width="52" height="52" viewBox="0 0 52 52" style="transform: rotate(-90deg);">
                <circle cx="26" cy="26" r="20" fill="none" stroke="var(--mr-border-bright, rgba(255,255,255,0.12))" stroke-width="4"/>
                <circle cx="26" cy="26" r="20" fill="none" stroke="var(--mr-mars, #c84125)" stroke-width="4"
                    stroke-dasharray="{{ $dashArray }}" stroke-dashoffset="{{ $dashOffset }}"
                    stroke-linecap="round" style="transition: stroke-dashoffset 1s ease;"/>
            </svg>
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-family: 'JetBrains Mono', monospace; font-size: 9px; font-weight: 700; color: #fff;">
                {{ number_format($percent, 1) }}%
            </div>
        </div>
        <div>
            <div style="font-family: 'JetBrains Mono', monospace; font-size: 10px; letter-spacing: 1px; text-transform: uppercase; color: var(--mr-text-dim); margin-bottom: 2px;">Your Share</div>
            <div style="font-family: 'Orbitron', sans-serif; font-size: 14px; font-weight: 600; color: #fff;">
                {{ number_format($balance, 2) }} <span style="font-size: 10px; color: var(--mr-text-dim);">MARS</span>
            </div>
        </div>
    </div>

    <div style="font-family: 'JetBrains Mono', monospace; font-size: 10px; color: var(--mr-text-faint); display: flex; justify-content: space-between;">
        <span>Circulation</span>
        <span style="color: var(--mr-text-dim);">{{ number_format($coincount, 0) }} MARS</span>
    </div>
</div>
