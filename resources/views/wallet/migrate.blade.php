{{-- v2026.03.31.1 - Civic Wallet Migration Wizard --}}
<html lang="en" class="no-js">
<head>
    <title>Migrate Civic Wallet — Martian Republic</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald:400,300,700">
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@700&family=Orbitron:wght@500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/wallet/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-admin.css?v=2">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-flat.css">
    <link rel="shortcut icon" href="/assets/favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
    body { background: var(--mr-void, #06060c) !important; color: var(--mr-text, #e0dfe6); }
    .migrate-page { min-height: 100vh; display: flex; flex-direction: column; }
    .migrate-page .content { flex: 1; }
    .migrate-steps { display: flex; gap: 4px; margin-bottom: 32px; }
    .migrate-step-dot { flex: 1; height: 4px; border-radius: 2px; background: var(--mr-border, rgba(255,255,255,0.06)); transition: background 0.4s; }
    .migrate-step-dot.active { background: var(--mr-mars, #c84125); }
    .migrate-step-dot.done { background: var(--mr-green, #34d399); }
    .migrate-card { background: var(--mr-surface, #12121e); border: 1px solid var(--mr-border, rgba(255,255,255,0.06)); border-radius: 12px; padding: 32px; max-width: 600px; margin: 0 auto 24px; animation: migrateFadeIn 0.4s ease-out both; }
    .migrate-title { font-family: 'Orbitron', sans-serif; font-size: 14px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: #fff; margin-bottom: 20px; }
    .migrate-label { font-family: 'JetBrains Mono', monospace; font-size: 10px; letter-spacing: 1.5px; text-transform: uppercase; color: var(--mr-text-dim, #8a8998); margin-bottom: 6px; }
    .migrate-addr { font-family: 'JetBrains Mono', monospace; font-size: 12px; color: var(--mr-cyan, #00e4ff); word-break: break-all; padding: 12px 16px; border-radius: 8px; background: var(--mr-dark, #0c0c16); border: 1px solid var(--mr-border, rgba(255,255,255,0.06)); margin-bottom: 16px; }
    .migrate-balance { font-family: 'Orbitron', sans-serif; font-size: 20px; font-weight: 700; color: #fff; }
    .migrate-balance .unit { font-size: 11px; font-weight: 400; color: var(--mr-text-dim, #8a8998); margin-left: 4px; }
    .migrate-warning { background: rgba(200,65,37,0.08); border: 1px solid rgba(200,65,37,0.2); border-radius: 8px; padding: 16px; margin: 16px 0; font-family: 'JetBrains Mono', monospace; font-size: 11px; line-height: 1.7; color: var(--mr-mars, #c84125); }
    .migrate-warning i { margin-right: 6px; }
    .migrate-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 14px 24px; border-radius: 8px; border: none; font-family: 'JetBrains Mono', monospace; font-size: 11px; font-weight: 500; letter-spacing: 1.5px; text-transform: uppercase; cursor: pointer; transition: all 0.2s; }
    .migrate-btn-primary { background: var(--mr-mars, #c84125); color: #fff; }
    .migrate-btn-primary:hover { background: #d94e30; box-shadow: 0 4px 20px rgba(200,65,37,0.35); }
    .migrate-btn-primary:disabled { opacity: 0.4; cursor: not-allowed; box-shadow: none; }
    .migrate-btn-secondary { background: transparent; color: var(--mr-text-dim, #8a8998); border: 1px solid var(--mr-border, rgba(255,255,255,0.06)); }
    .migrate-btn-secondary:hover { border-color: rgba(255,255,255,0.15); color: #fff; }
    .migrate-btn-success { background: var(--mr-green, #34d399); color: #06060c; }
    .migrate-btn-success:hover { background: #3ee8a8; }
    .seed-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; margin: 16px 0; }
    .seed-word { background: var(--mr-dark, #0c0c16); border: 1px solid var(--mr-border, rgba(255,255,255,0.06)); border-radius: 6px; padding: 10px 8px; text-align: center; font-family: 'JetBrains Mono', monospace; font-size: 12px; color: #fff; }
    .seed-word .num { font-size: 9px; color: var(--mr-text-faint, #5a5968); margin-right: 4px; }
    .migrate-input { width: 100%; padding: 12px 16px; border-radius: 8px; background: var(--mr-dark, #0c0c16) !important; border: 1px solid rgba(255,255,255,0.1); color: #fff; font-family: 'JetBrains Mono', monospace; font-size: 13px; transition: border-color 0.2s; }
    .migrate-input:focus { border-color: var(--mr-cyan, #00e4ff); outline: none; box-shadow: 0 0 0 3px rgba(0,228,255,0.08); }
    .migrate-input::placeholder { color: var(--mr-text-faint, #5a5968); }
    .migrate-check { display: flex; align-items: center; gap: 10px; margin: 16px 0; font-family: 'JetBrains Mono', monospace; font-size: 11px; color: var(--mr-text-dim, #8a8998); cursor: pointer; }
    .migrate-check input[type="checkbox"] { width: 18px; height: 18px; accent-color: var(--mr-mars, #c84125); cursor: pointer; }
    .migrate-arrow { text-align: center; padding: 12px 0; font-size: 20px; color: var(--mr-mars, #c84125); }
    .migrate-error { color: var(--mr-mars, #c84125); font-family: 'JetBrains Mono', monospace; font-size: 11px; margin-top: 8px; display: none; }
    .migrate-spinner { display: none; text-align: center; padding: 20px; }
    .migrate-spinner i { font-size: 24px; color: var(--mr-mars, #c84125); }
    .migrate-spinner-text { font-family: 'JetBrains Mono', monospace; font-size: 10px; letter-spacing: 1.5px; text-transform: uppercase; color: var(--mr-text-dim, #8a8998); margin-top: 8px; }
    .migrate-success-icon { width: 80px; height: 80px; border-radius: 50%; margin: 0 auto 20px; background: rgba(52,211,153,0.12); border: 2px solid var(--mr-green, #34d399); display: flex; align-items: center; justify-content: center; font-size: 32px; color: var(--mr-green, #34d399); animation: migrateSuccessPop 0.5s ease-out both; }
    .migrate-history { margin-top: 24px; }
    .migrate-history-item { background: var(--mr-dark, #0c0c16); border: 1px solid var(--mr-border, rgba(255,255,255,0.06)); border-radius: 8px; padding: 12px 16px; margin-bottom: 8px; font-family: 'JetBrains Mono', monospace; font-size: 11px; }
    .footer { border-top: 1px solid var(--mr-border, rgba(255,255,255,0.06)); padding: 20px 0; background: var(--mr-void, #06060c); }
    @keyframes migrateFadeIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes migrateSuccessPop { 0% { transform: scale(0.5); opacity: 0; } 60% { transform: scale(1.1); } 100% { transform: scale(1); opacity: 1; } }
    @media (max-width: 767px) { .migrate-card { padding: 20px; margin: 0 8px 24px; } .seed-grid { grid-template-columns: repeat(2, 1fr); } }
    </style>
    @livewireStyles
</head>
<body class="migrate-page">
    <div id="wrapper">
        <header class="navbar navbar-inverse" role="banner">
            <div class="container">
                <div class="navbar-header">@include('wallet.header')</div>
                <nav class="collapse navbar-collapse" role="navigation">
                    @include('wallet.navbarleft')
                    @include('wallet.navbarright')
                </nav>
            </div>
        </header>
        @include('wallet.mainnav', ['active' => 'wallet'])

        <div class="content">
            <div class="container">
                {{-- Step Progress Bar --}}
                <div style="max-width: 600px; margin: 24px auto 0;">
                    <div class="migrate-steps">
                        <div class="migrate-step-dot" id="dot-1"></div>
                        <div class="migrate-step-dot" id="dot-2"></div>
                        <div class="migrate-step-dot" id="dot-3"></div>
                        <div class="migrate-step-dot" id="dot-4"></div>
                    </div>
                </div>

                {{-- STEP 1: Confirm Intent --}}
                <div class="migrate-card" id="step-1">
                    <div class="migrate-title"><i class="fa fa-shield-halved" style="color: var(--mr-mars); margin-right: 8px;"></i> Migrate Civic Wallet</div>
                    <div class="migrate-label">Current Civic Address</div>
                    <div class="migrate-addr" id="current-civic-addr">{{ $civic_wallet->public_addr ?? '—' }}</div>
                    <div class="migrate-label">Current Balance</div>
                    <div style="margin-bottom: 16px;">
                        <span class="migrate-balance" id="current-balance"><i class="fa fa-spinner fa-spin" style="font-size: 14px; color: var(--mr-text-dim);"></i></span>
                        <span class="migrate-balance"><span class="unit">MARS</span></span>
                    </div>
                    <div class="migrate-warning">
                        <i class="fa fa-exclamation-triangle"></i>
                        This will replace your civic identity address. Your old address and funds will be preserved as an HD wallet. This action requires an on-chain signature and can only be done once every 30 days.
                    </div>
                    <button class="migrate-btn migrate-btn-primary" onclick="goToStep(2)"><i class="fa fa-check"></i> I understand, proceed</button>
                    @if($migrations && count($migrations) > 0)
                    <div class="migrate-history">
                        <div class="migrate-label" style="margin-top: 16px;">Migration History</div>
                        @foreach($migrations as $m)
                        <div class="migrate-history-item">
                            <div style="color: var(--mr-text-faint);">{{ $m->created_at->format('Y-m-d H:i') }}</div>
                            <div><span style="color: var(--mr-text-dim);">From:</span> <span style="color: var(--mr-cyan);">{{ \Illuminate\Support\Str::limit($m->old_address, 16) }}</span></div>
                            <div><span style="color: var(--mr-text-dim);">To:</span> <span style="color: var(--mr-green, #34d399);">{{ \Illuminate\Support\Str::limit($m->new_address, 16) }}</span></div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- STEP 2: Generate New Wallet --}}
                <div class="migrate-card" id="step-2" style="display: none;">
                    <div class="migrate-title"><i class="fa fa-key" style="color: var(--mr-cyan); margin-right: 8px;"></i> Generate New Civic Wallet</div>
                    <div class="migrate-label">New Recovery Seed Phrase</div>
                    <div class="seed-grid" id="seed-grid"></div>
                    <div class="migrate-label" style="margin-top: 16px;">New Civic Address</div>
                    <div class="migrate-addr" id="new-civic-addr" style="color: var(--mr-green, #34d399);">Generating...</div>
                    <label class="migrate-check">
                        <input type="checkbox" id="seed-backup-check" onchange="document.getElementById('btn-step2').disabled = !this.checked">
                        I have securely backed up this seed phrase
                    </label>
                    <div style="display: flex; gap: 8px;">
                        <button class="migrate-btn migrate-btn-secondary" style="width: auto; flex: 0 0 auto; padding: 14px 20px;" onclick="goToStep(1)"><i class="fa fa-arrow-left"></i></button>
                        <button class="migrate-btn migrate-btn-primary" id="btn-step2" disabled onclick="goToStep(3)">Continue <i class="fa fa-arrow-right"></i></button>
                    </div>
                </div>

                {{-- STEP 3: Sign Migration --}}
                <div class="migrate-card" id="step-3" style="display: none;">
                    <div class="migrate-title"><i class="fa fa-file-signature" style="color: var(--mr-mars); margin-right: 8px;"></i> Sign Migration Transaction</div>
                    <div class="migrate-label">Old Civic Address</div>
                    <div class="migrate-addr" id="sign-old-addr">—</div>
                    <div class="migrate-arrow"><i class="fa fa-arrow-down"></i></div>
                    <div class="migrate-label">New Civic Address</div>
                    <div class="migrate-addr" id="sign-new-addr" style="color: var(--mr-green, #34d399);">—</div>
                    <div class="migrate-label" style="margin-top: 16px;">Wallet Password</div>
                    <input type="password" class="migrate-input" id="migrate-password" placeholder="Enter your wallet password" autocomplete="off">
                    <div class="migrate-error" id="password-error"></div>
                    <div class="migrate-spinner" id="sign-spinner">
                        <i class="fa fa-circle-notch fa-spin"></i>
                        <div class="migrate-spinner-text" id="sign-spinner-text">Decrypting wallet...</div>
                    </div>
                    <div style="display: flex; gap: 8px; margin-top: 16px;" id="sign-buttons">
                        <button class="migrate-btn migrate-btn-secondary" style="width: auto; flex: 0 0 auto; padding: 14px 20px;" onclick="goToStep(2)"><i class="fa fa-arrow-left"></i></button>
                        <button class="migrate-btn migrate-btn-primary" id="btn-sign" onclick="signMigration()"><i class="fa fa-pen-nib"></i> Sign & Broadcast Migration</button>
                    </div>
                </div>

                {{-- STEP 4: Complete --}}
                <div class="migrate-card" id="step-4" style="display: none;">
                    <div class="migrate-success-icon"><i class="fa fa-check"></i></div>
                    <div style="text-align: center; margin-bottom: 24px;">
                        <div class="migrate-title" style="color: var(--mr-green, #34d399); margin-bottom: 8px;">Civic Wallet Migrated</div>
                        <div style="font-family: 'JetBrains Mono', monospace; font-size: 11px; color: var(--mr-text-dim);">Your civic identity has been transferred to the new address.</div>
                    </div>
                    <div class="migrate-label">Old Address <span style="color: var(--mr-text-faint);">(Preserved as HD wallet)</span></div>
                    <div class="migrate-addr" id="done-old-addr">—</div>
                    <div class="migrate-label">New Address <span style="color: var(--mr-green, #34d399);">(New civic identity)</span></div>
                    <div class="migrate-addr" id="done-new-addr" style="color: var(--mr-green, #34d399);">—</div>
                    <div class="migrate-label">Migration Transaction</div>
                    <div class="migrate-addr" id="done-txid"><a id="done-txid-link" href="#" target="_blank" style="color: var(--mr-cyan, #00e4ff); text-decoration: none; word-break: break-all;"></a></div>
                    <a href="/wallet/dashboard" class="migrate-btn migrate-btn-success" style="text-decoration: none; margin-top: 8px;"><i class="fa fa-house"></i> Go to Dashboard</a>
                            <p style="color: #f59e0b; font-size: 12px; margin-top: 16px;"><i class="fa fa-exclamation-triangle" style="margin-right: 4px;"></i> Do NOT discard your old seed phrase until all funds are moved to the new address. The blockchain scanner will complete the identity swap once the transaction is confirmed on-chain.</p>
                            <a</a>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer">@include('footer')</footer>

    <script src="/assets/wallet/js/dist/my_bundle.js"></script>
    <script src="/assets/wallet/js/libs/jquery-1.10.2.min.js"></script>
    <script src="/assets/wallet/js/libs/bootstrap.min.js"></script>
    <script src="/assets/wallet/js/mvpready-core.js"></script>
    <script src="/assets/wallet/js/mvpready-admin.js"></script>
    <script type="text/javascript">
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    // Marscoin network
    const Marscoin = { mainnet: {
        messagePrefix: "\x19Marscoin Signed Message:\n", bech32: "M", bip44: 2,
        bip32: { public: 0x043587cf, private: 0x04358394 },
        pubKeyHash: 0x32, scriptHash: 0x32, wif: 0x80,
    }};

    // Crypto helpers
    const SALT = "{{ $SALT }}";
    const PBKDF2_ROUNDS = 100000;
    const PBKDF2_LEGACY_ROUNDS = 1;
    var iv = "{{ json_encode($iv) }}".replace("]", "").replace("[", "").split(",");

    function hashPassword(passcode) {
        return my_bundle.pbkdf2.pbkdf2Sync(passcode, SALT, PBKDF2_ROUNDS, 16, 'sha512').toString('hex');
    }
    function hashPasswordLegacy(passcode) {
        return my_bundle.pbkdf2.pbkdf2Sync(passcode, SALT, PBKDF2_LEGACY_ROUNDS, 16, 'sha512').toString('hex');
    }
    function deriveCivicAddress(mnemonic) {
        var seed = my_bundle.bip39.mnemonicToSeedSync(mnemonic.trim());
        var root = my_bundle.bitcoin.bip32.fromSeed(seed, Marscoin.mainnet);
        var child = root.derivePath("m/44'/2'/0'/0/0");
        return my_bundle.bitcoin.payments.p2pkh({ pubkey: child.publicKey, network: Marscoin.mainnet }).address;
    }
    function deriveCivicKeyPair(mnemonic) {
        var seed = my_bundle.bip39.mnemonicToSeedSync(mnemonic.trim());
        var root = my_bundle.bitcoin.bip32.fromSeed(seed, Marscoin.mainnet);
        var child = root.derivePath("m/44'/2'/0'/0/0");
        return my_bundle.bitcoin.ECPair.fromWIF(child.toWIF(), Marscoin.mainnet);
    }

    // State
    var currentStep = 1;
    var newMnemonic = null, newCivicAddr = null, migrationId = null;
    var oldCivicAddr = "{{ $civic_wallet->public_addr ?? '' }}";
    var encryptedSeed = "{{ $civic_wallet->encrypted_seed ?? '' }}";

    // Step navigation
    function goToStep(step) {
        document.getElementById('step-' + currentStep).style.display = 'none';
        document.getElementById('step-' + step).style.display = 'block';
        currentStep = step;
        for (var i = 1; i <= 4; i++) {
            var dot = document.getElementById('dot-' + i);
            dot.className = 'migrate-step-dot' + (i < step ? ' done' : (i === step ? ' active' : ''));
        }
        if (step === 2) initStep2();
        if (step === 3) initStep3();
    }

    // Step 1: Load balance
    (function() {
        if (!oldCivicAddr) return;
        document.getElementById('dot-1').className = 'migrate-step-dot active';
        fetch('/api/balance/' + encodeURIComponent(oldCivicAddr))
            .then(function(r) { return r.json(); })
            .then(function(d) { document.getElementById('current-balance').textContent = parseFloat(d.balance || 0).toFixed(4); })
            .catch(function() { document.getElementById('current-balance').textContent = '—'; });
    })();

    // Step 2: Generate new seed
    function initStep2() {
        if (newMnemonic) return;
        newMnemonic = my_bundle.bip39.generateMnemonic();
        var words = newMnemonic.split(' '), grid = document.getElementById('seed-grid');
        grid.innerHTML = '';
        for (var i = 0; i < words.length; i++) {
            var el = document.createElement('div');
            el.className = 'seed-word';
            el.innerHTML = '<span class="num">' + (i + 1) + '.</span> ' + words[i];
            grid.appendChild(el);
        }
        newCivicAddr = deriveCivicAddress(newMnemonic);
        document.getElementById('new-civic-addr').textContent = newCivicAddr;
        document.getElementById('seed-backup-check').checked = false;
        document.getElementById('btn-step2').disabled = true;
    }

    // Step 3: Populate
    function initStep3() {
        document.getElementById('sign-old-addr').textContent = oldCivicAddr;
        document.getElementById('sign-new-addr').textContent = newCivicAddr;
        document.getElementById('migrate-password').value = '';
        document.getElementById('password-error').style.display = 'none';
        document.getElementById('sign-spinner').style.display = 'none';
        document.getElementById('sign-buttons').style.display = 'flex';
        document.getElementById('btn-sign').disabled = false;
    }

    // Step 3: Sign & Broadcast
    async function signMigration() {
        var password = document.getElementById('migrate-password').value.trim();
        var errEl = document.getElementById('password-error');
        if (!password) { errEl.textContent = 'Please enter your wallet password.'; errEl.style.display = 'block'; return; }

        errEl.style.display = 'none';
        document.getElementById('btn-sign').disabled = true;
        document.getElementById('sign-spinner').style.display = 'block';
        document.getElementById('sign-spinner-text').textContent = 'Decrypting wallet...';
        await new Promise(function(r) { setTimeout(r, 50); });

        try {
            // 1. Decrypt old civic wallet seed (try 100k rounds, fallback 1 round)
            var oldMnemonic = null;
            try {
                var dec = my_bundle.decrypt(encryptedSeed, hashPassword(password), iv).trim();
                if (deriveCivicAddress(dec) === oldCivicAddr) oldMnemonic = dec;
            } catch (e) {}
            if (!oldMnemonic) {
                try {
                    var decL = my_bundle.decrypt(encryptedSeed, hashPasswordLegacy(password), iv).trim();
                    if (deriveCivicAddress(decL) === oldCivicAddr) oldMnemonic = decL;
                } catch (e) {}
            }
            if (!oldMnemonic) {
                errEl.textContent = 'Incorrect password — could not decrypt wallet.';
                errEl.style.display = 'block';
                document.getElementById('sign-spinner').style.display = 'none';
                document.getElementById('btn-sign').disabled = false;
                return;
            }

            // 2. Initiate migration on server
            document.getElementById('sign-spinner-text').textContent = 'Initiating migration...';
            var initResp = await $.ajax({
                url: '/api/wallet/migrate/initiate', type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ old_address: oldCivicAddr, new_address: newCivicAddr }),
            });
            migrationId = initResp.migration_id;

            // 3. Build OP_RETURN tx: MG_<new_address>
            document.getElementById('sign-spinner-text').textContent = 'Building transaction...';
            var dustAmount = 0.0001;
            var utxoUrl = 'https://pebas.marscoin.org/api/mars/utxo?sender_address=' +
                encodeURIComponent(oldCivicAddr) + '&receiver_address=' +
                encodeURIComponent(oldCivicAddr) + '&amount=' + dustAmount;
            var txIO = await (await fetch(utxoUrl)).json();
            if (!txIO || !txIO.inputs || txIO.inputs.length === 0) {
                throw new Error('No UTXOs on civic address. Fund it with a small amount first.');
            }

            // 4. Sign transaction
            document.getElementById('sign-spinner-text').textContent = 'Signing transaction...';
            var oldKey = deriveCivicKeyPair(oldMnemonic);
            var psbt = new my_bundle.bitcoin.Psbt({ network: Marscoin.mainnet });
            psbt.setVersion(1);
            psbt.setMaximumFeeRate(100000);

            // OP_RETURN output
            var embed = my_bundle.bitcoin.payments.embed({ data: [my_bundle.Buffer('MG_' + newCivicAddr)] });
            psbt.addOutput({ script: embed.output, value: 0 });

            txIO.inputs.forEach(function(input) {
                psbt.addInput({ hash: input.txId, index: input.vout, nonWitnessUtxo: my_bundle.Buffer.from(input.rawTx, 'hex') });
            });
            txIO.outputs.forEach(function(output) {
                if (!output.address) output.address = oldCivicAddr;
                psbt.addOutput({ address: output.address, value: output.value });
            });
            for (var i = 0; i < txIO.inputs.length; i++) psbt.signInput(i, oldKey);
            var txHex = psbt.finalizeAllInputs().extractTransaction().toHex();

            // 5. Broadcast
            document.getElementById('sign-spinner-text').textContent = 'Broadcasting...';
            var bResp = await fetch('https://pebas.marscoin.org/api/mars/broadcast', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
                body: JSON.stringify({ a: 1, txhash: txHex }),
            });
            var bData = await bResp.json();
            var txid = bData.txid || bData.tx_hash || bData.result || txHex.substring(0, 64);

            // 6. Encrypt new seed & confirm
            document.getElementById('sign-spinner-text').textContent = 'Confirming migration...';
            var encryptedNewSeed = my_bundle.encrypt(newMnemonic.trim(), hashPassword(password), new Uint8Array(iv));
            await $.ajax({
                url: '/api/wallet/migrate/confirm', type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ migration_id: migrationId, txid: txid, encrypted_seed: encryptedNewSeed }),
            });

            // 7. Done
            document.getElementById('done-old-addr').textContent = oldCivicAddr;
            document.getElementById('done-new-addr').textContent = newCivicAddr;
            var explorerUrl = 'https://explore.marscoin.org/tx/' + txid;
            document.getElementById('done-txid-link').href = explorerUrl;
            document.getElementById('done-txid-link').textContent = txid;
            if (typeof WalletKey !== 'undefined') WalletKey.clear();
            goToStep(4);

        } catch (err) {
            console.error('Migration error:', err);
            document.getElementById('sign-spinner').style.display = 'none';
            document.getElementById('btn-sign').disabled = false;
            errEl.textContent = err.responseJSON ? err.responseJSON.message : (err.message || 'Migration failed. Please try again.');
            errEl.style.display = 'block';
        }
    }
    </script>
    @livewireScripts
</body>
</html>
