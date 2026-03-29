<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <title>The Ballot Box - Bill #{{ $proposal->id }} - Martian Republic</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Cast your secret ballot on the Martian Republic blockchain">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/assets/wallet/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-admin.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-flat.css">
    <link rel="shortcut icon" href="/assets/favicon.ico">
    <script src="/assets/wallet/js/plugins/scan/qrcode-gen.min.js"></script>

    <style>
    /* ============================================
       THE BALLOT BOX — Voting Wizard
       Martian Republic Civic Ceremony
       ============================================ */
    :root {
        --mr-void: #06060c;
        --mr-dark: #0c0c16;
        --mr-surface: #12121e;
        --mr-surface-raised: #1a1a2a;
        --mr-mars: #c84125;
        --mr-mars-glow: rgba(200,65,37,0.15);
        --mr-cyan: #00e4ff;
        --mr-green: #34d399;
        --mr-amber: #f59e0b;
        --mr-red: #ef4444;
        --mr-text: #e4e4e7;
        --mr-text-dim: #8a8998;
        --mr-text-faint: #5a5968;
        --mr-border: rgba(255,255,255,0.06);
        --mr-border-bright: rgba(255,255,255,0.12);
    }

    html, body { background: var(--mr-void) !important; color: var(--mr-text); margin: 0; padding: 0; }
    * { box-sizing: border-box; }

    .ballot-wizard {
        max-width: 620px;
        margin: 0 auto;
        padding: 40px 24px 80px;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* ---- Progress Dots ---- */
    .ballot-progress {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        margin-bottom: 48px;
        padding-top: 20px;
    }
    .ballot-dot {
        width: 10px; height: 10px;
        border-radius: 50%;
        background: var(--mr-border-bright);
        transition: all 0.4s ease;
    }
    .ballot-dot.done { background: var(--mr-green); }
    .ballot-dot.active { background: var(--mr-cyan); box-shadow: 0 0 10px rgba(0,228,255,0.4); }
    .ballot-connector {
        width: 20px; height: 2px;
        background: var(--mr-border-bright);
        transition: background 0.4s ease;
    }
    .ballot-connector.done { background: var(--mr-green); }

    /* ---- Steps ---- */
    .ballot-step {
        display: none;
        flex: 1;
        animation: stepIn 0.5s ease-out;
    }
    .ballot-step.active { display: flex; flex-direction: column; }
    @keyframes stepIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ---- Card ---- */
    .ballot-card {
        background: var(--mr-surface);
        border: 1px solid var(--mr-border);
        border-radius: 16px;
        padding: 40px 32px;
        text-align: center;
    }
    .ballot-icon {
        width: 72px; height: 72px;
        border-radius: 18px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 24px;
        font-size: 32px;
    }
    .ballot-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 18px; font-weight: 700;
        letter-spacing: 2px; text-transform: uppercase;
        color: #fff; margin-bottom: 8px;
    }
    .ballot-sub {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px; letter-spacing: 1px;
        color: var(--mr-text-dim);
        margin-bottom: 24px;
    }
    .ballot-desc {
        font-size: 15px; line-height: 1.8;
        color: var(--mr-text-dim);
        margin-bottom: 28px;
        max-width: 480px;
        margin-left: auto; margin-right: auto;
    }

    /* ---- Buttons ---- */
    .ballot-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 10px;
        padding: 16px 40px;
        border-radius: 10px;
        font-family: 'Orbitron', sans-serif;
        font-size: 13px; font-weight: 700;
        letter-spacing: 2px; text-transform: uppercase;
        text-decoration: none; cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }
    .ballot-btn-mars {
        background: var(--mr-mars); color: #fff !important;
    }
    .ballot-btn-mars:hover {
        background: transparent; border-color: var(--mr-mars); color: var(--mr-mars) !important;
        box-shadow: 0 0 30px rgba(200,65,37,0.2);
    }

    /* ---- Proposal Info (Step 1) ---- */
    .proposal-info {
        background: var(--mr-dark);
        border: 1px solid var(--mr-border);
        border-left: 3px solid var(--mr-mars);
        border-radius: 0 12px 12px 0;
        padding: 24px 28px;
        text-align: left;
        margin-bottom: 28px;
    }
    .proposal-info-title {
        font-size: 18px; font-weight: 700; color: #fff;
        margin-bottom: 8px; line-height: 1.4;
    }
    .proposal-info-meta {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px; letter-spacing: 1.5px; text-transform: uppercase;
        color: var(--mr-text-dim);
    }
    .proposal-info-meta .tier { color: var(--mr-cyan); }
    .proposal-info-desc {
        font-size: 14px; line-height: 1.7; color: var(--mr-text-dim);
        margin-top: 12px;
    }

    .info-bullets {
        text-align: left;
        margin: 20px auto;
        max-width: 420px;
    }
    .info-bullet {
        display: flex; align-items: flex-start; gap: 12px;
        margin-bottom: 14px;
        font-size: 14px; color: var(--mr-text-dim); line-height: 1.6;
    }
    .info-bullet i {
        color: var(--mr-cyan);
        margin-top: 3px;
        flex-shrink: 0;
        width: 16px;
        text-align: center;
    }

    /* ---- Progress Items (Step 2) ---- */
    .prep-list {
        text-align: left;
        max-width: 380px;
        margin: 0 auto 28px;
    }
    .prep-item {
        display: flex; align-items: center; gap: 14px;
        padding: 12px 0;
        border-bottom: 1px solid var(--mr-border);
        font-size: 14px; color: var(--mr-text-dim);
        opacity: 0.4;
        transition: opacity 0.4s ease;
    }
    .prep-item.active { opacity: 1; color: var(--mr-text); }
    .prep-item.done { opacity: 1; color: var(--mr-green); }
    .prep-item .check {
        width: 24px; height: 24px;
        border-radius: 50%;
        border: 2px solid var(--mr-border-bright);
        display: flex; align-items: center; justify-content: center;
        font-size: 11px;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }
    .prep-item.done .check {
        background: var(--mr-green);
        border-color: var(--mr-green);
        color: #fff;
    }
    .prep-item.active .check {
        border-color: var(--mr-cyan);
        color: var(--mr-cyan);
    }

    /* ---- Voter Dots (Step 3) ---- */
    .voter-dots {
        display: flex; justify-content: center; gap: 24px;
        margin: 32px 0;
    }
    .voter-dot {
        width: 48px; height: 48px;
        border-radius: 50%;
        background: var(--mr-dark);
        border: 2px solid var(--mr-border-bright);
        display: flex; align-items: center; justify-content: center;
        font-size: 20px;
        color: var(--mr-text-dim);
        transition: all 0.5s ease;
    }
    .voter-dot.connected {
        border-color: var(--mr-green);
        color: var(--mr-green);
        background: rgba(52,211,153,0.08);
        box-shadow: 0 0 16px rgba(52,211,153,0.2);
    }
    .voter-counter {
        font-family: 'Orbitron', sans-serif;
        font-size: 14px; color: var(--mr-text-dim);
        margin-top: 16px;
    }
    .voter-counter strong { color: #fff; }

    /* ---- Shuffle Phases (Step 4) ---- */
    .shuffle-phases {
        text-align: left;
        max-width: 380px;
        margin: 0 auto 28px;
    }
    .shuffle-phase {
        display: flex; align-items: center; gap: 14px;
        padding: 14px 0;
        border-bottom: 1px solid var(--mr-border);
        font-size: 14px; color: var(--mr-text-dim);
        opacity: 0.3;
        transition: all 0.4s ease;
    }
    .shuffle-phase.active { opacity: 1; color: var(--mr-cyan); }
    .shuffle-phase.done { opacity: 1; color: var(--mr-green); }
    .shuffle-phase .phase-icon {
        width: 28px; height: 28px;
        border-radius: 50%;
        border: 2px solid var(--mr-border-bright);
        display: flex; align-items: center; justify-content: center;
        font-size: 10px;
        flex-shrink: 0;
        transition: all 0.3s;
    }
    .shuffle-phase.done .phase-icon {
        background: var(--mr-green); border-color: var(--mr-green); color: #fff;
    }
    .shuffle-phase.active .phase-icon {
        border-color: var(--mr-cyan); color: var(--mr-cyan);
        animation: pulse 1.5s infinite;
    }

    /* ---- Blockchain Animation (Step 5) ---- */
    .chain-anim {
        margin: 32px auto;
        text-align: center;
    }
    .chain-dots {
        display: flex; justify-content: center; gap: 8px;
        margin-bottom: 16px;
    }
    .chain-dot {
        width: 12px; height: 12px;
        border-radius: 50%;
        background: var(--mr-cyan);
        animation: chainPulse 1.2s infinite;
    }
    .chain-dot:nth-child(2) { animation-delay: 0.2s; }
    .chain-dot:nth-child(3) { animation-delay: 0.4s; }
    @keyframes chainPulse {
        0%, 100% { opacity: 0.3; transform: scale(0.8); }
        50% { opacity: 1; transform: scale(1.2); }
    }

    /* ---- Vote Buttons (Step 6) ---- */
    .vote-buttons-grid {
        display: flex; gap: 16px; justify-content: center;
        margin: 40px 0;
    }
    .vote-btn {
        flex: 1; max-width: 180px;
        padding: 36px 20px;
        border-radius: 16px;
        text-align: center;
        font-family: 'Orbitron', sans-serif;
        font-size: 18px; font-weight: 800;
        letter-spacing: 3px; text-transform: uppercase;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        text-decoration: none;
        display: block;
    }
    .vote-btn:hover { transform: translateY(-3px); text-decoration: none; }
    .vote-btn i { display: block; font-size: 32px; margin-bottom: 12px; }

    .vote-yes {
        background: rgba(52,211,153,0.08); color: var(--mr-green);
        border-color: rgba(52,211,153,0.25);
    }
    .vote-yes:hover {
        background: var(--mr-green); color: var(--mr-void);
        box-shadow: 0 0 40px rgba(52,211,153,0.3);
    }
    .vote-no {
        background: rgba(239,68,68,0.08); color: var(--mr-red);
        border-color: rgba(239,68,68,0.25);
    }
    .vote-no:hover {
        background: var(--mr-red); color: #fff;
        box-shadow: 0 0 40px rgba(239,68,68,0.3);
    }
    .vote-abstain {
        background: rgba(245,158,11,0.08); color: var(--mr-amber);
        border-color: rgba(245,158,11,0.25);
    }
    .vote-abstain:hover {
        background: var(--mr-amber); color: var(--mr-void);
        box-shadow: 0 0 40px rgba(245,158,11,0.3);
    }

    .vote-message {
        font-size: 15px; line-height: 1.7;
        color: var(--mr-text-dim);
        max-width: 440px;
        margin: 0 auto 12px;
    }
    .vote-message strong { color: #fff; }

    /* ---- Success (Step 7) ---- */
    .success-check {
        width: 88px; height: 88px;
        border-radius: 50%;
        background: rgba(52,211,153,0.08);
        border: 3px solid var(--mr-green);
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 24px;
        font-size: 40px; color: var(--mr-green);
        animation: checkIn 0.6s ease-out;
    }
    @keyframes checkIn {
        0% { transform: scale(0); opacity: 0; }
        60% { transform: scale(1.15); }
        100% { transform: scale(1); opacity: 1; }
    }
    .success-hash {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px; color: var(--mr-cyan);
        word-break: break-all;
        text-decoration: none;
        display: block;
        margin: 16px 0;
    }
    .success-hash:hover { color: #fff; }
    .back-link {
        display: inline-flex; align-items: center; gap: 8px;
        color: var(--mr-text-dim); text-decoration: none;
        font-size: 14px; margin-top: 24px;
        transition: color 0.2s;
    }
    .back-link:hover { color: var(--mr-cyan); text-decoration: none; }

    /* ---- Warning Banner ---- */
    .ballot-warning {
        display: flex; align-items: center; gap: 10px;
        padding: 12px 20px;
        background: rgba(245,158,11,0.06);
        border: 1px solid rgba(245,158,11,0.15);
        border-radius: 8px;
        font-size: 12px; color: var(--mr-amber);
        margin-top: 20px;
    }
    .ballot-warning i { flex-shrink: 0; }

    /* ---- Animations ---- */
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    .spinner {
        width: 48px; height: 48px;
        border: 3px solid var(--mr-border-bright);
        border-top-color: var(--mr-cyan);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 20px;
    }

    /* ---- Responsive ---- */
    @media (max-width: 640px) {
        .ballot-card { padding: 28px 20px; }
        .vote-buttons-grid { flex-direction: column; align-items: center; }
        .vote-btn { max-width: 260px; width: 100%; }
        .voter-dots { gap: 16px; }
    }
    </style>
</head>

<body>
    <div id="wrapper">
        <header class="navbar navbar-inverse" role="banner">
            <div class="container">
                <div class="navbar-header">
                    @include('wallet.header')
                </div>
                <nav class="collapse navbar-collapse" role="navigation">
                    @include('wallet.navbarleft')
                    @include('wallet.navbarright')
                </nav>
            </div>
        </header>
        @include('wallet.mainnav', array('active'=>'congress'))

        <div class="content">
            @if($wallet_open)
            <div class="ballot-wizard">

                {{-- PROGRESS DOTS --}}
                <div class="ballot-progress" id="progress-bar">
                    <div class="ballot-dot active" data-step="1"></div>
                    <div class="ballot-connector"></div>
                    <div class="ballot-dot" data-step="2"></div>
                    <div class="ballot-connector"></div>
                    <div class="ballot-dot" data-step="3"></div>
                    <div class="ballot-connector"></div>
                    <div class="ballot-dot" data-step="4"></div>
                    <div class="ballot-connector"></div>
                    <div class="ballot-dot" data-step="5"></div>
                    <div class="ballot-connector"></div>
                    <div class="ballot-dot" data-step="6"></div>
                    <div class="ballot-connector"></div>
                    <div class="ballot-dot" data-step="7"></div>
                </div>

                {{-- ========== STEP 1: The Proposal ========== --}}
                <div class="ballot-step active" id="step-1">
                    <div class="ballot-card">
                        <div class="ballot-icon" style="background:var(--mr-mars-glow); color:var(--mr-mars);">
                            <i class="fa-solid fa-check-to-slot"></i>
                        </div>
                        <div class="ballot-title">The Ballot Box</div>
                        <div class="ballot-sub">Bill #{{ $proposal->id }} &middot; {{ strtoupper($proposal->tier ?? 'signal') }}</div>

                        <div class="proposal-info">
                            <div class="proposal-info-title">{{ $proposal->title }}</div>
                            <div class="proposal-info-meta">
                                Sponsored by {{ $proposal->author ?? 'Unknown' }}
                                &middot; <span class="tier">{{ strtoupper($proposal->tier ?? $proposal->category) }}</span>
                                &middot; {{ $proposal->threshold }}% threshold
                            </div>
                            @if($proposal->description)
                            <div class="proposal-info-desc">
                                {{ substr($proposal->description, 0, 200) }}@if(strlen($proposal->description) > 200)...@endif
                            </div>
                            @endif
                            <div style="display:flex; gap:16px; margin-top:14px;">
                                <a href="/congress/proposal/{{ $proposal->id }}" target="_blank" style="font-family:'JetBrains Mono',monospace; font-size:10px; color:var(--mr-cyan); text-decoration:none; letter-spacing:1px;">
                                    <i class="fa-solid fa-file-lines" style="margin-right:4px;"></i> READ FULL PROPOSAL <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:8px; margin-left:2px;"></i>
                                </a>
                                @if($proposal->discussion)
                                <a href="/forum/t/{{ $proposal->discussion }}-{{ Str::slug($proposal->title) }}" target="_blank" style="font-family:'JetBrains Mono',monospace; font-size:10px; color:var(--mr-text-dim); text-decoration:none; letter-spacing:1px;">
                                    <i class="fa-solid fa-comments" style="margin-right:4px;"></i> DISCUSSION <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:8px; margin-left:2px;"></i>
                                </a>
                                @endif
                            </div>
                        </div>

                        <div class="info-bullets">
                            <div class="info-bullet">
                                <i class="fa-solid fa-shuffle"></i>
                                <span>Your ballot will be anonymized through a cryptographic shuffle with other voters</span>
                            </div>
                            <div class="info-bullet">
                                <i class="fa-solid fa-users"></i>
                                <span>At least 3 citizens must be present for the ballot shuffle to begin</span>
                            </div>
                            <div class="info-bullet">
                                <i class="fa-solid fa-eye-slash"></i>
                                <span>No one — not even the system — can trace your vote back to you</span>
                            </div>
                        </div>

                        <a href="#" id="btn-request-ballot" class="ballot-btn ballot-btn-mars">
                            <i class="fa-solid fa-check-to-slot"></i> Request Secret Ballot
                        </a>

                        <div class="ballot-warning">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <span>Please keep this tab open throughout the voting process. Your ballot key exists only in this session.</span>
                        </div>
                    </div>
                </div>

                {{-- ========== STEP 2: Preparing Ballot ========== --}}
                <div class="ballot-step" id="step-2">
                    <div class="ballot-card">
                        <div class="spinner"></div>
                        <div class="ballot-title">Preparing Your Ballot</div>
                        <div class="ballot-sub">Generating anonymous identity</div>

                        <div class="prep-list">
                            <div class="prep-item" id="prep-identity">
                                <div class="check"><i class="fa-solid fa-check"></i></div>
                                <span>Generating anonymous identity...</span>
                            </div>
                            <div class="prep-item" id="prep-inputs">
                                <div class="check"><i class="fa-solid fa-check"></i></div>
                                <span>Preparing secure inputs...</span>
                            </div>
                            <div class="prep-item" id="prep-keys">
                                <div class="check"><i class="fa-solid fa-check"></i></div>
                                <span>Loading cryptographic keys...</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ========== STEP 3: Waiting for Voters ========== --}}
                <div class="ballot-step" id="step-3">
                    <div class="ballot-card">
                        <div class="ballot-icon" style="background:rgba(0,228,255,0.08); color:var(--mr-cyan);">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <div class="ballot-title">Waiting for Citizens</div>
                        <div class="ballot-sub">The shuffle requires 3 participants</div>

                        <div class="voter-dots">
                            <div class="voter-dot" id="voter-1"><i class="fa-solid fa-user"></i></div>
                            <div class="voter-dot" id="voter-2"><i class="fa-solid fa-user"></i></div>
                            <div class="voter-dot" id="voter-3"><i class="fa-solid fa-user"></i></div>
                        </div>

                        <div class="voter-counter">
                            <strong id="voter-count">0</strong> of 3 citizens connected
                        </div>

                        <div class="ballot-warning" style="margin-top:32px;">
                            <i class="fa-solid fa-info-circle" style="color:var(--mr-cyan);"></i>
                            <span style="color:var(--mr-text-dim);">The ballot shuffle will begin automatically when all voters are present.</span>
                        </div>
                    </div>
                </div>

                {{-- ========== STEP 4: Shuffling ========== --}}
                <div class="ballot-step" id="step-4">
                    <div class="ballot-card">
                        <div class="ballot-icon" style="background:rgba(0,228,255,0.08); color:var(--mr-cyan);">
                            <i class="fa-solid fa-shuffle"></i>
                        </div>
                        <div class="ballot-title">Shuffling Ballots</div>
                        <div class="ballot-sub">Anonymizing your identity</div>

                        <div class="shuffle-phases">
                            <div class="shuffle-phase" id="phase-init">
                                <div class="phase-icon"><i class="fa-solid fa-check"></i></div>
                                <span>Initiating anonymous shuffle...</span>
                            </div>
                            <div class="shuffle-phase" id="phase-encrypt">
                                <div class="phase-icon"><i class="fa-solid fa-check"></i></div>
                                <span>Encrypting and shuffling ballots...</span>
                            </div>
                            <div class="shuffle-phase" id="phase-sign">
                                <div class="phase-icon"><i class="fa-solid fa-check"></i></div>
                                <span>Collecting signatures...</span>
                            </div>
                            <div class="shuffle-phase" id="phase-broadcast">
                                <div class="phase-icon"><i class="fa-solid fa-check"></i></div>
                                <span>Broadcasting to blockchain...</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ========== STEP 5: Confirming on Blockchain ========== --}}
                <div class="ballot-step" id="step-5">
                    <div class="ballot-card">
                        <div class="ballot-icon" style="background:rgba(0,228,255,0.08); color:var(--mr-cyan);">
                            <i class="fa-solid fa-link"></i>
                        </div>
                        <div class="ballot-title">Confirming on Blockchain</div>
                        <div class="ballot-sub">Waiting for block confirmation</div>

                        <div class="chain-anim">
                            <div class="chain-dots">
                                <div class="chain-dot"></div>
                                <div class="chain-dot"></div>
                                <div class="chain-dot"></div>
                            </div>
                            <div style="font-size:13px; color:var(--mr-text-dim);">
                                Your ballot is being confirmed on the Marscoin blockchain...
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ========== STEP 6: Cast Your Vote ========== --}}
                <div class="ballot-step" id="step-6">
                    <div class="ballot-card" style="padding:48px 32px;">
                        <div class="ballot-icon" style="background:rgba(52,211,153,0.08); color:var(--mr-green);">
                            <i class="fa-solid fa-check-circle"></i>
                        </div>
                        <div class="ballot-title">Cast Your Vote</div>
                        <div class="ballot-sub">Ballot #{{ $proposal->id }} &middot; Issued</div>

                        <p class="vote-message">
                            Your vote is <strong>secret</strong>. No one — not even the system — can trace it back to you.
                        </p>

                        <div class="vote-buttons-grid">
                            <a href="#" id="pry" class="vote-btn vote-yes">
                                <i class="fa-solid fa-thumbs-up"></i>
                                Yes
                            </a>
                            <a href="#" id="prn" class="vote-btn vote-no">
                                <i class="fa-solid fa-thumbs-down"></i>
                                No
                            </a>
                            <a href="#" id="pra" class="vote-btn vote-abstain">
                                <i class="fa-solid fa-minus"></i>
                                Abstain
                            </a>
                        </div>

                        <p style="font-size:12px; color:var(--mr-text-faint); margin-top:8px;">
                            One voter. One vote. <strong style="color:var(--mr-text-dim);">Immutable.</strong>
                        </p>
                    </div>
                </div>

                {{-- ========== STEP 7: Success ========== --}}
                <div class="ballot-step" id="step-7">
                    <div class="ballot-card" style="padding:48px 32px;">
                        <div class="success-check"><i class="fa-solid fa-check"></i></div>
                        <div class="ballot-title">Vote Cast Successfully</div>
                        <div class="ballot-sub">Permanently recorded on the Marscoin blockchain</div>

                        <a id="cast_confirmation" href="" target="_blank" class="success-hash"></a>

                        <div class="proposal-info" style="margin-top:24px;">
                            <div class="proposal-info-title">{{ $proposal->title }}</div>
                            <div class="proposal-info-meta">
                                Bill #{{ $proposal->id }} &middot; {{ strtoupper($proposal->tier ?? $proposal->category) }}
                            </div>
                        </div>

                        <a href="/congress/voting" class="back-link">
                            <i class="fa-solid fa-arrow-left"></i> Return to Congress Hall
                        </a>
                    </div>
                </div>

            </div>

            @else
            {{-- Wallet locked --}}
            <div style="text-align:center; padding:80px 20px;">
                <i class="fa-solid fa-check-to-slot" style="font-size:56px; color:var(--mr-text-dim); margin-bottom:20px; display:block; opacity:0.5;"></i>
                <h2 style="font-family:'Orbitron',sans-serif; font-size:20px; color:#fff; letter-spacing:1px; margin-bottom:12px;">Wallet Required</h2>
                <p style="color:var(--mr-text-dim); font-size:14px; margin-bottom:24px;">Unlock your civic wallet to cast your vote.</p>
                <a href="/wallet/dashboard/hd" class="ballot-btn ballot-btn-mars">
                    <i class="fa-solid fa-lock-open"></i> Unlock Wallet
                </a>
            </div>
            @endif
        </div>
    </div>

    <footer class="footer" style="border-top:1px solid var(--mr-border); padding:20px 0; background:var(--mr-void); z-index:100; position:relative;">
        @include('footer')
    </footer>

    <script src="/assets/wallet/js/dist/my_bundle.js"></script>
    <script src="/assets/wallet/js/libs/jquery-1.10.2.min.js"></script>
    <script src="/assets/wallet/js/libs/bootstrap.min.js"></script>
    <script src="/assets/wallet/js/jquery-ui.min.js"></script>
    <script src="/assets/wallet/js/md5.min.js"></script>
    <script src="/assets/wallet/js/sha256.js"></script>
    <script src="/assets/wallet/js/jsencrypt.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js" integrity="sha512-E8QSvWZ0eCLGk4km3hxSsNmGWbLtSCSUcewDQPQWZF6pEU8GlT8a5fF32wOl1i8ftdMhssTrF/OhyGWwonTcXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
// ============================================================
// THE BALLOT BOX — Wizard Controller + CoinShuffle Protocol
// ============================================================

// --- Wizard Navigation ---
var currentStep = 1;

var stepNames = ['', 'Proposal Info', 'Preparing Ballot', 'Waiting for Citizens', 'Shuffling', 'Confirming on Chain', 'Cast Vote', 'Success'];

function goToStep(step) {
    currentStep = step;
    console.log('%c[Ballot] Step ' + step + ': ' + (stepNames[step] || ''), 'color: #00e4ff; font-weight: bold;');
    // Hide all steps
    document.querySelectorAll('.ballot-step').forEach(function(s) { s.classList.remove('active'); });
    // Show target step
    var target = document.getElementById('step-' + step);
    if (target) target.classList.add('active');
    // Update progress dots
    document.querySelectorAll('.ballot-dot').forEach(function(d, i) {
        d.classList.remove('active', 'done');
        if (i + 1 < step) d.classList.add('done');
        else if (i + 1 === step) d.classList.add('active');
    });
    document.querySelectorAll('.ballot-connector').forEach(function(c, i) {
        c.classList.remove('done');
        if (i + 1 < step) c.classList.add('done');
    });
}

// --- SessionStorage for ballot key resilience ---
var STORAGE_KEY = 'ballot_<?=$propid?>';

function saveBallotState(state) {
    try { sessionStorage.setItem(STORAGE_KEY, JSON.stringify(state)); } catch(e) {}
}

function loadBallotState() {
    try {
        var s = sessionStorage.getItem(STORAGE_KEY);
        return s ? JSON.parse(s) : null;
    } catch(e) { return null; }
}

function clearBallotState() {
    try { sessionStorage.removeItem(STORAGE_KEY); } catch(e) {}
}

$(document).ready(function() {

    // ============================================================
    // ALL EXISTING CRYPTO LOGIC — PRESERVED EXACTLY
    // ============================================================

    const Marscoin = {
        mainnet: {
            messagePrefix: "\x19Marscoin Signed Message:\n",
            bech32: "M",
            bip44: 2,
            bip32: { public: 0x043587cf, private: 0x04358394 },
            pubKeyHash: 0x32, scriptHash: 0x32, wif: 0x80,
        }
    };
    var crypt = new JSEncrypt({default_key_size: 1024});
    crypt.getKey();
    var amount = 0.1
    var addr = '{{$public_address}}'
    var hidden_target = "generated receiving address"
    var privkey = crypt.getPrivateKeyB64()
    var ek = crypt.getPublicKeyB64()
    var index = null
    var peers = null
    var order = null
    var is_last_shuffler = null
    var start_called = false
    var bpk = "";
    var bpkk = "";
    var local_key = "";
    var inputBlock = {}
    var ptimer = null
    var voterCount = 0;

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    async function doAjax(ajaxurl, args) {
        try { return await $.ajax({ url: ajaxurl, type: 'POST', data: args }); }
        catch (error) { console.error(error); }
    }

    const getLocalKey = async () => {
        const mnemonic = WalletKey.get().trim();
        const sender_address = "<?=$public_address?>".trim()
        const seed = my_bundle.bip39.mnemonicToSeedSync(mnemonic);
        const root = my_bundle.bip32.fromSeed(seed, Marscoin.mainnet)
        const child = root.derivePath("m/44'/2'/0'/0/0");
        const wif = child.toWIF()
        local_key = my_bundle.bitcoin.ECPair.fromWIF(wif, Marscoin.mainnet);
    }

    const initBallot = async () => {
        // Step 2: item 1 - generate identity
        document.getElementById('prep-identity').classList.add('active');
        hidden_target = getProposalOutputAddress();

        await new Promise(r => setTimeout(r, 400));
        document.getElementById('prep-identity').classList.remove('active');
        document.getElementById('prep-identity').classList.add('done');

        // Step 2: item 2 - prepare inputs
        document.getElementById('prep-inputs').classList.add('active');
        inputBlock = await getInputBlock();

        document.getElementById('prep-inputs').classList.remove('active');
        document.getElementById('prep-inputs').classList.add('done');

        // Step 2: item 3 - load keys
        document.getElementById('prep-keys').classList.add('active');
        await getLocalKey();

        // Save to sessionStorage
        saveBallotState({
            random_bytes: '<?=$random_bytes?>',
            hidden_target: hidden_target,
            propid: <?=$propid?>,
            step: 3
        });

        await new Promise(r => setTimeout(r, 300));
        document.getElementById('prep-keys').classList.remove('active');
        document.getElementById('prep-keys').classList.add('done');

        await new Promise(r => setTimeout(r, 500));
        // Advance to Step 3
        goToStep(3);
        connectToServer();
    }

    const getInputBlock = async () => {
        var sources = []
        var sender_address = addr;
        var receiver_address = hidden_target;
        const io = await getTxInputsOutputs(sender_address, receiver_address, 0.1)
        io.inputs.forEach((input, i) => {
            var obj = {'txId': input.txId, 'vout': input.vout, 'rawTx': my_bundle.Buffer.from(input.rawTx, 'hex'), 'value': input.value, 'originator': "{{$public_address}}"};
            sources.push(obj);
        })
        return JSON.stringify(sources);
    }

    const getTxInputsOutputs = async (sender_address, receiver_address, amount) => {
        if (!sender_address || !receiver_address || !amount) throw new Error("Missing inputs");
        const url = `https://pebas.marscoin.org/api/mars/utxo?sender_address=${sender_address}&receiver_address=${receiver_address}&amount=${amount}`
        const response = await fetch(url, { method: 'GET' });
        return response.json()
    }

    function pollConfirmation(txId) {
        $.get("https://pebas.marscoin.org/api/mars/txdetails?txid="+txId, {}, function(data) {
            if(data && data.confirmations && data.confirmations > 0){
                clearTimeout(ptimer);
                goToStep(6); // Show vote buttons!
            }
        });
        ptimer = setTimeout(pollConfirmation, 15000, txId); // Poll every 15s
    }

    function parseHexString(str) {
        var result = [];
        while (str.length >= 2) { result.push(parseInt(str.substring(0, 2), 16)); str = str.substring(2, str.length); }
        return result;
    }

    function createHexString(arr) {
        var result = "";
        for (var i in arr) { var str = arr[i].toString(16); str = str.length == 0 ? "00" : str.length == 1 ? "0" + str : str.length == 2 ? str : str.substring(str.length-2, str.length); result += str; }
        return result;
    }

    function nodeToLegacyAddress(hdNode) {
        return my_bundle.bitcoin.payments.p2pkh({ pubkey: hdNode.publicKey, network: Marscoin.mainnet }).address;
    }

    function genSeed(mnemonic){
        seed = my_bundle.bip39.mnemonicToSeedSync(mnemonic);
        root = my_bundle.bip32.fromSeed(seed, Marscoin.mainnet)
        child = root.derivePath("m/999999'/107'/<?=$propid?>'");
        wif2 = child.toWIF()
        bpkk = my_bundle.bitcoin.ECPair.fromWIF(wif2, Marscoin.mainnet);
        addy = nodeToLegacyAddress(bpkk)
        return { address: addy, pubKey: bpkk.publicKey.toString('hex'), xprv: root.toBase58(), mnemonic: mnemonic };
    }

    function getProposalOutputAddress(){
        rb = '<?=$random_bytes?>';
        rb = parseHexString(createHexString(rb))
        mnemonic = my_bundle.bip39.entropyToMnemonic(rb)
        const wallet = genSeed(mnemonic)
        return wallet.address;
    }

    function find_index(order){
        index = -1;
        Object.keys(order).forEach(function(k){
            if(order[k].replace(/\s/g,'') == ek.replace(/\s/g,'')){ index = k; }
        });
        return index;
    }

    function shuffle(array) {
        let currentIndex = array.length, randomIndex;
        while (currentIndex != 0) { randomIndex = Math.floor(Math.random() * currentIndex); currentIndex--; [array[currentIndex], array[randomIndex]] = [array[randomIndex], array[currentIndex]]; }
        return array;
    }

    function shuffle_data(data){
        old_order = Object.keys(data); new_order = shuffle(old_order); new_data = {}
        for([i, v] of Object.entries(data)) { new_data[new_order[parseInt(i)]] = v }
        return new_data
    }

    function decrypt_data(data){
        new_data = {}
        for([i, v] of Object.entries(data)) { vp = v; vp['target'] = decrypt(privkey, v["target"]); new_data[i] = vp; }
        return new_data;
    }

    function encrypt(epkey, message){
        var crypt = new JSEncrypt(); crypt.setKey(epkey);
        var password = "test"; var iterations = 500; var keySize = 256;
        var salt = CryptoJS.lib.WordArray.random(128/8);
        var output = CryptoJS.PBKDF2(password, salt, { keySize: keySize/32, iterations: iterations });
        messageKey = output.toString(CryptoJS.enc.Base64)
        var ctext = CryptoJS.AES.encrypt(message, messageKey);
        encMsg = ctext.toString(); ckey = crypt.encrypt(messageKey);
        return ckey + encMsg;
    }

    function decrypt(keypair, ctext){
        var crypt = new JSEncrypt(); crypt.setKey(keypair);
        encM = ctext; encKey = encM.substring(0, 172);
        ctext = encM.substring(172, encM.length);
        messageKey = crypt.decrypt(encKey);
        message = CryptoJS.AES.decrypt(ctext, messageKey);
        return message.toString(CryptoJS.enc.Utf8)
    }

    function encrypt_dest(){
        var t = hidden_target
        for (let i = num_peers-1; i > index; --i) { t = encrypt(order[i], t) }
        return t
    }

    const zubrinConvert = (MARS) => { return (MARS * 100000000) }
    const marsConvert = (zubrin) => { return (zubrin / 100000000) }

    function createRawTransaction(sources, destinations) {
        var psbt = new my_bundle.bitcoin.Psbt({ network: Marscoin.mainnet });
        psbt.setVersion(1); psbt.setMaximumFeeRate(10000000);
        const zubs = zubrinConvert(amount); origins = []
        Object.keys(sources).forEach(function(k){
            iB = sources[k]; inputBlock = JSON.parse(iB)[0]
            psbt.addInput({ hash: inputBlock.txId, index: inputBlock.vout, nonWitnessUtxo: my_bundle.Buffer.from(inputBlock.rawTx, 'hex') })
            if (!origins.includes(inputBlock.originator)) {
                origins.push(inputBlock.originator);
                psbt.addOutput({ address: inputBlock.originator, value: inputBlock.value - (zubs + zubrinConvert(0.1)) })
            }
        });
        Object.keys(destinations).forEach(function(k){
            output = destinations[k]; target = output['target']
            psbt.addOutput({ address: target, value: zubs })
        });
        return psbt.toBase64();
    }

    function signPartial(psbtBaseText) {
        const signer1 = my_bundle.bitcoin.Psbt.fromBase64(psbtBaseText);
        signer1.signAllInputs(local_key);
        return signer1.toBase64();
    }

    const broadcastTxHash = async (txhashstring) => {
        if (!txhashstring) throw new Error("Missing tx hash...");
        const url = "https://pebas.marscoin.org/api/mars/broadcast";
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
                body: JSON.stringify({ a: 1, txhash: txhashstring })
            });
            const data = await response.json();
            if (data.error) { console.error("Broadcast rejected:", data.error); return null; }
            return data;
        } catch (error) { console.error("Broadcast error:", error); return null; }
    }

    // ============================================================
    // WEBSOCKET + SHUFFLE PROTOCOL (mapped to wizard steps)
    // ============================================================

    var socket = null;

    function connectToServer() {
        var domain = document.domain.split('.')[1]
        if(domain == "local")
            socket = new WebSocket("wss://127.0.0.1:3678");
        else
            socket = new WebSocket("wss://martianrepublic.org/wss/ballot");

        socket.onopen = function(e) {
            console.log('%c[Ballot] WebSocket connected', 'color: #34d399;');
            socket.send("{{$public_address}}_{{ strtoupper(substr(str_replace('https://ipfs.marscoin.org/ipfs/', '', $proposal->ipfs_hash), 1, 8)) }}");
        };

        socket.onmessage = function(event) {
            // Count voters from "waiting for X more" messages
            if (event.data.includes("waiting for")) {
                var match = event.data.match(/waiting for (\d+) more/);
                if (match) {
                    var remaining = parseInt(match[1]);
                    voterCount = 3 - remaining;
                    updateVoterDots(voterCount);
                }
            }

            if (event.data == "JOINED_ACK") {
                console.log('%c[Ballot] JOINED_ACK — submitting ephemeral key', 'color: #34d399;');
                socket.send("SUBMIT_KEY#"+ek+"#")
            }

            if (event.data.includes("INITIATE_SHUFFLE_")) {
                // Transition to Step 4: Shuffling
                goToStep(4);
                setPhase('phase-init', 'active');

                json = event.data.split("_")[2]
                data = JSON.parse(json);
                start_called = true
                peers = JSON.parse(data.peers)
                num_peers = Object.keys(peers).length;
                order = JSON.parse(data.order)
                ord_length = Object.keys(order).length;
                index = parseInt(find_index(order))
                is_last_shuffler = (index + 1 == ord_length)
                encrypted_target = encrypt_dest()

                setPhase('phase-init', 'done');
                setPhase('phase-encrypt', 'active');

                if (index != 0)
                    socket.send("SHUFFLE_INIT_COMPLETE_"+JSON.stringify(encrypted_target));
            }

            if (event.data.includes("PERFORM_SHUFFLE")) {
                setPhase('phase-encrypt', 'active');

                json = event.data.split("#")[1]
                data = JSON.parse(json);
                json = event.data.split("#")[2]
                sources = JSON.parse(json);
                data = decrypt_data(data)
                data[index] = { "public_key": ek, "target": encrypted_target }
                sources[index] = inputBlock;
                data = shuffle_data(data)

                setPhase('phase-encrypt', 'done');

                if (index == num_peers - 1){
                    setPhase('phase-sign', 'active');
                    raw_tx = createRawTransaction(sources, data)
                    socket.send("COLLECT_SIGNATURES#" + raw_tx)
                } else {
                    socket.send("PERFORM_SHUFFLE_ACK#"+index+"#{'data': "+JSON.stringify(data) + "," + "'sources': "+JSON.stringify(sources)+"}")
                }
            }

            if (event.data.includes("SIGN_TX") && !event.data.includes("COMPLETE")) {
                setPhase('phase-sign', 'active');
                raw_tx = event.data.split("#")[1];
                signed_raw_tx = signPartial(raw_tx);
                socket.send("SIGN_TX_COMPLETE#"+index+"#" + signed_raw_tx);
                setPhase('phase-sign', 'done');
            }

            if (event.data.includes("COMBINE_AND_BROADCAST")) {
                setPhase('phase-broadcast', 'active');
                raw_tx = event.data.split("#")[1];
                try {
                    var parsedTexts = JSON.parse(raw_tx);
                    var initial = parsedTexts[Object.keys(parsedTexts)[0]];
                    var psbt = my_bundle.bitcoin.Psbt.fromBase64(initial);
                    for (var i = 0; i < Object.keys(parsedTexts).length; i++) {
                        var stext = parsedTexts[Object.keys(parsedTexts)[i]];
                        var fin = my_bundle.bitcoin.Psbt.fromBase64(stext);
                        psbt.combine(fin);
                    }
                    var tx = psbt.finalizeAllInputs().extractTransaction(true);
                    var txhash = tx.toHex();

                    broadcastTxHash(txhash).then(function(result) {
                        if (result && result.tx_hash) {
                            setPhase('phase-broadcast', 'done');
                            // Save ballot tx to sessionStorage
                            saveBallotState({ ballot_txid: result.tx_hash, step: 5 });
                            // Go to Step 5: Confirming
                            goToStep(5);
                            pollConfirmation(result.tx_hash);
                        } else {
                            setPhase('phase-broadcast', 'done');
                            alert("Ballot broadcast failed. Please try again.");
                        }
                    }).catch(function(err) {
                        alert("Broadcast error: " + err.message);
                    });
                } catch(e) {
                    console.error("Combine error:", e);
                    alert("Error combining signatures: " + e.message);
                }
            }
        };

        socket.onclose = function(event) {
            console.log('%c[Ballot] WebSocket closed: code=' + event.code, 'color: #f59e0b;');
            if (currentStep >= 2 && currentStep <= 4) {
                console.warn('[Ballot] Connection lost during active shuffle phase!');
            }
        };

        socket.onerror = function(error) {
            console.error('[Ballot] WebSocket error:', error);
        };
    }

    function updateVoterDots(count) {
        for (var i = 1; i <= 3; i++) {
            var dot = document.getElementById('voter-' + i);
            if (i <= count) dot.classList.add('connected');
            else dot.classList.remove('connected');
        }
        document.getElementById('voter-count').textContent = count;
    }

    function setPhase(id, state) {
        var el = document.getElementById(id);
        if (!el) return;
        el.classList.remove('active', 'done');
        el.classList.add(state);
    }

    // ============================================================
    // VOTE CASTING (PRY / PRN / PRA)
    // ============================================================

    function castVote(voteType) {
        var prefix = voteType === 'yes' ? 'PRY' : voteType === 'no' ? 'PRN' : 'PRA';
        var message = prefix + "_<?=str_replace("https://ipfs.marscoin.org/ipfs/", "", $proposal->ipfs_hash)?>";

        sendMARS(0.01, hidden_target).then(function(io) {
            return signMARS(message, 0.01, io);
        }).then(function(tx) {
            if (tx && tx.tx_hash) {
                // Success — go to Step 7
                $("#cast_confirmation").text(tx.tx_hash);
                $("#cast_confirmation").attr("href", "https://explore.marscoin.org/tx/" + tx.tx_hash);
                clearBallotState(); // Clear sessionStorage — vote is done
                goToStep(7);

                // Record the vote
                doAjax("/api/setfeed", {
                    "type": prefix,
                    "txid": tx.tx_hash,
                    "embedded_link": "https://ipfs.marscoin.org/ipfs/<?=str_replace("https://ipfs.marscoin.org/ipfs/", "", $proposal->ipfs_hash)?>",
                    "address": '<?=$public_address?>'
                });
            }
        }).catch(function(e) {
            alert("Vote failed: " + e.message);
        });
    }

    const sendMARS = async (mars_amount, receiver_address) => {
        const sender_address = receiver_address;
        const io = await getTxInputsOutputs(sender_address, receiver_address, mars_amount)
        return io
    }

    const signMARS = async (message, mars_amount, tx_i_o) => {
        const sender_address = hidden_target
        const zubs = zubrinConvert(mars_amount)
        var psbt = new my_bundle.bitcoin.Psbt({ network: Marscoin.mainnet });
        psbt.setVersion(1); psbt.setMaximumFeeRate(10000000);
        var data = my_bundle.Buffer(message)
        const embed = my_bundle.bitcoin.payments.embed({ data: [data] });
        psbt.addOutput({ script: embed.output, value: 0 })
        tx_i_o.inputs.forEach((input, i) => {
            psbt.addInput({ hash: input.txId, index: input.vout, nonWitnessUtxo: my_bundle.Buffer.from(input.rawTx, 'hex') })
        })
        for (let i = 0; i < tx_i_o.inputs.length; i++) {
            try { psbt.signInput(i, bpkk); } catch (e) { alert("Signing error. Please reconnect your wallet."); }
        }
        const tx = psbt.finalizeAllInputs().extractTransaction();
        const txhash = tx.toHex()
        const result = await broadcastTxHash(txhash);
        return result;
    }

    // ============================================================
    // BUTTON HANDLERS
    // ============================================================

    // Step 1: Request Ballot
    $("#btn-request-ballot").click(function(e) {
        e.preventDefault();
        goToStep(2);
        initBallot();
    });

    // Step 6: Vote buttons
    $("#pry").click(function(e) { e.preventDefault(); castVote('yes'); });
    $("#prn").click(function(e) { e.preventDefault(); castVote('no'); });
    $("#pra").click(function(e) { e.preventDefault(); castVote('abstain'); });

    // ============================================================
    // ON LOAD: Check sessionStorage for resume
    // ============================================================
    var savedState = loadBallotState();
    if (savedState) {
        console.log('%c[Ballot] Resuming from saved state, step: ' + savedState.step, 'color: #f59e0b; font-weight: bold;');

        // Restore ballot keys from saved random_bytes
        if (savedState.random_bytes && savedState.hidden_target) {
            console.log('[Ballot] Restoring ballot keys from sessionStorage');
            var rb = savedState.random_bytes;
            rb = parseHexString(createHexString(rb));
            var mnemonic = my_bundle.bip39.entropyToMnemonic(rb);
            genSeed(mnemonic); // This sets bpkk globally
            hidden_target = savedState.hidden_target;
        }

        if (savedState.ballot_txid) {
            // Ballot was broadcast — resume confirming (Step 5) or vote (Step 6)
            goToStep(5);
            pollConfirmation(savedState.ballot_txid);
        } else if (savedState.step >= 3) {
            // Keys were generated but shuffle not complete — restart from Step 3
            getInputBlock().then(function(ib) {
                inputBlock = ib;
                return getLocalKey();
            }).then(function() {
                goToStep(3);
                connectToServer();
            });
        }
    }

}); // end document.ready
</script>

</body>
</html>
