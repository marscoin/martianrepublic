{{-- Ballot Window — countdown to next shuffle window --}}
<div id="ballot-window" class="ballot-window-widget">
    <div class="bw-header">
        <span class="bw-icon" id="bw-icon">&#9711;</span>
        <span class="bw-label" id="bw-label">NEXT BALLOT WINDOW</span>
    </div>
    <div class="bw-timer" id="bw-timer">--:--</div>
    <div class="bw-ring-wrap">
        <svg class="bw-ring" viewBox="0 0 80 80">
            <circle cx="40" cy="40" r="36" fill="none" stroke="rgba(255,255,255,0.04)" stroke-width="3"/>
            <circle id="bw-progress" cx="40" cy="40" r="36" fill="none" stroke="var(--mr-cyan, #00e4ff)" stroke-width="3"
                stroke-dasharray="226.2" stroke-dashoffset="0" stroke-linecap="round"
                transform="rotate(-90 40 40)" style="transition: stroke-dashoffset 1s linear, stroke 0.5s;"/>
        </svg>
    </div>
    <div class="bw-status" id="bw-status">
        <span class="bw-online"><span class="bw-online-dot"></span> <span id="bw-online-count">--</span> citizens online</span>
    </div>
    <div class="bw-hint" id="bw-hint">Window opens at :00 for 15 min</div>
</div>

<style>
.ballot-window-widget {
    background: var(--mr-surface, #12121e);
    border: 1px solid var(--mr-border, rgba(255,255,255,0.06));
    border-radius: 8px;
    padding: 16px;
    text-align: center;
    margin-top: 12px;
    position: relative;
    overflow: hidden;
}
.ballot-window-widget::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--mr-cyan, #00e4ff) 50%, transparent);
    opacity: 0.3;
    transition: opacity 0.5s;
}
.ballot-window-widget.bw-open::before {
    background: linear-gradient(90deg, transparent, var(--mr-green, #34d399) 50%, transparent);
    opacity: 0.6;
}
.bw-header {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    margin-bottom: 10px;
}
.bw-icon {
    font-size: 8px;
    color: var(--mr-cyan, #00e4ff);
    animation: bwPulse 2s infinite;
}
.bw-open .bw-icon { color: var(--mr-green, #34d399); }
@keyframes bwPulse { 0%,100% { opacity: 1; } 50% { opacity: 0.3; } }
.bw-label {
    font-family: 'Orbitron', 'JetBrains Mono', monospace;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 1.5px;
    color: var(--mr-text-dim, #8a8998);
}
.bw-open .bw-label { color: var(--mr-green, #34d399); }
.bw-timer {
    font-family: 'JetBrains Mono', monospace;
    font-size: 32px;
    font-weight: 600;
    color: var(--mr-cyan, #00e4ff);
    letter-spacing: 2px;
    line-height: 1;
    margin: 4px 0 8px;
}
.bw-open .bw-timer { color: var(--mr-green, #34d399); }
.bw-ring-wrap {
    width: 80px;
    height: 10px;
    margin: 0 auto 8px;
    overflow: hidden;
}
.bw-ring {
    width: 80px;
    height: 80px;
    transform: translateY(-35px);
}
.bw-status {
    font-size: 11px;
    color: var(--mr-text-dim, #8a8998);
    margin-bottom: 4px;
}
.bw-online {
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.bw-online-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: var(--mr-green, #34d399);
    display: inline-block;
}
.bw-hint {
    font-family: 'JetBrains Mono', monospace;
    font-size: 9px;
    color: var(--mr-text-dim, #8a8998);
    opacity: 0.6;
}
.bw-open .bw-hint {
    color: var(--mr-green, #34d399);
    opacity: 1;
    font-weight: 600;
}
</style>

<script>
(function() {
    const WINDOW_DURATION = 15; // minutes
    const CYCLE_DURATION = 60; // minutes (full cycle)
    const COOLDOWN_DURATION = CYCLE_DURATION - WINDOW_DURATION; // 45 min

    const timerEl = document.getElementById('bw-timer');
    const labelEl = document.getElementById('bw-label');
    const hintEl = document.getElementById('bw-hint');
    const iconEl = document.getElementById('bw-icon');
    const progressEl = document.getElementById('bw-progress');
    const widget = document.getElementById('ballot-window');
    const circumference = 2 * Math.PI * 36; // 226.2

    function update() {
        const now = new Date();
        const min = now.getMinutes();
        const sec = now.getSeconds();
        const totalSec = min * 60 + sec;

        const isOpen = min < WINDOW_DURATION;
        const remainingSec = isOpen
            ? (WINDOW_DURATION * 60) - totalSec
            : (CYCLE_DURATION * 60) - totalSec;

        const dispMin = Math.floor(remainingSec / 60);
        const dispSec = remainingSec % 60;
        timerEl.textContent = String(dispMin).padStart(2, '0') + ':' + String(dispSec).padStart(2, '0');

        if (isOpen) {
            widget.classList.add('bw-open');
            labelEl.textContent = 'BALLOT WINDOW OPEN';
            hintEl.textContent = 'Join a ballot shuffle now';
            const progress = 1 - (remainingSec / (WINDOW_DURATION * 60));
            progressEl.style.strokeDashoffset = (progress * circumference).toFixed(1);
            progressEl.style.stroke = 'var(--mr-green, #34d399)';
        } else {
            widget.classList.remove('bw-open');
            labelEl.textContent = 'NEXT BALLOT WINDOW';
            hintEl.textContent = 'Window opens at :00 for 15 min';
            const progress = 1 - (remainingSec / (COOLDOWN_DURATION * 60));
            progressEl.style.strokeDashoffset = (progress * circumference).toFixed(1);
            progressEl.style.stroke = 'var(--mr-cyan, #00e4ff)';
        }
    }

    // Online count — poll the ballot server for connected clients
    function updateOnlineCount() {
        // For now, show a placeholder. In production, this would query the ballot server.
        const el = document.getElementById('bw-online-count');
        if (el) el.textContent = '{{ $active->count() > 0 ? "—" : "0" }}';
    }

    update();
    updateOnlineCount();
    setInterval(update, 1000);
})();
</script>
