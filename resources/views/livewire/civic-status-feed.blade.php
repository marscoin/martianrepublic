<div>
    @if($activities->isEmpty())
        <div style="text-align: center; padding: 20px; color: var(--mr-text-faint, #5a5968);">
            <i class="fa fa-satellite-dish" style="font-size: 24px; margin-bottom: 8px; display: block;"></i>
            <span style="font-family: 'JetBrains Mono', monospace; font-size: 11px;">No recent transmissions</span>
        </div>
    @else
        <div style="display: flex; flex-direction: column; gap: 0;">
            @foreach($activities as $activity)
                <div style="display: flex; gap: 12px; padding: 12px 0; border-bottom: 1px solid var(--mr-border, rgba(255,255,255,0.06)); {{ $loop->last ? 'border-bottom: none;' : '' }}">
                    <div style="flex-shrink: 0; width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 13px;
                        @if($activity->tag == 'ED')
                            background: rgba(0,228,255,0.1); color: var(--mr-cyan, #00e4ff);
                        @elseif($activity->tag == 'CT')
                            background: rgba(52,211,153,0.1); color: var(--mr-green, #34d399);
                        @elseif($activity->tag == 'GP')
                            background: rgba(200,65,37,0.1); color: var(--mr-mars, #c84125);
                        @elseif($activity->tag == 'PR')
                            background: rgba(212,164,74,0.1); color: var(--mr-amber, #d4a44a);
                        @else
                            background: rgba(255,255,255,0.04); color: var(--mr-text-dim);
                        @endif
                    ">
                        <i class="fa {{ $activity->tag == 'ED' ? 'fa-handshake-o' : ($activity->tag == 'CT' ? 'fa-rocket' : ($activity->tag == 'GP' ? 'fa-user-plus' : ($activity->tag == 'PR' ? 'fa-gavel' : 'fa-link'))) }}"></i>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-size: 13px; color: var(--mr-text, #e0dfe6); line-height: 1.4;">
                            {!! $activity->displayMessage !!}
                        </div>
                        <div style="font-family: 'JetBrains Mono', monospace; font-size: 10px; color: var(--mr-text-faint, #5a5968); margin-top: 3px;">
                            @if($activity->mined)
                                {{ \Carbon\Carbon::parse($activity->mined)->diffForHumans() }}
                            @else
                                Pending confirmation
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
