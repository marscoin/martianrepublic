<div class="tab-pane fade active in" id="notary">
    <div style="font-family: 'Orbitron', sans-serif; font-size: 12px; font-weight: 600; letter-spacing: 1.5px; text-transform: uppercase; color: #fff; margin-bottom: 16px;">
        <i class="fa fa-cube" style="color: var(--mr-cyan, #00e4ff); margin-right: 6px;"></i> On-Chain Activity
        @if($activities->count() > 0)
        <span style="font-family: 'JetBrains Mono', monospace; font-size: 9px; font-weight: 400; color: var(--mr-text-dim); margin-left: 8px;">{{ $activities->count() }} records</span>
        @endif
    </div>

    <div style="display: flex; flex-direction: column; gap: 8px;">
    @forelse($activities as $activity)
        @php
            $tagConfig = [
                'ED' => ['icon' => 'fa-handshake', 'color' => 'var(--mr-green, #34d399)', 'bg' => 'rgba(52,211,153,0.1)', 'border' => 'rgba(52,211,153,0.12)', 'label' => 'Endorsement'],
                'SP' => ['icon' => 'fa-signature', 'color' => 'var(--mr-cyan, #00e4ff)', 'bg' => 'rgba(0,228,255,0.06)', 'border' => 'rgba(0,228,255,0.1)', 'label' => 'Signed Message'],
                'GP' => ['icon' => 'fa-users', 'color' => 'var(--mr-mars, #c84125)', 'bg' => 'rgba(200,65,37,0.06)', 'border' => 'rgba(200,65,37,0.1)', 'label' => 'Public Registration'],
                'CT' => ['icon' => 'fa-shield-halved', 'color' => 'var(--mr-green, #34d399)', 'bg' => 'rgba(52,211,153,0.06)', 'border' => 'rgba(52,211,153,0.1)', 'label' => 'Citizenship Oath'],
                'LB' => ['icon' => 'fa-book', 'color' => '#f59e0b', 'bg' => 'rgba(245,158,11,0.06)', 'border' => 'rgba(245,158,11,0.1)', 'label' => 'Logbook Entry'],
            ];
            $cfg = $tagConfig[$activity->tag] ?? ['icon' => 'fa-cube', 'color' => 'var(--mr-text-dim)', 'bg' => 'rgba(255,255,255,0.03)', 'border' => 'var(--mr-border)', 'label' => $activity->tag];
        @endphp

        <div style="padding: 14px 16px; background: var(--mr-dark, #0c0c16); border: 1px solid {{ $cfg['border'] }}; border-radius: 8px;">
            {{-- Header: icon + label --}}
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                <div style="width: 28px; height: 28px; border-radius: 6px; background: {{ $cfg['bg'] }}; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="fa {{ $cfg['icon'] }}" style="font-size: 11px; color: {{ $cfg['color'] }};"></i>
                </div>
                <div style="font-family: 'JetBrains Mono', monospace; font-size: 10px; letter-spacing: 1px; text-transform: uppercase; color: {{ $cfg['color'] }};">
                    {{ $cfg['label'] }}
                </div>
            </div>

            {{-- Content by type --}}
            @if($activity->tag === 'ED')
                <div style="font-size: 12px; color: var(--mr-text, #e0dfe6); line-height: 1.6;">
                    {{ $citcache->firstname }} {{ $citcache->lastname }} notarized an endorsement for
                    <a href="/citizen/id/{{ $activity->message }}" style="font-family: 'JetBrains Mono', monospace; font-size: 11px;">{{ Str::limit($activity->message, 20) }}</a>
                </div>
            @elseif($activity->tag === 'SP')
                <blockquote style="border-left: 2px solid var(--mr-cyan, #00e4ff); padding-left: 12px; margin: 0; font-size: 13px; color: var(--mr-text); line-height: 1.6;">
                    {{ str_replace('\"', "'", str_replace('\n', "\n", $activity->message)) }}
                </blockquote>
            @elseif($activity->tag === 'GP')
                <div style="font-size: 12px; color: var(--mr-text); line-height: 1.6;">
                    {{ $citcache->firstname }} {{ $citcache->lastname }} registered as a member of the General Martian Public.
                </div>
                <div style="display: flex; gap: 12px; margin-top: 8px; flex-wrap: wrap;">
                    @if($activity->embedded_link)
                    <a href="{{ $activity->embedded_link }}" target="_blank" style="font-family: 'JetBrains Mono', monospace; font-size: 9px; padding: 4px 8px; background: var(--mr-surface, #12121e); border: 1px solid var(--mr-border); border-radius: 4px; text-decoration: none;">
                        <i class="fa fa-file-lines" style="margin-right: 4px;"></i> Data Set
                    </a>
                    @endif
                    @if($citcache->avatar_link)
                    <a href="{{ $citcache->avatar_link }}" target="_blank" style="font-family: 'JetBrains Mono', monospace; font-size: 9px; padding: 4px 8px; background: var(--mr-surface, #12121e); border: 1px solid var(--mr-border); border-radius: 4px; text-decoration: none;">
                        <i class="fa fa-image" style="margin-right: 4px;"></i> Photo
                    </a>
                    @endif
                    @if($citcache->liveness_link)
                    <a href="{{ $citcache->liveness_link }}" target="_blank" style="font-family: 'JetBrains Mono', monospace; font-size: 9px; padding: 4px 8px; background: var(--mr-surface, #12121e); border: 1px solid var(--mr-border); border-radius: 4px; text-decoration: none;">
                        <i class="fa fa-video" style="margin-right: 4px;"></i> Liveness
                    </a>
                    @endif
                </div>
            @elseif($activity->tag === 'CT')
                <div style="font-style: italic; font-size: 12px; color: var(--mr-text); border-left: 2px solid var(--mr-green, #34d399); padding-left: 12px; line-height: 1.6;">
                    "I herewith declare that I, {{ $citcache->firstname }} {{ $citcache->lastname }}, am human and a member of the Martian Republic."
                </div>
            @elseif($activity->tag === 'LB')
                <div style="font-size: 12px; color: var(--mr-text);">
                    {{ $citcache->firstname }} {{ $citcache->lastname }} posted a logbook entry.
                    @if($activity->embedded_link)
                    <a href="{{ $activity->embedded_link }}" target="_blank" style="font-family: 'JetBrains Mono', monospace; font-size: 10px; margin-left: 6px;">
                        <i class="fa fa-arrow-up-right-from-square" style="margin-right: 3px;"></i> IPFS Link
                    </a>
                    @endif
                </div>
            @endif

            {{-- Footer: blockchain proof --}}
            <div style="display: flex; justify-content: space-between; margin-top: 10px; font-family: 'JetBrains Mono', monospace; font-size: 9px; color: var(--mr-text-faint, #5a5968);">
                <a target="_blank" href="https://explore.marscoin.org/tx/{{ $activity->txid }}" style="text-decoration: none; color: var(--mr-text-faint);">
                    <i class="fa fa-cube" style="margin-right: 4px;"></i> Block {{ $activity->blockid }}
                </a>
                <span>
                    <i class="fa fa-clock" style="margin-right: 4px;"></i> {{ $activity->mined ? $activity->mined->diffForHumans() : 'pending' }}
                </span>
            </div>
        </div>
    @empty
        <div style="text-align: center; padding: 40px; color: var(--mr-text-faint); font-family: 'JetBrains Mono', monospace; font-size: 11px;">
            <i class="fa fa-cube" style="font-size: 24px; display: block; margin-bottom: 12px; opacity: 0.3;"></i>
            No on-chain activity recorded yet.
        </div>
    @endforelse
    </div>

    @if($hasMore)
    <div style="text-align: center; margin-top: 16px;">
        <button wire:click="loadMore" style="
            font-family: 'JetBrains Mono', monospace; font-size: 10px; letter-spacing: 1px;
            text-transform: uppercase; padding: 10px 24px; border-radius: 6px;
            background: var(--mr-surface, #12121e); border: 1px solid var(--mr-border-bright, rgba(255,255,255,0.12));
            color: var(--mr-text-dim, #8a8998); cursor: pointer; transition: all 0.2s;
        " onmouseover="this.style.borderColor='var(--mr-cyan)';this.style.color='var(--mr-cyan)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.12)';this.style.color='var(--mr-text-dim)'">
            <i class="fa fa-chevron-down" style="margin-right: 6px;"></i> Load More
        </button>
    </div>
    @endif
</div>
