<div wire:poll.30s="fetchBlockNumber">
    <div style="display: flex; align-items: center; gap: 14px;">
        {{-- Animated block icon --}}
        <div style="position: relative; width: 44px; height: 44px; flex-shrink: 0;">
            <div style="width: 44px; height: 44px; background: linear-gradient(135deg, var(--mr-mars, #c84125), #e05535); border-radius: 8px; display: flex; align-items: center; justify-content: center; animation: blockPulse 4s ease-in-out infinite;">
                <i class="fa fa-cube" style="color: #fff; font-size: 18px;"></i>
            </div>
            <div style="position: absolute; top: -2px; right: -2px; width: 10px; height: 10px; border-radius: 50%; background: var(--mr-green, #34d399); border: 2px solid var(--mr-surface, #12121e); animation: pulse 2s infinite;"></div>
        </div>

        <div style="flex: 1;">
            <div style="font-family: 'Orbitron', sans-serif; font-size: 18px; font-weight: 700; color: #fff; letter-spacing: 1px;">
                {{ is_numeric($blockNumber) ? number_format($blockNumber) : $blockNumber }}
            </div>
            <div style="font-family: 'JetBrains Mono', monospace; font-size: 10px; color: var(--mr-text-faint, #5a5968); letter-spacing: 0.5px;">
                <span id="timeSinceLastBlock-{{ $this->getId() }}">{{ $timeSinceLastBlock }}</span> ago
            </div>
        </div>
    </div>

    <style>
        @keyframes blockPulse {
            0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(200,65,37,0.3); }
            50% { transform: scale(1.03); box-shadow: 0 0 16px 4px rgba(200,65,37,0.15); }
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }
    </style>

    <script>
    (function() {
        const elId = 'timeSinceLastBlock-{{ $this->getId() }}';
        const minedAt = @json($this->lastBlockMinedAt ? $this->lastBlockMinedAt->getTimestamp() : null);

        function updateBlockTime() {
            if (!minedAt) return;
            const el = document.getElementById(elId);
            if (!el) return;
            const diff = Math.floor(Date.now() / 1000) - minedAt;
            const m = Math.floor(diff / 60);
            const s = diff % 60;
            el.textContent = m > 0 ? m + 'm ' + s + 's' : s + 's';
        }

        updateBlockTime();
        setInterval(updateBlockTime, 1000);

        window.addEventListener('block-update', () => {
            const el = document.getElementById(elId);
            if (el) {
                el.textContent = '0s';
                el.style.color = 'var(--mr-green, #34d399)';
                setTimeout(() => { el.style.color = ''; }, 3000);
            }
        });
    })();
    </script>
</div>
