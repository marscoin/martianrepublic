<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <title>Draft Proposal - Martian Republic Congress</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Draft new legislation for the Martian Republic">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/assets/wallet/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-admin.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-flat.css">
    <link rel="stylesheet" href="/assets/wallet/css/simplemde.min.css">
    <link rel="shortcut icon" href="/assets/favicon.ico">

    <style>
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
        --mr-border: rgba(255,255,255,0.06);
        --mr-border-bright: rgba(255,255,255,0.12);
    }

    html, body { background: #06060c !important; }
    .footer { z-index: 100; position: relative; clear: both; }
    #wrapper { overflow: hidden; }

    .draft-page { min-height: 100vh; display: flex; flex-direction: column; }
    .draft-page .content { flex: 1; }
    .draft-page .footer { margin-top: auto; }

    /* ---- Header ---- */
    .draft-header {
        padding: 32px 0 28px;
        border-bottom: 1px solid var(--mr-border);
        margin-bottom: 32px;
        position: relative;
    }
    .draft-header::after {
        content: '';
        position: absolute;
        bottom: -1px; left: 0;
        width: 120px; height: 2px;
        background: var(--mr-mars);
    }
    .draft-subtitle {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: var(--mr-mars);
        margin-bottom: 8px;
    }
    .draft-title {
        font-family: 'Orbitron', sans-serif;
        font-weight: 800;
        font-size: 32px;
        letter-spacing: 2px;
        color: #fff;
        text-transform: uppercase;
        margin: 0;
    }

    /* ---- Form Sections ---- */
    .draft-section {
        background: var(--mr-surface);
        border: 1px solid var(--mr-border);
        border-radius: 10px;
        padding: 28px;
        margin-bottom: 20px;
    }
    .draft-section-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 11px;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--mr-text-dim);
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--mr-border);
    }

    .draft-label {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: var(--mr-text-dim);
        margin-bottom: 8px;
        display: block;
    }
    .draft-label .required { color: var(--mr-mars); }

    .draft-input {
        width: 100%;
        background: var(--mr-dark);
        border: 1px solid var(--mr-border);
        border-radius: 8px;
        padding: 14px 16px;
        color: #fff;
        font-size: 16px;
        font-family: 'Open Sans', sans-serif;
        transition: border-color 0.2s;
    }
    .draft-input:focus { outline: none; border-color: var(--mr-cyan); }
    .draft-input::placeholder { color: var(--mr-text-dim); }

    /* ---- SimpleMDE Dark Override ---- */
    .CodeMirror {
        background: var(--mr-dark) !important;
        color: var(--mr-text) !important;
        border: 1px solid var(--mr-border) !important;
        border-radius: 0 0 8px 8px !important;
        min-height: 350px;
    }
    .CodeMirror-cursor { border-left-color: var(--mr-cyan) !important; }
    .CodeMirror-selected { background: rgba(0,228,255,0.1) !important; }
    .CodeMirror-gutters { background: var(--mr-dark) !important; border-right-color: var(--mr-border) !important; }
    .CodeMirror-linenumber { color: var(--mr-text-dim) !important; }
    .editor-toolbar {
        background: var(--mr-surface-raised) !important;
        border: 1px solid var(--mr-border) !important;
        border-radius: 8px 8px 0 0 !important;
        border-bottom: none !important;
    }
    .editor-toolbar a { color: var(--mr-text-dim) !important; }
    .editor-toolbar a:hover, .editor-toolbar a.active { color: var(--mr-cyan) !important; background: transparent !important; }
    .editor-toolbar i.separator { border-left-color: var(--mr-border) !important; border-right-color: var(--mr-border) !important; }
    .editor-toolbar.fullscreen { z-index: 2470 !important; }
    .CodeMirror-scroll { min-height: 350px; }

    /* ---- Preset Selector ---- */
    .preset-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 8px;
        margin-bottom: 20px;
    }
    .preset-btn {
        background: var(--mr-dark);
        border: 1px solid var(--mr-border);
        border-radius: 8px;
        padding: 14px 12px;
        text-align: center;
        cursor: pointer;
        transition: all 0.25s ease;
        position: relative;
    }
    .preset-btn:hover { border-color: var(--mr-border-bright); background: var(--mr-surface-raised); }
    .preset-btn.active {
        border-color: var(--mr-border-bright);
        background: var(--mr-surface-raised);
    }
    .preset-btn.active::after {
        content: '';
        position: absolute;
        bottom: 0; left: 20%; right: 20%;
        height: 2px;
        background: var(--mr-mars);
        border-radius: 2px;
    }
    .preset-name {
        font-family: 'Orbitron', sans-serif;
        font-size: 10px;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #fff;
        margin-bottom: 4px;
    }
    .preset-desc {
        font-size: 10px;
        color: var(--mr-text-dim);
        line-height: 1.4;
    }

    /* ---- Sliders ---- */
    .slider-group {
        margin-bottom: 24px;
    }
    .slider-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    .slider-label {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: var(--mr-text-dim);
    }
    .slider-value {
        font-family: 'Orbitron', sans-serif;
        font-size: 16px;
        font-weight: 700;
        color: var(--mr-cyan);
    }

    /* jQuery UI slider dark override */
    .ui-widget-content {
        border: none !important;
        background: var(--mr-dark) !important;
        height: 6px !important;
        border-radius: 3px !important;
    }
    .ui-slider-range-min {
        background: var(--mr-mars) !important;
        border-radius: 3px !important;
    }
    .ui-slider .ui-slider-handle {
        width: 20px !important;
        height: 20px !important;
        border-radius: 50% !important;
        background: var(--mr-mars) !important;
        border: 3px solid var(--mr-surface) !important;
        top: -8px !important;
        margin-left: -10px !important;
        cursor: pointer !important;
        box-shadow: 0 0 12px rgba(200,65,37,0.3) !important;
    }
    .ui-slider .ui-slider-handle:hover {
        box-shadow: 0 0 20px rgba(200,65,37,0.5) !important;
    }
    .ui-slider .ui-slider-handle label { display: none !important; }
    .ui-state-default, .ui-widget-content .ui-state-default { border: none !important; }

    .slider-hint {
        font-size: 11px;
        color: var(--mr-text-dim);
        margin-top: 4px;
    }

    /* ---- Requirements Summary ---- */
    .requirements-card {
        background: var(--mr-dark);
        border: 1px solid var(--mr-border);
        border-left: 3px solid var(--mr-cyan);
        border-radius: 0 8px 8px 0;
        padding: 20px;
        margin-top: 20px;
    }
    .requirements-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 10px;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--mr-cyan);
        margin-bottom: 10px;
    }
    .requirements-text {
        font-size: 13px;
        line-height: 1.8;
        color: var(--mr-text-dim);
    }
    .requirements-text strong {
        color: #fff;
        font-family: 'JetBrains Mono', monospace;
        font-size: 12px;
    }

    /* ---- Sponsor Card ---- */
    .sponsor-card {
        display: flex;
        align-items: center;
        gap: 12px;
        background: var(--mr-dark);
        border: 1px solid var(--mr-border);
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 20px;
    }
    .sponsor-icon {
        width: 40px; height: 40px;
        border-radius: 50%;
        background: var(--mr-mars-glow);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--mr-mars);
        font-size: 16px;
        flex-shrink: 0;
    }
    .sponsor-name {
        font-family: 'Orbitron', sans-serif;
        font-size: 13px;
        font-weight: 700;
        color: #fff;
        letter-spacing: 0.5px;
    }
    .sponsor-role {
        font-size: 11px;
        color: var(--mr-text-dim);
    }

    /* ---- Submit Button ---- */
    .btn-confirm-proposal {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: var(--mr-mars) !important;
        color: #fff !important;
        padding: 16px 36px;
        border-radius: 8px;
        font-family: 'Orbitron', sans-serif;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        text-decoration: none;
        border: 1px solid transparent;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .btn-confirm-proposal:hover {
        background: transparent;
        border-color: var(--mr-mars);
        color: var(--mr-mars);
        box-shadow: 0 0 30px rgba(200,65,37,0.2);
    }

    /* ---- Modal Override ---- */
    .modal-content {
        background: var(--mr-surface) !important;
        border: 1px solid var(--mr-border) !important;
        border-radius: 10px !important;
        color: var(--mr-text) !important;
    }
    .modal-header {
        border-bottom: 1px solid var(--mr-border) !important;
        padding: 20px 24px !important;
    }
    .modal-header .close { color: var(--mr-text-dim) !important; text-shadow: none !important; opacity: 0.8 !important; }
    .modal-header .modal-title, .modal-header h3 {
        font-family: 'Orbitron', sans-serif !important;
        font-size: 14px !important;
        letter-spacing: 1.5px !important;
        text-transform: uppercase !important;
        color: #fff !important;
    }
    .modal-body {
        padding: 24px !important;
    }
    .modal-body-box {
        margin-bottom: 16px;
        padding: 12px 16px;
        background: var(--mr-dark);
        border-radius: 6px;
        border: 1px solid var(--mr-border);
    }
    .modal-body-box h5 {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: var(--mr-text-dim);
        margin: 0 0 6px;
    }
    .modal-body-box p { color: var(--mr-text); font-size: 14px; margin: 0; }
    .modal-footer {
        border-top: 1px solid var(--mr-border) !important;
        padding: 16px 24px !important;
    }
    .modal-footer .btn-primary {
        background: var(--mr-mars) !important;
        border: none !important;
        font-family: 'Orbitron', sans-serif !important;
        font-size: 11px !important;
        letter-spacing: 1.5px !important;
        text-transform: uppercase !important;
        padding: 12px 24px !important;
        border-radius: 6px !important;
    }
    .transaction-hash {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        color: var(--mr-cyan);
        word-break: break-all;
        padding: 0 24px;
    }
    #modal-message-success {
        color: var(--mr-green);
        font-family: 'JetBrains Mono', monospace;
        font-size: 12px;
    }
    #modal-message-error {
        color: var(--mr-red);
        font-family: 'JetBrains Mono', monospace;
        font-size: 12px;
    }
    .modal-backdrop { background: rgba(6,6,12,0.85) !important; }

    /* ---- Responsive ---- */
    @media (max-width: 991px) {
        .preset-grid { grid-template-columns: repeat(2, 1fr) !important; }
    }
    @media (max-width: 768px) {
        .draft-title { font-size: 22px; }
        .preset-grid { grid-template-columns: repeat(2, 1fr) !important; }
    }
    </style>
</head>

<body class="draft-page">
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
            <div class="container">

                @if($wallet_open)

                {{-- HEADER --}}
                <div class="draft-header">
                    <div class="draft-subtitle">Congress &mdash; New Legislation</div>
                    <h1 class="draft-title">Draft Proposal</h1>
                </div>

                <div class="row">
                    {{-- LEFT: Form --}}
                    <div class="col-md-8">

                        {{-- TITLE --}}
                        <div class="draft-section">
                            <div class="draft-section-title">Proposal Content</div>
                            <label class="draft-label">Title <span class="required">*</span></label>
                            <input type="text" id="title" name="title" class="draft-input" placeholder="Enter a clear, descriptive title for your proposal">

                            <label class="draft-label" style="margin-top:20px;">Description <span class="required">*</span></label>
                            <textarea id="description" name="description" cols="10" rows="60"></textarea>
                        </div>

                    </div>

                    {{-- RIGHT: Config --}}
                    <div class="col-md-4">

                        {{-- SPONSOR --}}
                        <div class="sponsor-card">
                            <div class="sponsor-icon"><i class="fa-solid fa-user-tie"></i></div>
                            <div>
                                <div class="sponsor-name">{{ $fullname }}</div>
                                <div class="sponsor-role">Sponsor</div>
                            </div>
                        </div>

                        {{-- PRESETS --}}
                        <div class="draft-section">
                            <div class="draft-section-title">Proposal Type</div>

                            <div class="preset-grid" style="grid-template-columns: repeat(2, 1fr);">
                                <div class="preset-btn active" data-preset="signal" style="border-top:2px solid var(--mr-green);">
                                    <div class="preset-name" style="color:var(--mr-green);">Signal</div>
                                    <div class="preset-desc">Temperature check</div>
                                </div>
                                <div class="preset-btn" data-preset="operational" style="border-top:2px solid var(--mr-cyan);">
                                    <div class="preset-name" style="color:var(--mr-cyan);">Operational</div>
                                    <div class="preset-desc">Day-to-day</div>
                                </div>
                                <div class="preset-btn" data-preset="legislative" style="border-top:2px solid var(--mr-amber);">
                                    <div class="preset-name" style="color:var(--mr-amber);">Legislative</div>
                                    <div class="preset-desc">New policy</div>
                                </div>
                                <div class="preset-btn" data-preset="constitutional" style="border-top:2px solid var(--mr-mars);">
                                    <div class="preset-name" style="color:var(--mr-mars);">Constitutional</div>
                                    <div class="preset-desc">Code change</div>
                                </div>
                            </div>

                            {{-- Hidden select for JS compat --}}
                            <select name="preset" id="preset" style="display:none;">
                                <option value="signal" selected>Signal</option>
                                <option value="operational">Operational</option>
                                <option value="legislative">Legislative</option>
                                <option value="constitutional">Constitutional</option>
                            </select>

                            {{-- Descriptor text (hidden, used by modal) --}}
                            <div class="descriptor-text" id="signal-descriptor" style="display:block; margin-top:16px; font-size:13px; color:var(--mr-text-dim); line-height:1.7;">Signal: Non-binding temperature check — think of it as a formal poll. 10% active quorum, simple majority, 7 sols. No CoinShuffle required.</div>
                            <div class="descriptor-text" id="operational-descriptor" style="display:none;">Operational: Routine governance, resource allocation, parameter changes. 25% active quorum, 60% approval, 14 sols + 3-sol timelock. Expires after 1 Martian year (668 sols).</div>
                            <div class="descriptor-text" id="legislative-descriptor" style="display:none;">Legislative: Significant policy, committees, major treasury decisions. 40% active quorum, 66% supermajority, 30 sols + 7-sol timelock. Quiet ending. Expires after 4 Earth years.</div>
                            <div class="descriptor-text" id="constitutional-descriptor" style="display:none;">Constitutional: Code changes, governance rules, citizenship parameters. 50% active quorum, 75% supermajority, 60 sols + 30-sol timelock. Quiet ending. Never expires. Proposal text = code diff.</div>
                        </div>

                        {{-- SLIDERS --}}
                        <div class="draft-section">
                            <div class="draft-section-title">Parameters</div>

                            <div class="slider-group">
                                <div class="slider-header">
                                    <span class="slider-label"><i class="fa-solid fa-users" style="color:var(--mr-cyan); margin-right:4px;"></i> Participation</span>
                                    <span class="slider-value" id="val-participation">5%</span>
                                </div>
                                <div id="slider"></div>
                                <div class="slider-hint">Minimum citizen participation required</div>
                            </div>

                            <div class="slider-group">
                                <div class="slider-header">
                                    <span class="slider-label"><i class="fa-solid fa-calendar" style="color:var(--mr-cyan); margin-right:4px;"></i> Duration</span>
                                    <span class="slider-value" id="val-duration">7 days</span>
                                </div>
                                <div id="slider2"></div>
                                <div class="slider-hint">Voting window in days</div>
                            </div>

                            <div class="slider-group">
                                <div class="slider-header">
                                    <span class="slider-label"><i class="fa-solid fa-gavel" style="color:var(--mr-cyan); margin-right:4px;"></i> Threshold</span>
                                    <span class="slider-value" id="val-threshold">51%</span>
                                </div>
                                <div id="slider3"></div>
                                <div class="slider-hint">Percentage of yay votes to pass</div>
                            </div>

                            <div class="slider-group">
                                <div class="slider-header">
                                    <span class="slider-label"><i class="fa-solid fa-hourglass" style="color:var(--mr-cyan); margin-right:4px;"></i> Expiration</span>
                                    <span class="slider-value" id="val-expiration">Never</span>
                                </div>
                                <div id="slider4"></div>
                                <div class="slider-hint">Auto-expire after N sols (0 = never)</div>
                            </div>

                            <input type="hidden" id="amount" class="form-control">
                            <input type="hidden" id="duration" class="form-control">
                            <input type="hidden" id="threshold" class="form-control">
                            <input type="hidden" id="expiration" class="form-control">
                        </div>

                        {{-- REQUIREMENTS SUMMARY --}}
                        <div class="requirements-card">
                            <div class="requirements-title">Requirements Summary</div>
                            <div class="requirements-text">
                                <strong><span id="req_amount">5%</span></strong> citizen participation &middot;
                                <strong><span id="req_duration">7 days</span></strong> voting window &middot;
                                <strong><span id="req_threshold">51%</span></strong> to pass &middot;
                                Expires: <strong><span id="req_expiration">Never</span></strong>
                            </div>
                        </div>

                        {{-- SUBMIT --}}
                        <div style="margin-top:24px; text-align:center;">
                            <a data-toggle="modal" href="#proposalModal" id="proposalModalBtn" class="btn-confirm-proposal">
                                <i class="fa-solid fa-paper-plane"></i> Submit to Blockchain
                            </a>
                        </div>

                    </div>
                </div>

                {{-- MODAL --}}
                <div id="proposalModal" class="modal styled fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h3 class="modal-title">Confirm Submission</h3>
                            </div>
                            <div class="modal-body">
                                <div class="modal-body-box">
                                    <h5>Category</h5>
                                    <p class="modal-category"></p>
                                </div>
                                <div class="modal-body-box">
                                    <h5>Description</h5>
                                    <p class="modal-description"></p>
                                </div>
                                <div class="modal-body-box">
                                    <h5>Configuration</h5>
                                    <p class="modal-configuration"></p>
                                </div>
                                <div class="modal-message" style="display:none; margin-top:12px; padding:12px; background:var(--mr-dark); border-radius:6px;">
                                    <i class="fa fa-times-circle"></i>
                                    <span id="modal-message-error"></span>
                                    <span id="modal-message-success" style="display:none;">Proposal submitted to blockchain successfully!</span>
                                </div>
                            </div>
                            <h5 class="transaction-hash" style="text-align:center;"></h5>
                            <div class="modal-footer">
                                <button id="submit-proposal" type="submit" class="btn btn-primary">Submit Proposal</button>
                                <img src="https://i.stack.imgur.com/FhHRx.gif" alt="Loading..." style="display:none; height:24px;" id="loading">
                            </div>
                        </div>
                    </div>
                </div>

                @else
                {{-- WALLET LOCKED --}}
                <div style="text-align:center; padding:80px 20px;">
                    <i class="fa-solid fa-landmark" style="font-size:56px; color:var(--mr-text-dim); margin-bottom:20px; display:block; opacity:0.5;"></i>
                    <h2 style="font-family:'Orbitron',sans-serif; font-size:20px; color:#fff; letter-spacing:1px; margin-bottom:12px;">Wallet Required</h2>
                    <p style="color:var(--mr-text-dim); font-size:14px; margin-bottom:24px;">Unlock your civic wallet to draft proposals.</p>
                    <a href="/wallet/dashboard/hd" style="display:inline-flex; align-items:center; gap:10px; background:var(--mr-mars); color:#fff; padding:14px 28px; border-radius:8px; font-family:'Orbitron',sans-serif; font-size:12px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; text-decoration:none;">
                        <i class="fa-solid fa-lock-open"></i> Unlock Wallet
                    </a>
                </div>
                @endif

            </div>
        </div>
    </div>

    <footer class="footer" style="border-top:1px solid var(--mr-border,rgba(255,255,255,0.06)); padding:20px 0; background:var(--mr-void,#06060c); z-index:100;">
        @include('footer')
    </footer>

    <script src="/assets/wallet/js/dist/my_bundle.js"></script>
    <script src="/assets/wallet/js/libs/jquery-1.10.2.min.js"></script>
    <script src="/assets/wallet/js/libs/bootstrap.min.js"></script>
    <script src="/assets/wallet/js/jquery-ui.min.js"></script>
    <script src="/assets/wallet/js/simplemde.min.js"></script>
    <script src="/assets/wallet/js/md5.min.js"></script>
    <script src="/assets/wallet/js/sha256.js"></script>

    <script>
    var simplemde = new SimpleMDE({ element: document.getElementById("description") });

    // Preset buttons
    document.querySelectorAll('.preset-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.preset-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            var val = this.getAttribute('data-preset');
            $('#preset').val(val).trigger('change');
        });
    });
    </script>

    <script>
    $(document).ready(function() {
        function updateDisplays(participation, duration, threshold, expiration) {
            $('#val-participation').text(participation + '%');
            $('#val-duration').text(duration + (duration === 1 ? ' day' : ' days'));
            $('#val-threshold').text(threshold + '%');
            $('#val-expiration').text(expiration === 0 ? 'Never' : expiration + ' sols');
            $('#req_amount').text(participation + '%');
            $('#req_duration').text(duration + ' days');
            $('#req_threshold').text(threshold + '%');
            $('#req_expiration').text(expiration === 0 ? 'Never' : expiration + ' sols');
        }

        // Default: Signal tier
        updateDisplays(10, 7, 51, 0);

        $('#preset').on('change', function() {
            $('.descriptor-text').hide();
            var v = this.value;
            if (v === 'signal') {
                $("#slider").slider("value", 10); $("#slider2").slider("value", 7); $("#slider3").slider("value", 51); $("#slider4").slider("value", 0);
                updateDisplays(10, 7, 51, 0);
                $("#signal-descriptor").show();
            } else if (v === 'operational') {
                $("#slider").slider("value", 25); $("#slider2").slider("value", 14); $("#slider3").slider("value", 60); $("#slider4").slider("value", 668);
                updateDisplays(25, 14, 60, 668);
                $("#operational-descriptor").show();
            } else if (v === 'legislative') {
                $("#slider").slider("value", 40); $("#slider2").slider("value", 30); $("#slider3").slider("value", 66); $("#slider4").slider("value", 2672);
                updateDisplays(40, 30, 66, 2672);
                $("#legislative-descriptor").show();
            } else if (v === 'constitutional') {
                $("#slider").slider("value", 50); $("#slider2").slider("value", 60); $("#slider3").slider("value", 75); $("#slider4").slider("value", 0);
                updateDisplays(50, 60, 75, 0);
                $("#constitutional-descriptor").show();
            }
        });

        $("#slider").slider({ animate: true, value: 10, min: 5, max: 100, step: 1, range: "min",
            slide: function(e, ui) { updateDisplays(ui.value, $("#slider2").slider("value"), $("#slider3").slider("value"), $("#slider4").slider("value")); }
        });
        $("#slider2").slider({ animate: true, value: 7, min: 1, max: 680, step: 1, range: "min",
            slide: function(e, ui) { updateDisplays($("#slider").slider("value"), ui.value, $("#slider3").slider("value"), $("#slider4").slider("value")); }
        });
        $("#slider3").slider({ animate: true, value: 51, min: 51, max: 100, step: 1, range: "min",
            slide: function(e, ui) { updateDisplays($("#slider").slider("value"), $("#slider2").slider("value"), ui.value, $("#slider4").slider("value")); }
        });
        $("#slider4").slider({ animate: true, value: 0, min: 0, max: 3000, step: 1, range: "min",
            slide: function(e, ui) { updateDisplays($("#slider").slider("value"), $("#slider2").slider("value"), $("#slider3").slider("value"), ui.value); }
        });

        $("#amount").val(5);
        $("#duration").val(7);
        $("#threshold").val(51);
        $("#expiration").val(0);
    });
    </script>

    <script type="text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    });
    </script>

    <script>
    function isValidCID(hash) {
        const cidv0Regex = /^Qm[a-zA-Z1-9]{44}$/;
        const cidv1Regex = /^b[a-z2-7]{58}$/;
        return cidv0Regex.test(hash) || cidv1Regex.test(hash);
    }

    $(document).ready(function() {
        if (WalletKey.get() === null) {
            // Key not loaded - user may need to reconnect wallet
            console.log("WalletKey not loaded");
        }

        async function doAjax(ajaxurl, args) {
            try {
                return await $.ajax({ url: ajaxurl, type: 'POST', data: args });
            } catch (error) {
                console.error(error);
            }
        }

        $("#proposalModalBtn").click(async (e) => {
            handleFormFilled();
            $(".modal-title").text($("#title").val());
            var fulltext = simplemde.value();
            $(".modal-description").text(fulltext.substring(0, 200));
            $(".modal-configuration").text($("#" + $("#preset").val() + "-descriptor").text());
            $(".modal-category").text($("#preset").val());

            if ('<?=$public_address?>') {
                $.ajax({
                    url: "/api/balance/<?=$public_address?>",
                    type: 'GET',
                    success: function(balance) {
                        balance = parseFloat(balance.balance);
                        if (balance > 0.1) {
                            $(".modal-message").hide();
                            $("#submit-proposal").prop("disabled", false);
                            $("#submit-proposal").off('click').click(async () => {
                                $("#loading").show();
                                try {
                                    var obj = { data: {}, meta: {} };
                                    obj.data.title = $("#title").val();
                                    obj.data.description = simplemde.value();
                                    obj.data.category = $("#preset").val();
                                    obj.data.participation = $("#slider").slider('value');
                                    obj.data.duration = $("#slider2").slider('value');
                                    obj.data.threshold = $("#slider3").slider('value');
                                    obj.data.expiration = $("#slider4").slider('value');
                                    var jsonString = JSON.stringify(obj.data);
                                    obj.meta.hash = sha256(jsonString);
                                    jsonString = JSON.stringify(obj);
                                    var utcnow = new Date().getTime();
                                    const data = await doAjax("/api/permapinjson", {"type": "proposal_"+utcnow, "payload": jsonString, "address": '<?=$public_address?>'});
                                    if (data.Hash == "Error" || data.Hash == "undefined" || !isValidCID(data.Hash)) {
                                        alert("Pinning data failed. Check IPFS connection and try again later.");
                                        return false;
                                    }
                                    var cid = data.Hash;
                                    var message = "PR_"+cid;
                                    const io = await sendMARS(1, "<?=$public_address?>");
                                    const tx = await signMARS(message, 0.01, io);
                                    $(".transaction-hash").text(tx.tx_hash);
                                    const feedData = await doAjax("/api/setfeed", {"type": "PR", "txid": tx.tx_hash, message: $("#title").val(), "embedded_link": "https://ipfs.marscoin.org/ipfs/"+cid, "address": '<?=$public_address?>'});
                                    if (feedData.Hash) {
                                        $("#modal-message-success").show();
                                        $("#loading").hide();
                                        $(".modal-footer").hide();
                                        const cacheData = await doAjax("/api/cacheproposal", {"type": "PR", "txid": tx.tx_hash, message: jsonString, "embedded_link": "https://ipfs.marscoin.org/ipfs/"+cid, "address": '<?=$public_address?>'});
                                        if (cacheData && cacheData.Proposal) {
                                            // Redirect to the new proposal page
                                            location.href = "/congress/proposal/" + cacheData.Proposal;
                                        } else {
                                            console.error("cacheproposal response:", cacheData);
                                            // Fallback: redirect to voting hub
                                            location.href = "/congress/voting";
                                        }
                                    }
                                } catch (e) {
                                    console.error(e);
                                    $("#loading").hide();
                                }
                            });
                        } else {
                            $("#submit-proposal").prop("disabled", true);
                            $("#modal-message-error").text("Insufficient MARS balance (need >0.1 MARS)");
                            $(".modal-message").show();
                        }
                    },
                    error: function() { console.log('Error fetching balance'); }
                });
            }
        });

        const handleFormFilled = () => {
            let title = $("#title").val();
            let desc = simplemde.value();
            if (title === "") {
                $("#modal-message-error").text("Title is required");
                $("#submit-proposal").prop("disabled", true);
                $(".modal-message").show();
                return false;
            } else if (desc === "") {
                $("#modal-message-error").text("Description is required");
                $("#submit-proposal").prop("disabled", true);
                $(".modal-message").show();
                return false;
            } else {
                $("#submit-proposal").prop("disabled", false);
                $(".modal-message").hide();
                return true;
            }
        };

        const Marscoin = {
            mainnet: {
                messagePrefix: "\x19Marscoin Signed Message:\n",
                bech32: "M", bip44: 2,
                bip32: { public: 0x043587cf, private: 0x04358394 },
                pubKeyHash: 0x32, scriptHash: 0x32, wif: 0x80,
            }
        };

        const sendMARS = async (mars_amount, receiver_address) => {
            const sender_address = "<?=$public_address?>".trim();
            const io = await getTxInputsOutputs(sender_address, receiver_address, mars_amount);
            return io;
        };

        const signMARS = async (message, mars_amount, tx_i_o) => {
            const mnemonic = WalletKey.get().trim();
            const sender_address = "<?=$public_address?>".trim();
            const seed = my_bundle.bip39.mnemonicToSeedSync(mnemonic);
            const root = my_bundle.bip32.fromSeed(seed, Marscoin.mainnet);
            const child = root.derivePath("m/44'/2'/0'/0/0");
            const wif = child.toWIF();
            var key = my_bundle.bitcoin.ECPair.fromWIF(wif, Marscoin.mainnet);
            var psbt = new my_bundle.bitcoin.Psbt({ network: Marscoin.mainnet });
            psbt.setVersion(1);
            psbt.setMaximumFeeRate(10000000);
            var data = my_bundle.Buffer(message);
            const embed = my_bundle.bitcoin.payments.embed({ data: [data] });
            psbt.addOutput({ script: embed.output, value: 0 });
            tx_i_o.inputs.forEach((input) => {
                psbt.addInput({ hash: input.txId, index: input.vout, nonWitnessUtxo: my_bundle.Buffer.from(input.rawTx, 'hex') });
            });
            tx_i_o.outputs.forEach(output => {
                if (!output.address) output.address = sender_address;
                psbt.addOutput({ address: output.address, value: output.value });
            });
            for (let i = 0; i < tx_i_o.inputs.length; i++) {
                try { psbt.signInput(i, key); } catch (e) { alert("Problem signing. Please reconnect your wallet."); }
            }
            const tx = psbt.finalizeAllInputs().extractTransaction();
            return await broadcastTxHash(tx.toHex());
        };

        const getTxInputsOutputs = async (sender_address, receiver_address, amount) => {
            if (!sender_address || !receiver_address || !amount) throw new Error("Missing inputs");
            const url = `https://pebas.marscoin.org/api/mars/utxo?sender_address=${sender_address}&receiver_address=${receiver_address}&amount=${amount}`;
            const response = await fetch(url, { method: 'GET' });
            return response.json();
        };

        const broadcastTxHash = async (txhash) => {
            if (!txhash) throw new Error("Missing tx hash");
            const url = 'https://pebas.marscoin.org/api/mars/broadcast?txhash=' + txhash;
            const response = await fetch(url, { method: 'GET' });
            return response.json();
        };
    });
    </script>
</body>
</html>
