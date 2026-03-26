<div>
    @if($hasCivicWallet)
    <a href="/citizen/id/{{ $address }}" style="text-decoration: none; display: block;">
    <div class="citizen-id" style="
        position: relative;
        background: linear-gradient(145deg, #0e0e1a 0%, #161628 50%, #1a1020 100%);
        border: 1px solid rgba(200,65,37,0.2);
        border-radius: 10px;
        padding: 18px 20px 14px;
        overflow: hidden;
        transition: border-color 0.3s, box-shadow 0.3s;
    ">
        {{-- Background pattern --}}
        <div style="position: absolute; inset: 0; opacity: 0.03; pointer-events: none;
            background: repeating-linear-gradient(90deg, transparent, transparent 39px, rgba(255,255,255,0.5) 39px, rgba(255,255,255,0.5) 40px),
                        repeating-linear-gradient(0deg, transparent, transparent 39px, rgba(255,255,255,0.5) 39px, rgba(255,255,255,0.5) 40px);">
        </div>

        {{-- Mars Republic seal watermark --}}
        <div style="position: absolute; top: 50%; right: -10px; transform: translateY(-50%); opacity: 0.04; pointer-events: none;">
            <svg width="120" height="120" viewBox="0 0 120 120">
                <circle cx="60" cy="60" r="55" fill="none" stroke="#c84125" stroke-width="3"/>
                <circle cx="60" cy="60" r="45" fill="none" stroke="#c84125" stroke-width="1"/>
                <text x="60" y="65" text-anchor="middle" font-family="Orbitron" font-size="16" fill="#c84125" font-weight="700">MR</text>
            </svg>
        </div>

        {{-- Top row: header + status --}}
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px; position: relative;">
            <div>
                <div style="font-family: 'JetBrains Mono', monospace; font-size: 7px; letter-spacing: 3px; text-transform: uppercase; color: var(--mr-mars, #c84125); margin-bottom: 2px;">
                    Martian Congressional Republic
                </div>
                <div style="font-family: 'Orbitron', sans-serif; font-size: 9px; font-weight: 600; letter-spacing: 1.5px; text-transform: uppercase; color: var(--mr-text-dim, #8a8998);">
                    Civic Identity
                </div>
            </div>
            <div style="
                font-family: 'JetBrains Mono', monospace;
                font-size: 8px;
                letter-spacing: 1px;
                text-transform: uppercase;
                padding: 3px 8px;
                border-radius: 3px;
                @if($status === 'CT' || $statusLabel === 'Citizen')
                    background: rgba(52,211,153,0.12);
                    color: var(--mr-green, #34d399);
                    border: 1px solid rgba(52,211,153,0.25);
                @elseif($status === 'GP' || $statusLabel === 'General Public')
                    background: rgba(0,228,255,0.1);
                    color: var(--mr-cyan, #00e4ff);
                    border: 1px solid rgba(0,228,255,0.2);
                @else
                    background: rgba(200,65,37,0.1);
                    color: var(--mr-mars, #c84125);
                    border: 1px solid rgba(200,65,37,0.2);
                @endif
            ">
                @if($status === 'CT')
                    <i class="fa fa-check-circle" style="margin-right: 3px;"></i>
                @endif
                {{ $statusLabel }}
            </div>
        </div>

        {{-- Name --}}
        <div style="font-family: 'Orbitron', sans-serif; font-size: 16px; font-weight: 700; color: #fff; letter-spacing: 0.5px; margin-bottom: 8px; position: relative;">
            {{ $fullname }}
        </div>

        {{-- Address --}}
        <div style="font-family: 'JetBrains Mono', monospace; font-size: 10px; color: var(--mr-cyan, #00e4ff); letter-spacing: 0.3px; margin-bottom: 10px; opacity: 0.75; position: relative;">
            {{ $address }}
        </div>

        {{-- Bottom row: metadata --}}
        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 10px; border-top: 1px solid rgba(255,255,255,0.04); position: relative;">
            <div style="display: flex; gap: 16px;">
                @if($citizenSince)
                <div>
                    <div style="font-family: 'JetBrains Mono', monospace; font-size: 7px; letter-spacing: 1.5px; text-transform: uppercase; color: var(--mr-text-faint, #5a5968);">Since</div>
                    <div style="font-family: 'JetBrains Mono', monospace; font-size: 10px; color: var(--mr-text-dim, #8a8998);">{{ $citizenSince }}</div>
                </div>
                @endif
                <div>
                    <div style="font-family: 'JetBrains Mono', monospace; font-size: 7px; letter-spacing: 1.5px; text-transform: uppercase; color: var(--mr-text-faint, #5a5968);">Status</div>
                    <div style="font-family: 'JetBrains Mono', monospace; font-size: 10px; color: var(--mr-text-dim, #8a8998);">{{ strtoupper($status) }}</div>
                </div>
            </div>
            <div style="font-family: 'JetBrains Mono', monospace; font-size: 8px; color: var(--mr-text-faint, #5a5968);">
                <i class="fa fa-arrow-right" style="font-size: 7px;"></i> View Profile
            </div>
        </div>
    </div>
    </a>
    <style>
        .citizen-id:hover {
            border-color: rgba(200,65,37,0.4) !important;
            box-shadow: 0 4px 24px rgba(200,65,37,0.08) !important;
        }
    </style>
    @else
    <a href="/wallet/dashboard/hd" style="text-decoration: none; display: block;">
        <div style="
            text-align: center;
            padding: 24px 16px;
            border: 1px dashed var(--mr-border-bright, rgba(255,255,255,0.1));
            border-radius: 10px;
            background: var(--mr-surface, #12121e);
            transition: border-color 0.3s;
        ">
            <i class="fa fa-id-card" style="font-size: 24px; color: var(--mr-text-faint, #5a5968); margin-bottom: 8px;"></i>
            <div style="font-family: 'JetBrains Mono', monospace; font-size: 10px; color: var(--mr-text-dim, #8a8998);">
                Create a wallet to get your Civic ID
            </div>
        </div>
    </a>
    @endif
</div>
