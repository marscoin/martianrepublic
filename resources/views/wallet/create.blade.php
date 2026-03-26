<!DOCTYPE html>
<html lang="en">
<head>
    <title>Create Wallet - Martian Republic</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('partials.public-head')
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" />
    <style>
    /* ============================================ */
    /* THE FORGE: Wallet Creation Wizard            */
    /* ============================================ */
    .forge-wizard {
        max-width: 560px;
        margin: 0 auto;
        padding: 40px 20px 60px;
    }
    .forge-step {
        display: none;
        animation: forgeIn 0.4s ease-out;
    }
    .forge-step.active { display: block; }
    @keyframes forgeIn {
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Progress dots */
    .forge-progress {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-bottom: 40px;
    }
    .forge-dot {
        width: 10px; height: 10px;
        border-radius: 50%;
        background: var(--mr-border-bright, rgba(255,255,255,0.12));
        transition: all 0.3s;
    }
    .forge-dot.done { background: var(--mr-green, #34d399); }
    .forge-dot.current { background: var(--mr-cyan, #00e4ff); box-shadow: 0 0 8px rgba(0,228,255,0.4); }
    .forge-connector {
        width: 24px; height: 2px;
        background: var(--mr-border-bright, rgba(255,255,255,0.12));
        transition: background 0.3s;
    }
    .forge-connector.done { background: var(--mr-green, #34d399); }

    /* Card container */
    .forge-card {
        background: var(--mr-surface, #12121e);
        border: 1px solid var(--mr-border, rgba(255,255,255,0.06));
        border-radius: 16px;
        padding: 36px 32px;
        text-align: center;
    }
    .forge-icon {
        width: 64px; height: 64px;
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 24px;
        font-size: 28px;
    }
    .forge-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 18px; font-weight: 700;
        letter-spacing: 2px; text-transform: uppercase;
        color: #fff; margin-bottom: 8px;
    }
    .forge-sub {
        font-family: 'JetBrains Mono', monospace;
        font-size: 12px; color: var(--mr-text-dim, #8a8998);
        margin-bottom: 28px;
    }
    .forge-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        width: 100%; padding: 14px 24px;
        border-radius: 10px; border: none;
        font-family: 'JetBrains Mono', monospace;
        font-size: 12px; font-weight: 500;
        letter-spacing: 1.5px; text-transform: uppercase;
        cursor: pointer; transition: all 0.2s;
    }
    .forge-btn.primary {
        background: var(--mr-mars, #c84125);
        color: #fff;
    }
    .forge-btn.primary:hover { background: #d94e30; box-shadow: 0 4px 20px rgba(200,65,37,0.3); }
    .forge-btn.secondary {
        background: transparent;
        border: 1px solid var(--mr-border-bright, rgba(255,255,255,0.15));
        color: var(--mr-text-dim);
        margin-top: 10px;
    }
    .forge-btn:disabled { opacity: 0.5; cursor: not-allowed; }
    .forge-label {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px; letter-spacing: 1.5px;
        text-transform: uppercase; color: var(--mr-text-dim);
        text-align: left; margin-bottom: 6px;
    }
    .forge-input {
        width: 100%; padding: 12px 16px;
        background: var(--mr-void, #06060c);
        border: 1px solid var(--mr-border-bright, rgba(255,255,255,0.1));
        border-radius: 8px; color: #fff;
        font-family: 'JetBrains Mono', monospace; font-size: 14px;
        outline: none; transition: border-color 0.2s;
    }
    .forge-input:focus {
        border-color: var(--mr-cyan, #00e4ff);
        box-shadow: 0 0 0 3px rgba(0,228,255,0.08);
    }

    /* Entropy box */
    .forge-entropy-box {
        position: relative;
        width: 100%; height: 200px;
        background: var(--mr-void, #06060c);
        border: 1px solid var(--mr-border-bright, rgba(255,255,255,0.1));
        border-radius: 12px;
        cursor: crosshair;
        overflow: hidden;
        margin-bottom: 20px;
    }
    .forge-entropy-box::before {
        content: '';
        position: absolute; inset: 0;
        background:
            repeating-linear-gradient(0deg, transparent, transparent 19px, rgba(255,255,255,0.015) 19px, rgba(255,255,255,0.015) 20px),
            repeating-linear-gradient(90deg, transparent, transparent 19px, rgba(255,255,255,0.015) 19px, rgba(255,255,255,0.015) 20px);
        pointer-events: none;
    }
    .forge-entropy-box .placeholder-text {
        position: absolute; top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px; letter-spacing: 3px;
        color: var(--mr-text-faint, #5a5968);
        opacity: 0.5; pointer-events: none;
        transition: opacity 0.5s;
    }
    .forge-entropy-box:hover .placeholder-text { opacity: 0; }
    .forge-entropy-box .entropy-dot {
        position: absolute;
        width: 4px; height: 4px;
        border-radius: 50%;
        background: var(--mr-cyan, #00e4ff);
        pointer-events: none;
        box-shadow: 0 0 6px var(--mr-cyan, #00e4ff), 0 0 12px rgba(0,228,255,0.3);
        animation: dotGlow 2s ease-out forwards;
    }
    @keyframes dotGlow {
        0% { opacity: 1; transform: scale(1.5); }
        30% { opacity: 0.8; transform: scale(1); }
        100% { opacity: 0.15; transform: scale(0.6); }
    }
    .forge-pct {
        position: absolute; top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        font-family: 'Orbitron', sans-serif;
        font-size: 42px; font-weight: 800;
        color: var(--mr-mars, #c84125);
        pointer-events: none; z-index: 5;
    }
    .forge-progress-bar {
        height: 4px; background: var(--mr-void);
        border-radius: 2px; overflow: hidden;
        border: 1px solid var(--mr-border, rgba(255,255,255,0.06));
    }
    .forge-progress-bar .fill {
        height: 100%; width: 0%;
        background: var(--mr-mars, #c84125);
        border-radius: 2px; transition: width 0.2s;
    }

    /* Mnemonic grid */
    .forge-mnemonic-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        margin: 20px 0;
    }
    .forge-word {
        display: flex; align-items: center; gap: 8px;
        padding: 10px 12px;
        background: var(--mr-void, #06060c);
        border: 1px solid var(--mr-border-bright, rgba(255,255,255,0.1));
        border-radius: 6px; text-align: left;
    }
    .forge-word-num {
        font-family: 'JetBrains Mono', monospace;
        font-size: 9px; color: var(--mr-text-faint);
        min-width: 16px;
    }
    .forge-word-text {
        font-family: 'JetBrains Mono', monospace;
        font-size: 13px; font-weight: 500; color: #fff;
    }

    /* Verify inputs */
    .forge-verify-grid {
        display: flex; gap: 12px; justify-content: center;
        flex-wrap: wrap; margin: 20px 0;
    }
    .forge-verify-item { text-align: center; }
    .forge-verify-label {
        font-family: 'JetBrains Mono', monospace;
        font-size: 9px; letter-spacing: 1px;
        text-transform: uppercase; color: var(--mr-text-faint);
        margin-bottom: 4px;
    }
    .forge-verify-input {
        width: 120px; padding: 10px 12px;
        background: var(--mr-void); border: 2px solid var(--mr-border-bright);
        border-radius: 8px; color: #fff; text-align: center;
        font-family: 'JetBrains Mono', monospace; font-size: 14px;
        outline: none; transition: border-color 0.2s;
    }
    .forge-verify-input:focus { border-color: var(--mr-cyan); }
    .forge-verify-input.error { border-color: var(--mr-mars, #c84125); }
    .forge-error {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px; color: var(--mr-mars, #c84125);
        margin-top: 12px; display: none;
    }

    /* Success animation */
    @keyframes forgeSuccess {
        0% { transform: scale(0.5); opacity: 0; }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); opacity: 1; }
    }
    </style>
    <script src="/assets/wallet/js/dist/bundle.js"></script>
</head>
<body class="mr-theme">
    @include('partials.public-nav')

    <main class="mr-auth-page">
        <div class="forge-wizard">

            {{-- Progress Indicator --}}
            <div class="forge-progress" id="forge-progress">
                <div class="forge-dot current" data-step="1"></div>
                <div class="forge-connector"></div>
                <div class="forge-dot" data-step="2"></div>
                <div class="forge-connector"></div>
                <div class="forge-dot" data-step="3"></div>
                <div class="forge-connector"></div>
                <div class="forge-dot" data-step="4"></div>
                <div class="forge-connector"></div>
                <div class="forge-dot" data-step="5"></div>
            </div>

            {{-- ========== STEP 1: ENTROPY ========== --}}
            <div class="forge-step active" id="step-1">
                <div class="forge-card">
                    <div class="forge-icon" style="background: rgba(0,228,255,0.1); border: 1px solid rgba(0,228,255,0.2); color: var(--mr-cyan);">
                        <i class="fa fa-wand-magic-sparkles"></i>
                    </div>
                    <div class="forge-title">Generate Entropy</div>
                    <div class="forge-sub">Move your mouse inside the box to create cryptographic randomness</div>

                    <div class="forge-entropy-box" id="entropy-box">
                        <span class="placeholder-text">MOVE YOUR MOUSE HERE</span>
                        <div class="forge-pct" id="entropy-pct">0%</div>
                    </div>
                    <div class="forge-progress-bar">
                        <div class="fill" id="entropy-fill"></div>
                    </div>

                    <div style="margin-top: 20px;">
                        <button class="forge-btn primary" id="entropy-next" disabled>
                            <i class="fa fa-arrow-right"></i> Continue
                        </button>
                    </div>
                </div>
            </div>

            {{-- ========== STEP 2: SEED PHRASE ========== --}}
            <div class="forge-step" id="step-2">
                <div class="forge-card">
                    <div class="forge-icon" style="background: rgba(200,65,37,0.1); border: 1px solid rgba(200,65,37,0.2); color: var(--mr-mars);">
                        <i class="fa fa-key"></i>
                    </div>
                    <div class="forge-title">Your Seed Phrase</div>
                    <div class="forge-sub">Write these 12 words down and store them safely. They are the ONLY way to recover your wallet.</div>

                    <div class="forge-mnemonic-grid" id="mnemonic-grid"></div>

                    <div style="text-align: right; margin-bottom: 16px;">
                        <button type="button" onclick="navigator.clipboard.writeText(window._forgeMnemonic); this.innerHTML='<i class=\'fa fa-check\' style=\'color:var(--mr-green)\'></i> Copied!'; setTimeout(() => this.innerHTML='<i class=\'fa fa-copy\'></i> Copy', 2000);"
                            style="background: var(--mr-void); border: 1px solid var(--mr-border-bright); border-radius: 6px; padding: 6px 12px; cursor: pointer; font-family: 'JetBrains Mono', monospace; font-size: 10px; color: var(--mr-text-dim); transition: all 0.2s;">
                            <i class="fa fa-copy"></i> Copy
                        </button>
                    </div>

                    <div style="background: rgba(200,65,37,0.06); border: 1px solid rgba(200,65,37,0.15); border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; text-align: left;">
                        <div style="font-family: 'JetBrains Mono', monospace; font-size: 10px; color: var(--mr-mars, #c84125);">
                            <i class="fa fa-exclamation-triangle" style="margin-right: 6px;"></i>
                            Never share your seed phrase. Anyone with these words has full access to your funds.
                        </div>
                    </div>

                    <button class="forge-btn primary" id="seed-next">
                        <i class="fa fa-arrow-right"></i> I've Saved My Seed Phrase
                    </button>
                </div>
            </div>

            {{-- ========== STEP 3: VERIFY ========== --}}
            <div class="forge-step" id="step-3">
                <div class="forge-card">
                    <div class="forge-icon" style="background: rgba(52,211,153,0.1); border: 1px solid rgba(52,211,153,0.2); color: var(--mr-green);">
                        <i class="fa fa-shield-halved"></i>
                    </div>
                    <div class="forge-title">Verify Seed Phrase</div>
                    <div class="forge-sub">Enter the requested words to confirm you saved your seed phrase</div>

                    <div class="forge-verify-grid" id="verify-grid"></div>

                    <div class="forge-error" id="verify-error">
                        <i class="fa fa-xmark-circle"></i> Incorrect words. Please check your seed phrase and try again.
                    </div>

                    <div style="margin-top: 20px;">
                        <button class="forge-btn primary" id="verify-next">
                            <i class="fa fa-check"></i> Confirm
                        </button>
                        <button class="forge-btn secondary" id="verify-back">
                            <i class="fa fa-arrow-left"></i> Go Back
                        </button>
                    </div>
                </div>
            </div>

            {{-- ========== STEP 4: PASSWORD ========== --}}
            <div class="forge-step" id="step-4">
                <div class="forge-card">
                    <div class="forge-icon" style="background: rgba(0,228,255,0.1); border: 1px solid rgba(0,228,255,0.2); color: var(--mr-cyan);">
                        <i class="fa fa-lock"></i>
                    </div>
                    <div class="forge-title">Backup Password</div>
                    <div class="forge-sub">Encrypt your wallet backup with a password for extra security</div>

                    <div style="text-align: left; margin-bottom: 16px;">
                        <div class="forge-label">Password</div>
                        <input type="password" class="forge-input" id="forge-password" placeholder="Enter a strong password" autocomplete="new-password">
                    </div>
                    <div style="text-align: left; margin-bottom: 24px;">
                        <div class="forge-label">Confirm Password</div>
                        <input type="password" class="forge-input" id="forge-repassword" placeholder="Re-type your password" autocomplete="new-password">
                    </div>

                    <button class="forge-btn primary" id="password-next">
                        <i class="fa fa-arrow-right"></i> Encrypt & Continue
                    </button>
                    <button class="forge-btn secondary" id="password-skip">
                        Skip — I'll rely on my seed phrase only
                    </button>
                </div>
            </div>

            {{-- ========== STEP 5: WALLET READY ========== --}}
            <div class="forge-step" id="step-5">
                <div class="forge-card">
                    <div class="forge-icon" style="background: rgba(52,211,153,0.1); border: 1px solid rgba(52,211,153,0.2); color: var(--mr-green); animation: forgeSuccess 0.6s ease-out;">
                        <i class="fa fa-rocket"></i>
                    </div>
                    <div class="forge-title">Wallet Ready</div>
                    <div class="forge-sub">Your Marscoin wallet has been created</div>

                    <div style="text-align: left; margin-bottom: 16px;">
                        <div class="forge-label">Your Public Address</div>
                        <div style="display: flex; align-items: center; gap: 8px; padding: 12px 16px; background: var(--mr-void); border: 1px solid var(--mr-border-bright); border-radius: 8px;">
                            <span id="forge-address" style="font-family: 'JetBrains Mono', monospace; font-size: 12px; color: var(--mr-cyan); word-break: break-all; flex: 1;"></span>
                            <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('forge-address').textContent)" style="background: var(--mr-surface-raised); border: 1px solid var(--mr-border-bright); border-radius: 4px; padding: 4px 8px; cursor: pointer; color: var(--mr-text-dim); font-size: 12px;">
                                <i class="fa fa-copy"></i>
                            </button>
                        </div>
                    </div>

                    <div style="text-align: left; margin-bottom: 24px;">
                        <div class="forge-label">Wallet Name</div>
                        <input type="text" class="forge-input" id="forge-wallet-name" value="MARS" maxlength="500" placeholder="Give your wallet a name">
                    </div>

                    <form method="POST" action="/wallet/createwallet" id="forge-form">
                        @csrf
                        <input type="hidden" name="public_addr" id="forge-form-addr">
                        <input type="hidden" name="password" id="forge-form-password">
                        <input type="hidden" name="re-password" id="forge-form-repassword">
                        <input type="hidden" name="wallet_name" id="forge-form-name">
                        <button type="submit" class="forge-btn primary" id="forge-submit">
                            <i class="fa fa-rocket"></i> Open Wallet
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </main>

    @include('partials.public-footer')

    <script src="/assets/wallet/js/dist/bundle.js"></script>
    <script src="/assets/wallet/js/dist/my_bundle.js"></script>
    <script src="/assets/wallet/js/libs/jquery-1.10.2.min.js"></script>
    <script>
    (function() {
        // Marscoin network config
        const Marscoin = {
            mainnet: {
                messagePrefix: "\x19Marscoin Signed Message:\n",
                bech32: "M", bip44: 2,
                bip32: { public: 0x043587cf, private: 0x04358394 },
                pubKeyHash: 0x32, scriptHash: 0x32, wif: 0x80,
            },
        };

        const SALT = "{{ $SALT }}";
        let iv = "{{ json_encode($iv) }}".replace("]", "").replace("[", "").split(",");
        iv = new Uint8Array(iv);

        const PBKDF2_ROUNDS = 100000;
        const hashPassword = (p) => my_bundle.pbkdf2.pbkdf2Sync(p, SALT, PBKDF2_ROUNDS, 16, 'sha512').toString('hex');

        const genSeed = (mnemonic) => {
            const seed = my_bundle.bip39.mnemonicToSeedSync(mnemonic.trim());
            const root = my_bundle.bitcoin.bip32.fromSeed(seed, Marscoin.mainnet);
            const child = root.derivePath("m/44'/2'/0'").neutered();
            const tpub = child.toBase58();
            const hdNode = my_bundle.bip32.fromBase58(tpub, Marscoin.mainnet);
            const node = hdNode.derive(0);
            const addy = my_bundle.bitcoin.payments.p2pkh({ pubkey: node.derive(0).publicKey, network: Marscoin.mainnet }).address;
            return { address: addy, mnemonic: mnemonic };
        };

        // State
        let currentStep = 1;
        let entropy = [];
        let mnemonic = '';
        let walletAddress = '';
        let encryptedSeed = '';
        let hashedRePassword = '';
        let verifyIndices = [];
        let lastCapture = 0;

        function goToStep(step) {
            currentStep = step;
            document.querySelectorAll('.forge-step').forEach(s => s.classList.remove('active'));
            document.getElementById('step-' + step).classList.add('active');
            // Update progress dots
            document.querySelectorAll('.forge-dot').forEach(d => {
                const s = parseInt(d.dataset.step);
                d.className = 'forge-dot' + (s < step ? ' done' : s === step ? ' current' : '');
            });
            document.querySelectorAll('.forge-connector').forEach((c, i) => {
                c.className = 'forge-connector' + (i < step - 1 ? ' done' : '');
            });
        }

        // ===== STEP 1: ENTROPY =====
        const box = document.getElementById('entropy-box');
        box.addEventListener('mousemove', function(e) {
            const now = Date.now();
            if (now - lastCapture < 100) return;
            lastCapture = now;

            const rect = box.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const w = rect.width, h = rect.height;
            const cellDim = w / Math.sqrt(2048);
            const cell = Math.round((x / cellDim) + ((w / cellDim) * (y / cellDim)));
            entropy.push(cell);

            // Create dot
            const dot = document.createElement('div');
            dot.className = 'entropy-dot';
            dot.style.left = x + 'px';
            dot.style.top = y + 'px';
            box.appendChild(dot);

            const pct = Math.min(100, entropy.length);
            document.getElementById('entropy-pct').textContent = pct + '%';
            document.getElementById('entropy-fill').style.width = pct + '%';

            if (pct >= 100) {
                document.getElementById('entropy-next').disabled = false;
                document.getElementById('entropy-pct').style.color = 'var(--mr-green)';

                // Generate mnemonic from entropy
                function shuffle(arr) {
                    for (let i = arr.length - 1; i > 0; i--) {
                        const j = Math.floor(Math.random() * (i + 1));
                        [arr[i], arr[j]] = [arr[j], arr[i]];
                    }
                    return arr;
                }
                shuffle(entropy);
                const finalEntropy = entropy.slice(0, 16);
                mnemonic = my_bundle.bip39.entropyToMnemonic(finalEntropy);
                window._forgeMnemonic = mnemonic;

                const wallet = genSeed(mnemonic);
                walletAddress = wallet.address;
            }
        });

        document.getElementById('entropy-next').addEventListener('click', () => {
            if (entropy.length < 100) return;

            // Populate mnemonic grid
            const words = mnemonic.split(/\s+/);
            const grid = document.getElementById('mnemonic-grid');
            grid.innerHTML = words.map((w, i) => `
                <div class="forge-word">
                    <span class="forge-word-num">${i + 1}.</span>
                    <span class="forge-word-text">${w}</span>
                </div>
            `).join('');

            goToStep(2);
        });

        // ===== STEP 2: SEED PHRASE =====
        document.getElementById('seed-next').addEventListener('click', () => {
            // Pick 3 random words for verification
            const words = mnemonic.split(/\s+/);
            verifyIndices = [];
            while (verifyIndices.length < 3) {
                const idx = Math.floor(Math.random() * 12);
                if (!verifyIndices.includes(idx)) verifyIndices.push(idx);
            }
            verifyIndices.sort((a, b) => a - b);

            const grid = document.getElementById('verify-grid');
            grid.innerHTML = verifyIndices.map(idx => `
                <div class="forge-verify-item">
                    <div class="forge-verify-label">Word #${idx + 1}</div>
                    <input type="text" class="forge-verify-input" data-idx="${idx}" placeholder="Word ${idx + 1}" autocomplete="off">
                </div>
            `).join('');

            goToStep(3);
            grid.querySelector('input').focus();
        });

        // ===== STEP 3: VERIFY =====
        document.getElementById('verify-next').addEventListener('click', () => {
            const words = mnemonic.split(/\s+/);
            let allCorrect = true;

            document.querySelectorAll('.forge-verify-input').forEach(input => {
                const idx = parseInt(input.dataset.idx);
                const entered = input.value.trim().toLowerCase();
                if (entered !== words[idx].toLowerCase()) {
                    allCorrect = false;
                    input.classList.add('error');
                } else {
                    input.classList.remove('error');
                }
            });

            if (allCorrect) {
                document.getElementById('verify-error').style.display = 'none';
                goToStep(4);
            } else {
                document.getElementById('verify-error').style.display = 'block';
            }
        });

        document.getElementById('verify-back').addEventListener('click', () => goToStep(2));

        // ===== STEP 4: PASSWORD =====
        document.getElementById('password-next').addEventListener('click', () => {
            const pw = document.getElementById('forge-password').value.trim();
            const rpw = document.getElementById('forge-repassword').value.trim();

            if (!pw || !rpw) { alert('Please enter and confirm your password.'); return; }
            if (pw !== rpw) { alert('Passwords do not match.'); return; }

            // Show encrypting state
            const btn = document.getElementById('password-next');
            btn.innerHTML = '<i class="fa fa-lock fa-spin"></i> Encrypting...';
            btn.disabled = true;

            setTimeout(() => {
                const hashed = hashPassword(pw);
                encryptedSeed = my_bundle.encrypt(mnemonic, hashed, iv);
                hashedRePassword = hashPassword(rpw);
                showFinalStep();
            }, 80);
        });

        document.getElementById('password-skip').addEventListener('click', () => {
            if (!confirm('Warning: Without a password, you can only recover your wallet with the seed phrase. Continue?')) return;
            encryptedSeed = '';
            hashedRePassword = '';
            showFinalStep();
        });

        function showFinalStep() {
            document.getElementById('forge-address').textContent = walletAddress;
            document.getElementById('forge-form-addr').value = walletAddress;
            document.getElementById('forge-form-password').value = encryptedSeed;
            document.getElementById('forge-form-repassword').value = hashedRePassword;
            goToStep(5);
        }

        // ===== STEP 5: SUBMIT =====
        document.getElementById('forge-form').addEventListener('submit', function() {
            document.getElementById('forge-form-name').value = document.getElementById('forge-wallet-name').value || 'MARS';
            WalletKey.set(mnemonic);
        });

    })();
    </script>
</body>
</html>
