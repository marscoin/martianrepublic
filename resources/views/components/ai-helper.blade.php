{{-- Olympus AI Helper — floating chat widget --}}
<div id="olympus-chat" class="olympus-chat">
    <button id="olympus-toggle" class="olympus-toggle" title="Ask Olympus">
        <img src="/images/olympus-avatar.jpg" alt="Olympus" class="olympus-avatar-btn">
    </button>
    <div id="olympus-panel" class="olympus-panel" style="display:none;">
        <div class="olympus-header">
            <div class="olympus-header-left">
                <img src="/images/olympus-avatar.jpg" alt="" class="olympus-header-avatar">
                <span class="olympus-title">OLYMPUS</span>
            </div>
            <button id="olympus-close" class="olympus-close">&times;</button>
        </div>
        <div id="olympus-messages" class="olympus-messages">
            <div class="olympus-msg olympus-msg-ai"><img src="/images/olympus-avatar.jpg" alt="" class="olympus-msg-avatar">
                Welcome to the Martian Republic! I'm Olympus, your AI guide. Ask me anything about governance, voting, citizenship, or how Mars works.
            </div>
        </div>
        <div class="olympus-input-row">
            <input type="text" id="olympus-input" class="olympus-input" placeholder="Ask anything..." autocomplete="off" maxlength="500">
            <button id="olympus-send" class="olympus-send">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
            </button>
        </div>
    </div>
</div>

<style>
.olympus-chat { position: fixed; bottom: 24px; right: 24px; z-index: 9999; font-family: 'Inter', -apple-system, sans-serif; }
.olympus-toggle {
    width: 56px; height: 56px; border-radius: 50%; border: 2px solid rgba(0,228,255,0.3);
    background: linear-gradient(135deg, #12121e 0%, #1a1a2a 100%);
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 20px rgba(0,0,0,0.5), 0 0 15px rgba(0,228,255,0.1);
    transition: all 0.3s; position: relative;
}
.olympus-toggle:hover { border-color: rgba(0,228,255,0.6); box-shadow: 0 4px 24px rgba(0,0,0,0.6), 0 0 20px rgba(0,228,255,0.2); transform: scale(1.05); }
.olympus-toggle::after {
    content: ''; position: absolute; top: -2px; right: -2px; width: 10px; height: 10px;
    background: #34d399; border-radius: 50%; border: 2px solid #12121e;
}
.olympus-panel {
    position: absolute; bottom: 64px; right: 0; width: 360px; max-height: 480px;
    background: #0c0c16; border: 1px solid rgba(255,255,255,0.08); border-radius: 12px;
    overflow: hidden; box-shadow: 0 8px 40px rgba(0,0,0,0.7);
    display: flex; flex-direction: column;
}
.olympus-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px 16px; border-bottom: 1px solid rgba(255,255,255,0.06);
    background: #12121e;
}
.olympus-header-left { display: flex; align-items: center; gap: 8px; }
.olympus-dot { width: 8px; height: 8px; border-radius: 50%; background: #34d399; animation: olympusPulse 2s infinite; }
@keyframes olympusPulse { 0%,100% { opacity: 1; } 50% { opacity: 0.4; } }
.olympus-title { font-family: 'Orbitron', sans-serif; font-size: 11px; font-weight: 700; color: #00e4ff; letter-spacing: 2px; }
.olympus-close { background: none; border: none; color: #8a8998; font-size: 20px; cursor: pointer; padding: 0 4px; line-height: 1; }
.olympus-close:hover { color: #fff; }
.olympus-messages {
    flex: 1; overflow-y: auto; padding: 16px; display: flex; flex-direction: column; gap: 12px;
    max-height: 340px; scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.1) transparent;
}
.olympus-msg {
    padding: 10px 14px; border-radius: 10px; font-size: 13px; line-height: 1.5;
    max-width: 90%; word-wrap: break-word;
}
.olympus-msg a { color: #00e4ff; text-decoration: underline; }
.olympus-msg-ai {
    background: #1a1a2a; color: #e4e4e7; align-self: flex-start;
    border-bottom-left-radius: 4px;
}
.olympus-msg-user {
    background: rgba(0,228,255,0.12); color: #e4e4e7; align-self: flex-end;
    border-bottom-right-radius: 4px;
}
.olympus-msg-typing { color: #8a8998; font-style: italic; }
.olympus-input-row {
    display: flex; gap: 8px; padding: 12px 16px;
    border-top: 1px solid rgba(255,255,255,0.06); background: #12121e;
}
.olympus-input {
    flex: 1; background: #1a1a2a; border: 1px solid rgba(255,255,255,0.08);
    border-radius: 8px; padding: 8px 12px; color: #e4e4e7; font-size: 13px;
    outline: none; font-family: inherit;
}
.olympus-input:focus { border-color: rgba(0,228,255,0.3); }
.olympus-input::placeholder { color: #5a5968; }
.olympus-send {
    background: #c84125; border: none; border-radius: 8px; width: 36px;
    display: flex; align-items: center; justify-content: center;
    color: #fff; cursor: pointer; transition: background 0.2s;
}
.olympus-send:hover { background: #e04830; }
.olympus-send:disabled { opacity: 0.4; cursor: not-allowed; }
.olympus-avatar-btn { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; }
.olympus-header-avatar { width: 28px; height: 28px; border-radius: 50%; object-fit: cover; border: 1px solid rgba(0,228,255,0.3); }
.olympus-msg-avatar { width: 24px; height: 24px; border-radius: 50%; object-fit: cover; float: left; margin-right: 8px; margin-top: 2px; border: 1px solid rgba(0,228,255,0.2); }
.olympus-dot { display: none; }
@media (max-width: 480px) {
    .olympus-panel { width: calc(100vw - 32px); right: -8px; bottom: 60px; max-height: 70vh; }
}
</style>

<script>
(function() {
    const toggle = document.getElementById('olympus-toggle');
    const panel = document.getElementById('olympus-panel');
    const close = document.getElementById('olympus-close');
    const input = document.getElementById('olympus-input');
    const send = document.getElementById('olympus-send');
    const messages = document.getElementById('olympus-messages');
    let history = [];
    let streaming = false;

    toggle.addEventListener('click', () => {
        const open = panel.style.display === 'none';
        panel.style.display = open ? 'flex' : 'none';
        toggle.style.display = open ? 'none' : 'flex';
        if (open) input.focus();
    });
    close.addEventListener('click', () => {
        panel.style.display = 'none';
        toggle.style.display = 'flex';
    });

    function addMsg(text, role) {
        const div = document.createElement('div');
        div.className = 'olympus-msg ' + (role === 'user' ? 'olympus-msg-user' : 'olympus-msg-ai');
        if (role !== 'user') { var av = document.createElement('img'); av.src = '/images/olympus-avatar.jpg'; av.className = 'olympus-msg-avatar'; div.appendChild(av); }
        div.textContent = text;
        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
        return div;
    }

    async function sendMessage() {
        const text = input.value.trim();
        if (!text || streaming) return;
        input.value = '';
        addMsg(text, 'user');
        history.push({ role: 'user', content: text });

        streaming = true;
        send.disabled = true;
        const aiDiv = addMsg('', 'assistant');
        aiDiv.classList.add('olympus-msg-typing');
        aiDiv.textContent = 'Thinking...';

        try {
            const token = document.querySelector('meta[name="csrf-token"]');
            const resp = await fetch('/api/ai/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token ? token.content : '',
                },
                body: JSON.stringify({ messages: history }),
            });

            aiDiv.textContent = '';
            aiDiv.classList.remove('olympus-msg-typing');
            let fullText = '';

            const reader = resp.body.getReader();
            const decoder = new TextDecoder();
            let buffer = '';

            while (true) {
                const { done, value } = await reader.read();
                if (done) break;
                buffer += decoder.decode(value, { stream: true });
                const lines = buffer.split('\n');
                buffer = lines.pop();
                for (const line of lines) {
                    const trimmed = line.trim();
                    if (!trimmed.startsWith('data: ')) continue;
                    const data = trimmed.slice(6);
                    if (data === '[DONE]') continue;
                    try {
                        const j = JSON.parse(data);
                        if (j.content) {
                            fullText += j.content;
                            aiDiv.innerHTML = fullText.replace(/\[([^\]]+)\]\((\/[^)]+)\)/g, '<a href="$2" target="_blank">$1</a>');
                            messages.scrollTop = messages.scrollHeight;
                        }
                    } catch(e) {}
                }
            }
            history.push({ role: 'assistant', content: fullText });
        } catch(e) {
            aiDiv.classList.remove('olympus-msg-typing');
            aiDiv.textContent = 'Connection error. Please try again.';
        }
        streaming = false;
        send.disabled = false;
    }

    send.addEventListener('click', sendMessage);
    input.addEventListener('keydown', (e) => { if (e.key === 'Enter') sendMessage(); });
})();
</script>
