<html lang="en" class="no-js">
<head>
    <title>Marscoin Wallet</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald:400,300,700">
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@700&family=Orbitron:wght@500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/assets/wallet/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-admin.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-flat.css">
    <link rel="shortcut icon" href="/assets/favicon.ico">
    <link rel="stylesheet" href="/assets/wallet/css/hd-open/hd-open.css">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    <style>
    /* ============================================ */
    /* THE BRIDGE: Active Wallet Command Interface  */
    /* ============================================ */
    body { background: var(--mr-void, #06060c) !important; color: var(--mr-text, #e0dfe6); }
    .bridge-page { min-height: 100vh; display: flex; flex-direction: column; }
    .bridge-page .content { flex: 1; }

    /* -- Hero Balance Bar -- */
    .bridge-hero {
        padding: 28px 0 22px;
        position: relative;
        margin-bottom: 24px;
    }
    .bridge-hero::after {
        content: '';
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.08) 20%, var(--mr-mars, #c84125) 50%, rgba(255,255,255,0.08) 80%, transparent);
    }
    .bridge-status {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: var(--mr-green, #34d399);
        margin-bottom: 4px;
    }
    .bridge-status .dot {
        display: inline-block;
        width: 6px; height: 6px;
        border-radius: 50%;
        background: var(--mr-green, #34d399);
        margin-right: 8px;
        animation: bridgePulse 2s infinite;
        vertical-align: middle;
    }
    .bridge-balance-row {
        display: flex;
        align-items: baseline;
        gap: 24px;
        flex-wrap: wrap;
    }
    .bridge-balance {
        font-family: 'Orbitron', sans-serif;
        font-size: 32px;
        font-weight: 800;
        color: #fff;
        letter-spacing: 1px;
    }
    .bridge-balance .unit {
        font-size: 14px;
        font-weight: 400;
        color: var(--mr-text-dim, #8a8998);
        margin-left: 6px;
    }
    .bridge-addr {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        color: var(--mr-cyan, #00e4ff);
        opacity: 0.7;
    }

    /* -- Quick Action Cards -- */
    .bridge-actions {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        margin-bottom: 28px;
    }
    .bridge-action {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        padding: 16px 12px;
        background: var(--mr-surface, #12121e);
        border: 1px solid var(--mr-border, rgba(255,255,255,0.06));
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.25s;
        text-decoration: none !important;
    }
    .bridge-action:hover {
        border-color: rgba(255,255,255,0.15);
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.3);
    }
    .bridge-action-icon {
        width: 40px; height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }
    .bridge-action-icon.send { background: rgba(200,65,37,0.15); color: var(--mr-mars, #c84125); }
    .bridge-action-icon.receive { background: rgba(52,211,153,0.15); color: var(--mr-green, #34d399); }
    .bridge-action-icon.backup { background: rgba(0,228,255,0.12); color: var(--mr-cyan, #00e4ff); }
    .bridge-action-icon.lock { background: rgba(138,137,152,0.12); color: var(--mr-text-dim, #8a8998); }
    .bridge-action-label {
        font-family: 'JetBrains Mono', monospace;
        font-size: 9px;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: var(--mr-text-dim, #8a8998);
    }

    /* -- Section label -- */
    .bridge-section {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--mr-text-dim, #8a8998);
        margin-bottom: 14px;
        padding-bottom: 8px;
        border-bottom: 1px solid var(--mr-border, rgba(255,255,255,0.06));
    }

    /* -- Card container -- */
    .bridge-card {
        background: var(--mr-surface, #12121e);
        border: 1px solid var(--mr-border, rgba(255,255,255,0.06));
        border-radius: 10px;
        padding: 24px;
        margin-bottom: 20px;
    }
    .bridge-card-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #fff;
        margin-bottom: 18px;
    }

    /* -- Send form styling -- */
    .bridge-card .form-control {
        background: var(--mr-dark, #0c0c16) !important;
        border: 1px solid var(--mr-border-bright, rgba(255,255,255,0.1)) !important;
        color: #fff !important;
        border-radius: 8px !important;
        font-family: 'JetBrains Mono', monospace !important;
        font-size: 13px !important;
        padding: 12px 16px !important;
        height: auto !important;
        transition: border-color 0.2s !important;
    }
    .bridge-card .form-control:focus {
        border-color: var(--mr-cyan, #00e4ff) !important;
        box-shadow: 0 0 0 3px rgba(0,228,255,0.08) !important;
        outline: none !important;
    }
    .bridge-card .form-control::placeholder {
        color: var(--mr-text-faint, #5a5968) !important;
    }
    .bridge-card label, .bridge-card span[for] {
        font-family: 'JetBrains Mono', monospace !important;
        font-size: 10px !important;
        letter-spacing: 1.5px !important;
        text-transform: uppercase !important;
        color: var(--mr-text-dim, #8a8998) !important;
        margin-bottom: 6px !important;
        display: block;
    }
    .bridge-card .btn-primary {
        background: var(--mr-mars, #c84125) !important;
        border-color: var(--mr-mars, #c84125) !important;
        font-family: 'JetBrains Mono', monospace !important;
        font-size: 11px !important;
        font-weight: 500;
        letter-spacing: 1.5px !important;
        text-transform: uppercase !important;
        padding: 12px 28px !important;
        border-radius: 8px !important;
        transition: all 0.2s !important;
        width: 100%;
        margin-top: 8px;
    }
    .bridge-card .btn-primary:hover {
        background: #d94e30 !important;
        box-shadow: 0 4px 20px rgba(200,65,37,0.35) !important;
    }

    /* -- Receive / QR styling -- */
    .bridge-qr-wrap {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 16px;
    }
    .bridge-qr-wrap canvas, .bridge-qr-wrap img {
        border-radius: 8px;
        border: 2px solid var(--mr-border-bright, rgba(255,255,255,0.1));
    }
    .pub-addr {
        display: flex !important;
        align-items: center !important;
        gap: 10px !important;
        background: var(--mr-dark, #0c0c16) !important;
        padding: 12px 16px !important;
        border-radius: 8px !important;
        border: 1px solid var(--mr-border, rgba(255,255,255,0.06)) !important;
        height: auto !important;
        width: 100% !important;
    }
    .pub-addr-text, .pub-addr a {
        font-family: 'JetBrains Mono', monospace !important;
        font-size: 11px !important;
        color: var(--mr-cyan, #00e4ff) !important;
        word-break: break-all;
        flex: 1;
        text-decoration: none !important;
    }
    .copy-icon {
        background: var(--mr-surface-raised, #1a1a2a) !important;
        color: var(--mr-text-dim, #8a8998) !important;
        padding: 8px 10px !important;
        border-radius: 6px !important;
        cursor: pointer !important;
        transition: all 0.2s !important;
        border: 1px solid var(--mr-border, rgba(255,255,255,0.06));
        flex-shrink: 0;
    }
    .copy-icon:hover {
        color: var(--mr-cyan, #00e4ff) !important;
        border-color: var(--mr-cyan, #00e4ff) !important;
        background: rgba(0,228,255,0.08) !important;
    }

    /* -- HD Addresses -- */
    #hd-address-list {
        background: var(--mr-dark, #0c0c16);
        border-radius: 8px;
        padding: 12px;
        max-height: 280px;
        overflow-y: auto;
        border: 1px solid var(--mr-border, rgba(255,255,255,0.06));
    }
    #hd-address-list a {
        color: var(--mr-cyan, #00e4ff) !important;
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
    }
    #hd-toggle-addresses {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        color: var(--mr-text-dim, #8a8998);
        cursor: pointer;
    }

    /* -- Security section -- */
    .bridge-security-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }
    .bridge-sec-btn {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 16px;
        background: var(--mr-dark, #0c0c16);
        border: 1px solid var(--mr-border, rgba(255,255,255,0.06));
        border-radius: 8px;
        text-decoration: none !important;
        transition: all 0.2s;
        cursor: pointer;
    }
    .bridge-sec-btn:hover {
        border-color: rgba(255,255,255,0.15);
        transform: translateY(-1px);
    }
    .bridge-sec-btn i {
        font-size: 16px;
        width: 20px;
        text-align: center;
    }
    .bridge-sec-btn .sec-label {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: var(--mr-text-dim, #8a8998);
    }
    .bridge-sec-btn .sec-desc {
        font-family: 'JetBrains Mono', monospace;
        font-size: 8px;
        color: var(--mr-text-faint, #5a5968);
        margin-top: 2px;
    }

    /* -- Portlet overrides -- */
    .portlet {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
        margin: 0 0 20px 0 !important;
    }
    .bridge-card.portlet {
        margin: 0 0 20px 0 !important;
    }
    .portlet-title {
        font-family: 'Orbitron', sans-serif !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        letter-spacing: 1.5px !important;
        text-transform: uppercase !important;
        color: #fff !important;
        border: none !important;
        text-decoration: none !important;
        margin-bottom: 16px !important;
    }
    .portlet-title u { text-decoration: none !important; }

    /* -- Exchange icon -- */
    .exchange {
        background: var(--mr-surface-raised, #1a1a2a) !important;
        color: var(--mr-text-dim, #8a8998) !important;
        padding: 8px 10px !important;
        border-radius: 6px !important;
        cursor: pointer !important;
        border: 1px solid var(--mr-border, rgba(255,255,255,0.06));
        transition: all 0.2s;
    }
    .exchange:hover {
        color: var(--mr-cyan, #00e4ff) !important;
        border-color: rgba(0,228,255,0.3) !important;
    }

    /* -- Conversion display -- */
    .conversion-rate {
        font-family: 'JetBrains Mono', monospace !important;
        font-size: 13px !important;
        color: var(--mr-text-dim, #8a8998) !important;
        padding: 5px 8px !important;
        border-radius: 4px !important;
        background: transparent !important;
    }

    /* -- Modal overrides -- */
    .modal-content {
        background: var(--mr-surface, #12121e) !important;
        border: 1px solid var(--mr-border-bright, rgba(255,255,255,0.1)) !important;
        border-radius: 12px !important;
        color: var(--mr-text, #e0dfe6) !important;
        box-shadow: 0 24px 80px rgba(0,0,0,0.6) !important;
    }
    .modal-header {
        background: var(--mr-dark, #0c0c16) !important;
        border-bottom: 1px solid var(--mr-border, rgba(255,255,255,0.06)) !important;
        border-radius: 12px 12px 0 0 !important;
        padding: 16px 24px !important;
    }
    .modal-title {
        font-family: 'Orbitron', sans-serif !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        letter-spacing: 1.5px !important;
        text-transform: uppercase !important;
        color: #fff !important;
    }
    .modal-header .close {
        color: var(--mr-text-dim) !important;
        opacity: 0.7 !important;
        text-shadow: none !important;
    }
    .modal-body { background: var(--mr-surface, #12121e) !important; padding: 24px !important; }
    .modal-footer { background: var(--mr-surface, #12121e) !important; border-top: 1px solid var(--mr-border) !important; }

    /* Scan button inside address field */
    .bridge-card .btn-default, .bridge-card .btn.btn-default {
        background: var(--mr-surface-raised, #1a1a2a) !important;
        border: 1px solid var(--mr-border-bright, rgba(255,255,255,0.12)) !important;
        color: var(--mr-text-dim, #8a8998) !important;
        font-family: 'JetBrains Mono', monospace !important;
        font-size: 10px !important;
        letter-spacing: 0.5px !important;
        text-transform: uppercase !important;
    }
    .bridge-card .btn-default:hover {
        border-color: var(--mr-cyan, #00e4ff) !important;
        color: var(--mr-cyan, #00e4ff) !important;
    }

    /* Confirmation modal sections */
    .confirm-transaction { background: transparent !important; }
    .from, .to, .amount-modal {
        background: var(--mr-dark, #0c0c16) !important;
        border: 1px solid var(--mr-border, rgba(255,255,255,0.06)) !important;
        border-radius: 8px !important;
        padding: 14px 16px !important;
        margin: 8px 0 !important;
        width: 100% !important;
        color: var(--mr-text) !important;
    }
    .from strong, .to strong, .amount-modal strong {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: var(--mr-text-dim, #8a8998);
    }
    .from p, .to p, .amount-modal p {
        font-family: 'JetBrains Mono', monospace;
        font-size: 12px;
        color: #fff;
        margin: 4px 0 0;
        word-break: break-all;
    }
    .modal .form-control {
        background: var(--mr-dark, #0c0c16) !important;
        border: 1px solid var(--mr-border-bright, rgba(255,255,255,0.1)) !important;
        color: #fff !important;
        border-radius: 6px !important;
        font-family: 'JetBrains Mono', monospace !important;
        font-size: 13px !important;
    }
    .modal .form-control:focus {
        border-color: var(--mr-cyan, #00e4ff) !important;
        box-shadow: 0 0 0 2px rgba(0,228,255,0.1) !important;
    }
    .modal .btn-primary {
        background: var(--mr-mars, #c84125) !important;
        border-color: var(--mr-mars, #c84125) !important;
        font-family: 'JetBrains Mono', monospace !important;
        font-size: 11px !important;
        letter-spacing: 1px !important;
        text-transform: uppercase !important;
        border-radius: 6px !important;
    }
    .modal .btn-primary:hover { background: #d94e30 !important; }

    /* Error messages */
    .error-message { color: var(--mr-mars, #c84125) !important; font-family: 'JetBrains Mono', monospace !important; font-size: 11px !important; }
    .error-unlocking, .error-unlocking-tx { color: var(--mr-mars, #c84125) !important; font-family: 'JetBrains Mono', monospace !important; }

    /* Alert styling */
    .alert-danger { background: rgba(200,65,37,0.1) !important; border-color: rgba(200,65,37,0.25) !important; color: var(--mr-mars) !important; border-radius: 8px !important; }
    .alert-info { background: rgba(0,228,255,0.08) !important; border-color: rgba(0,228,255,0.2) !important; color: var(--mr-cyan) !important; border-radius: 8px !important; }

    /* Footer */
    .footer { border-top: 1px solid var(--mr-border, rgba(255,255,255,0.06)) !important; padding: 20px 0 !important; background: var(--mr-void, #06060c) !important; }

    /* -- QR Scanner Popup -- */
    .scan-popup .modal-dialog {
        max-width: 480px !important;
        margin: 80px auto !important;
    }
    .scan-popup .modal-content {
        background: var(--mr-dark, #0c0c16) !important;
        border: 2px solid var(--mr-mars, #c84125) !important;
        border-radius: 12px !important;
        overflow: hidden;
        padding: 0 !important;
    }
    .scan-popup #app {
        position: relative;
    }
    .scan-popup .sidebar {
        position: absolute;
        top: 0; left: 0; right: 0;
        z-index: 10;
        padding: 12px 16px;
        background: linear-gradient(to bottom, rgba(6,6,12,0.9), transparent);
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--mr-mars, #c84125);
        text-align: center;
    }
    .scan-popup .sidebar::before {
        content: '\f029  SCANNING FOR QR CODE';
        font-family: 'JetBrains Mono', monospace, 'Font Awesome 6 Free';
    }
    .scan-popup #qr-canvas {
        display: block !important;
        width: 100% !important;
        height: auto !important;
        min-height: 360px;
        max-height: 480px;
        object-fit: cover;
        padding: 0 !important;
        border-radius: 0 !important;
    }
    .scan-popup .modal-backdrop { background: rgba(0,0,0,0.85) !important; }

    /* Corner scanner overlay */
    .scan-popup #app::before,
    .scan-popup #app::after {
        content: '';
        position: absolute;
        z-index: 5;
        pointer-events: none;
        border-color: var(--mr-mars, #c84125);
        border-style: solid;
        border-width: 0;
    }
    .scan-popup #app::before {
        top: 40px; left: 40px;
        width: 40px; height: 40px;
        border-top-width: 3px;
        border-left-width: 3px;
        border-top-left-radius: 6px;
    }
    .scan-popup #app::after {
        bottom: 40px; right: 40px;
        width: 40px; height: 40px;
        border-bottom-width: 3px;
        border-right-width: 3px;
        border-bottom-right-radius: 6px;
    }

    /* Animations */
    @keyframes bridgePulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }
    @keyframes bridgeFadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .bridge-fade-1 { animation: bridgeFadeIn 0.5s ease-out 0.1s both; }
    .bridge-fade-2 { animation: bridgeFadeIn 0.5s ease-out 0.25s both; }
    .bridge-fade-3 { animation: bridgeFadeIn 0.5s ease-out 0.4s both; }

    /* Mobile */
    @media (max-width: 767px) {
        .bridge-actions { grid-template-columns: repeat(2, 1fr); }
        .bridge-balance { font-size: 24px; }
        .bridge-security-grid { grid-template-columns: 1fr; }
        .bridge-qr-wrap { flex-direction: column; align-items: flex-start; }
    }
    </style>
    <script src="/assets/wallet/js/plugins/scan/qrcode-gen.min.js"></script>
    @livewireStyles
</head>

<body class="bridge-page">
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
            </div> <!-- /.container -->
        </header>
        @include('wallet.mainnav', ['active' => 'wallet'])

        <div class="content">
            <div class="container">

                {{-- BRIDGE HERO: Balance + Address --}}
                <div class="bridge-hero bridge-fade-1">
                    <div class="bridge-status"><span class="dot"></span>Wallet Active — Connected</div>
                    <div class="bridge-balance-row">
                        <div class="bridge-balance"><span id="bridge-balance-display"><i class="fa fa-spinner fa-spin" style="font-size: 20px; color: var(--mr-text-dim);"></i></span><span class="unit">MARS</span></div>
                        <div class="bridge-addr">{{ $public_addr }}</div>
                    </div>
                </div>

                {{-- CIVIC WALLET STATUS BAR --}}
                @if(!empty($civic_addr))
                <div class="bridge-fade-2" style="
                    display: flex; align-items: center; gap: 16px; padding: 14px 20px;
                    background: linear-gradient(135deg, rgba(200,65,37,0.06) 0%, rgba(200,65,37,0.02) 100%);
                    border: 1px solid rgba(200,65,37,0.15); border-radius: 10px;
                    margin-bottom: 20px;
                ">
                    <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(200,65,37,0.12); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fa fa-id-card" style="font-size: 14px; color: var(--mr-mars, #c84125);"></i>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 2px;">
                            <span style="font-family: 'JetBrains Mono', monospace; font-size: 9px; letter-spacing: 1.5px; text-transform: uppercase; color: var(--mr-mars, #c84125);">Civic Wallet</span>
                            <span style="font-family: 'JetBrains Mono', monospace; font-size: 9px; color: var(--mr-text-faint, #5a5968);">{{ $civic_addr }}</span>
                        </div>
                        <div style="display: flex; align-items: baseline; gap: 6px;">
                            <span id="civic-balance-display" style="font-family: 'Orbitron', sans-serif; font-size: 15px; font-weight: 700; color: #fff;">
                                <i class="fa fa-spinner fa-spin" style="font-size: 11px; color: var(--mr-text-dim);"></i>
                            </span>
                            <span style="font-family: 'JetBrains Mono', monospace; font-size: 10px; color: var(--mr-text-dim);">MARS</span>
                        </div>
                    </div>
                    <button onclick="fundCivicWallet()" style="
                        display: flex; align-items: center; gap: 6px;
                        padding: 8px 16px; border-radius: 6px;
                        background: var(--mr-mars, #c84125); border: none;
                        font-family: 'JetBrains Mono', monospace; font-size: 10px;
                        font-weight: 500; letter-spacing: 1px; text-transform: uppercase;
                        color: #fff; cursor: pointer; transition: all 0.2s; flex-shrink: 0;
                    " onmouseover="this.style.background='#d94e30';this.style.boxShadow='0 4px 16px rgba(200,65,37,0.3)'" onmouseout="this.style.background='var(--mr-mars, #c84125)';this.style.boxShadow='none'">
                        <i class="fa fa-arrow-up"></i> Fund
                    </button>
                </div>
                <script>
                // Fetch civic wallet balance
                (function() {
                    var civicAddr = '{{ $civic_addr ?? "" }}';
                    if (!civicAddr) return;
                    fetch('/api/balance/' + encodeURIComponent(civicAddr))
                        .then(function(r) { return r.json(); })
                        .then(function(d) {
                            var bal = parseFloat(d.balance || 0);
                            document.getElementById('civic-balance-display').textContent = bal.toFixed(4);
                        })
                        .catch(function() {
                            document.getElementById('civic-balance-display').textContent = '—';
                        });
                })();

                function fundCivicWallet() {
                    var civicAddr = '{{ $civic_addr ?? "" }}';
                    if (!civicAddr) { alert('No civic wallet found.'); return; }

                    // Pre-fill recipient with civic address
                    var recipientEl = document.getElementById('recipient');
                    recipientEl.value = civicAddr;
                    recipientEl.dispatchEvent(new Event('input', { bubbles: true }));
                    $(recipientEl).trigger('change').trigger('input');

                    // Visual feedback: flash the recipient field
                    recipientEl.style.borderColor = 'var(--mr-mars, #c84125)';
                    recipientEl.style.boxShadow = '0 0 0 3px rgba(200,65,37,0.15)';
                    setTimeout(function() {
                        recipientEl.style.borderColor = '';
                        recipientEl.style.boxShadow = '';
                    }, 2000);

                    // Scroll to send form
                    document.getElementById('send-section').scrollIntoView({ behavior: 'smooth' });

                    // Focus amount field after scroll
                    setTimeout(function() {
                        var amountEl = document.querySelector('.input-placeholder');
                        amountEl.focus();
                        amountEl.placeholder = 'Amount to fund civic wallet';
                    }, 500);
                }
                </script>
                @endif

                {{-- QUICK ACTIONS --}}
                <div class="bridge-actions bridge-fade-2">
                    <a href="#send-section" class="bridge-action" onclick="document.getElementById('recipient').focus()">
                        <div class="bridge-action-icon send"><i class="fa fa-arrow-up-right-from-square"></i></div>
                        <div class="bridge-action-label">Send</div>
                    </a>
                    <a href="#receive-section" class="bridge-action">
                        <div class="bridge-action-icon receive"><i class="fa fa-arrow-down"></i></div>
                        <div class="bridge-action-label">Receive</div>
                    </a>
                    <a href="javascript:void(0)" class="bridge-action" onclick="onDownloadWallet()">
                        <div class="bridge-action-icon backup"><i class="fa fa-shield-halved"></i></div>
                        <div class="bridge-action-label">Backup</div>
                    </a>
                    <a href="/wallet/dashboard/hd-close" class="bridge-action">
                        <div class="bridge-action-icon lock"><i class="fa fa-lock"></i></div>
                        <div class="bridge-action-label">Lock</div>
                    </a>
                </div>

                <div class="row">
                    <div class="col-md-7 col-sm-7">

                        @if(!session()->has('blockchain_re-sync_dismissed'))
                            <div class="connectivity alert alert-danger" style="display: none" role="alert">
                                <a class="close" data-dismiss="alert" href="#" onclick="dismissAlert('blockchain_re-sync_dismissed')" aria-hidden="true">×</a>
                                <i class="fa fa-spinner fa-spin fa-fw"></i>
                                Blockchain connection re-sycing.
                            </div>
                        @endif
                        @if($is_civic_wallet && !session()->has('passport_wallet_dismissed'))
                            <div class="alert alert-info" role="alert">
                                <a class="close" data-dismiss="alert" href="#" onclick="dismissAlert('passport_wallet_dismissed')" aria-hidden="true">×</a>
                                You are currently viewing your Passport Wallet used for civic functions.
                            </div>
                        @endif
                        <div id="send-section" class="bridge-section">Send & Receive</div>
                        <div class="bridge-card portlet">

                            <h3 class="bridge-card-title portlet-title">
                                <i class="fa fa-paper-plane" style="margin-right: 6px; color: var(--mr-mars);"></i> Send Marscoin
                            </h3>

                            <div class="portlet-body">

                                <div>
                                    <span for="name">Destination Address</span>
                                    <div style="position: relative;">
                                        <input type="text" name="recipient" id="recipient"
                                            class="form-control destination-address" style="width: 100%; padding-right: 80px !important;" data-required="true"
                                            placeholder="Marscoin Address">
                                        <a style="position: absolute; right: 6px; top: 50%; transform: translateY(-50%); padding: 6px 12px; font-size: 11px; border-radius: 5px;" href="#"
                                            onclick="scan();" class="btn btn-default"><i class="fa fa-qrcode" style="margin-right: 4px;"></i> Scan</a>
                                    </div>

                                    <span id="address-error" style="display: none;" class="error-message">Enter a valid
                                        MARS address</span>
                                    <br />

                                    <span for="name">Amount To Send: <strong class="amount-to-send"> MARS</strong></span>

                                    {{-- Amount + Conversion Row --}}
                                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 4px;">
                                        <div id="amount-cont" style="flex: 1;">
                                            <input type="number" min="1" step="any" name="amount"
                                                class="form-control input-placeholder" data-required="true"
                                                placeholder="0.0 MARS">
                                        </div>
                                        <i class="fa fa-exchange exchange" style="flex-shrink: 0;"></i>
                                        <div class="converted-value" style="flex: 0 0 auto; display: flex; align-items: baseline; gap: 2px; background: var(--mr-dark, #0c0c16); padding: 10px 14px; border-radius: 8px; border: 1px solid var(--mr-border, rgba(255,255,255,0.06));">
                                            <span class="symbol" style="font-family: 'JetBrains Mono', monospace; font-size: 14px; color: var(--mr-text-dim);"> $ </span>
                                            <span class="conversion-rate" style="font-family: 'Orbitron', sans-serif; font-size: 16px; font-weight: 600; color: #fff; padding: 0;"> 0 </span>
                                            <span class="currency-abbr" style="font-family: 'JetBrains Mono', monospace; font-size: 10px; color: var(--mr-text-faint); margin-left: 4px;"> USD</span>
                                        </div>
                                    </div>
                                    <div style="margin-bottom: 16px;">
                                        <span id="amount-error" style="display: none;" class="error-message">Enter an amount</span>
                                        <span id="insufficient-error" style="display: none;" class="error-message">Insufficient balance</span>
                                    </div>

                                    {{-- Send Button --}}
                                    <button id="send-preconfirm" type="button" class="btn btn-primary"
                                        data-toggle="modal" href=""
                                        style="width: 100%; padding: 14px 0 !important; font-size: 13px !important; letter-spacing: 2px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                        <i class="fa fa-paper-plane"></i> Send MARS
                                    </button>
                                </div>
                            </div>
                        </div>


                        {{-- Recent Transactions --}}
                        <div class="bridge-card" style="margin-top: 0;">
                            <h3 class="bridge-card-title"><i class="fa fa-clock-rotate-left" style="margin-right: 6px; color: var(--mr-text-dim);"></i> Recent Activity</h3>
                            <div id="wallet-recent-txs" style="font-family: 'JetBrains Mono', monospace; font-size: 11px;">
                                <div style="text-align: center; padding: 20px; color: var(--mr-text-faint);">
                                    <i class="fa fa-spinner fa-spin"></i> Loading transactions...
                                </div>
                            </div>
                            <div style="margin-top: 12px; text-align: center;">
                                <a href="/wallet/dashboard" style="font-family: 'JetBrains Mono', monospace; font-size: 9px; letter-spacing: 1px; text-transform: uppercase; color: var(--mr-text-dim); text-decoration: none;">
                                    View All on Dashboard <i class="fa fa-arrow-right" style="font-size: 8px; margin-left: 4px;"></i>
                                </a>
                            </div>
                        </div>
                        <script>
                        // Load recent transactions using pebas (Electrum) - works with all address formats
                        // Waits for HD discovery to provide working addresses, then fetches tx history
                        function loadRecentTxs(addresses) {
                            if (!addresses || addresses.length === 0) {
                                $('#wallet-recent-txs').html('<div style="text-align:center;padding:16px;color:var(--mr-text-faint);">No addresses found</div>');
                                return;
                            }
                            // Fetch from first address with balance (most likely to have transactions)
                            var addr = addresses[0];
                            $.get('/api/mars-txhistory?address=' + encodeURIComponent(addr), function(data) {
                                if (!data || !data.txs || data.txs.length === 0) {
                                    $('#wallet-recent-txs').html('<div style="text-align:center;padding:16px;color:var(--mr-text-faint);">No transactions yet</div>');
                                    return;
                                }
                                var txs = data.txs.sort(function(a,b){ return (b.time||0) - (a.time||0); }).slice(0, 6);
                                renderRecentTxs(txs, addresses);
                            }).fail(function() {
                                // Fallback: try explorer
                                $.post('/api/getTransactions', {address: '{{ $public_addr }}'}, function(data) {
                                    if (!data || !data.txs || data.txs.length === 0) {
                                        $('#wallet-recent-txs').html('<div style="text-align:center;padding:16px;color:var(--mr-text-faint);">No transactions yet</div>');
                                        return;
                                    }
                                    renderRecentTxs(data.txs.sort(function(a,b){ return (b.time||0) - (a.time||0); }).slice(0, 6), addresses);
                                }).fail(function() {
                                    $('#wallet-recent-txs').html('<div style="text-align:center;padding:20px 16px;color:var(--mr-text-faint);"><i class="fa fa-clock-rotate-left" style="font-size:18px;display:block;margin-bottom:8px;opacity:0.4;"></i><span style="font-size:10px;">Transaction history loading after discovery...</span></div>');
                                });
                            });
                        }

                        function renderRecentTxs(txs, myAddresses) {
                            var html = '';
                            txs.forEach(function(tx) {
                                var isSender = false, valueReceived = 0, totalTxValue = 0, isAnchor = false;
                                (tx.vin || []).forEach(function(vin) {
                                    if (vin.addr && myAddresses.indexOf(vin.addr) !== -1) { isSender = true; totalTxValue -= (vin.value || 0); }
                                });
                                (tx.vout || []).forEach(function(vout) {
                                    var sp = vout.scriptPubKey || {};
                                    var va = sp.addresses || (sp.address ? [sp.address] : []);
                                    for (var i = 0; i < va.length; i++) {
                                        if (myAddresses.indexOf(va[i]) !== -1) { valueReceived += parseFloat(vout.value || 0); break; }
                                    }
                                    if (sp.type === 'nulldata') { isAnchor = true; }
                                });
                                if (isSender) { totalTxValue -= (tx.fees || 0); }
                                totalTxValue += valueReceived;

                                var color = totalTxValue >= 0 ? 'var(--mr-green,#34d399)' : 'var(--mr-mars,#c84125)';
                                var icon = isAnchor ? 'fa-anchor' : (totalTxValue >= 0 ? 'fa-arrow-down' : 'fa-arrow-up');
                                var iconBg = isAnchor ? 'rgba(0,228,255,0.1)' : (totalTxValue >= 0 ? 'rgba(52,211,153,0.1)' : 'rgba(200,65,37,0.1)');
                                var iconColor = isAnchor ? 'var(--mr-cyan)' : color;
                                var timeStr = tx.time ? new Date(tx.time * 1000).toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'}) : 'pending';
                                var label = isAnchor ? 'On-Chain Data' : (totalTxValue >= 0 ? 'Received' : 'Sent');

                                html += '<div style="display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid var(--mr-border,rgba(255,255,255,0.04));">' +
                                    '<div style="width:28px;height:28px;border-radius:6px;background:' + iconBg + ';display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fa ' + icon + '" style="font-size:10px;color:' + iconColor + ';"></i></div>' +
                                    '<div style="flex:1;min-width:0;"><div style="color:var(--mr-text-dim);font-size:10px;">' + label + '</div><div style="color:var(--mr-text-faint);font-size:9px;">' + timeStr + '</div></div>' +
                                    '<div style="color:' + color + ';font-weight:500;font-size:11px;">' + (totalTxValue >= 0 ? '+' : '') + totalTxValue.toFixed(4) + '</div></div>';
                            });
                            $('#wallet-recent-txs').html(html);
                        }

                        // Hook into HD discovery completion to get working addresses
                        window._walletDiscoveredAddresses = [];
                        </script>

                        <div class="bridge-card" style="margin-top: 0; padding: 18px 20px;">
                            <div style="font-family: 'JetBrains Mono', monospace; font-size: 9px; letter-spacing: 1.5px; text-transform: uppercase; color: var(--mr-text-dim, #8a8998); margin-bottom: 12px;">
                                <i class="fa fa-mobile" style="margin-right: 4px;"></i> Mobile Wallets
                            </div>
                            <div style="display: flex; gap: 8px;">
                                <a href="https://apps.apple.com/us/app/martianrepublic/id6480416861" target="_blank" rel="noopener"
                                   style="flex: 1; display: flex; align-items: center; gap: 8px; padding: 10px 12px; background: var(--mr-dark, #0c0c16); border: 1px solid var(--mr-border-bright, rgba(255,255,255,0.1)); border-radius: 6px; text-decoration: none; transition: border-color 0.2s;">
                                    <i class="fa-brands fa-apple" style="font-size: 18px; color: #fff;"></i>
                                    <div>
                                        <div style="font-family: 'JetBrains Mono', monospace; font-size: 8px; color: var(--mr-text-faint, #5a5968);">Download on</div>
                                        <div style="font-family: 'Orbitron', sans-serif; font-size: 10px; font-weight: 600; color: #fff;">App Store</div>
                                    </div>
                                </a>
                                <a href="https://play.google.com/store/apps/details?id=io.bytewallet.bytewallet" target="_blank" rel="noopener"
                                   style="flex: 1; display: flex; align-items: center; gap: 8px; padding: 10px 12px; background: var(--mr-dark, #0c0c16); border: 1px solid var(--mr-border-bright, rgba(255,255,255,0.1)); border-radius: 6px; text-decoration: none; transition: border-color 0.2s;">
                                    <i class="fa-brands fa-google-play" style="font-size: 16px; color: var(--mr-green, #34d399);"></i>
                                    <div>
                                        <div style="font-family: 'JetBrains Mono', monospace; font-size: 8px; color: var(--mr-text-faint, #5a5968);">Get it on</div>
                                        <div style="font-family: 'Orbitron', sans-serif; font-size: 10px; font-weight: 600; color: #fff;">Google Play</div>
                                    </div>
                                </a>
                            </div>
                        </div>


                    </div>

                    <div class="col-md-5 col-sm-5">

                        {{-- HD Wallet Balance with address discovery --}}
                        <div class="bridge-card portlet" id="hd-balance-portlet">
                            <h3 class="bridge-card-title portlet-title"><i class="fa fa-layer-group" style="margin-right: 6px; color: var(--mr-cyan);"></i> HD Addresses</h3>
                            <div class="portlet-body">
                                <h2 id="hd-total-balance" style="font-family: 'Orbitron', sans-serif; font-size: 24px; font-weight: 700; color: #fff; margin: 0 0 12px;">
                                    <i class="fa fa-spinner fa-spin" style="color: var(--mr-text-dim);"></i> <span style="font-family: 'JetBrains Mono', monospace; font-size: 12px; color: var(--mr-text-dim);">Discovering addresses...</span>
                                </h2>
                                <div id="hd-address-list" style="margin-top: 12px; font-size: 12px; display: none;"></div>
                                <a href="javascript:;" id="hd-toggle-addresses" style="font-family: 'JetBrains Mono', monospace; font-size: 10px; color: var(--mr-cyan, #00e4ff); display: none; margin-top: 8px;">
                                    <i class="fa fa-chevron-down" style="margin-right: 4px; font-size: 8px;"></i> Show all addresses
                                </a>
                            </div>
                        </div>

                        <div id="receive-section" class="bridge-card portlet">
                            <h3 class="bridge-card-title portlet-title"><i class="fa fa-qrcode" style="margin-right: 6px; color: var(--mr-green);"></i> Receive Marscoin</h3>
                            <div class="portlet-body">
                                <div class="pub-addr">
                                    <h3 class="pub-addr-text"><a href="https://explore.marscoin.org/address/{{ $public_addr }}" target="_blank">{{ $public_addr }}</a></h3>
                                    <i id="copy-addr" class="fa fa-copy copy-icon"></i>
                                </div>
                                <div style="margin-top: 16px; display: flex; justify-content: center;">
                                    <div class="mars-qr-frame">
                                        <div class="mars-qr-corner tl"></div>
                                        <div class="mars-qr-corner tr"></div>
                                        <div class="mars-qr-corner bl"></div>
                                        <div class="mars-qr-corner br"></div>
                                        <img id="qrious" height="180" width="180">
                                        <div class="mars-qr-label">SCAN TO RECEIVE</div>
                                    </div>
                                </div>
                                <style>
                                    .mars-qr-frame {
                                        position: relative;
                                        display: inline-block;
                                        padding: 16px;
                                        background: var(--mr-dark, #0c0c16);
                                        border: 1px solid rgba(200,65,37,0.2);
                                        border-radius: 10px;
                                    }
                                    .mars-qr-frame img {
                                        display: block;
                                        border-radius: 4px;
                                    }
                                    .mars-qr-corner {
                                        position: absolute;
                                        width: 16px;
                                        height: 16px;
                                        border-color: var(--mr-mars, #c84125);
                                        border-style: solid;
                                        border-width: 0;
                                    }
                                    .mars-qr-corner.tl { top: 6px; left: 6px; border-top-width: 2px; border-left-width: 2px; border-top-left-radius: 4px; }
                                    .mars-qr-corner.tr { top: 6px; right: 6px; border-top-width: 2px; border-right-width: 2px; border-top-right-radius: 4px; }
                                    .mars-qr-corner.bl { bottom: 24px; left: 6px; border-bottom-width: 2px; border-left-width: 2px; border-bottom-left-radius: 4px; }
                                    .mars-qr-corner.br { bottom: 24px; right: 6px; border-bottom-width: 2px; border-right-width: 2px; border-bottom-right-radius: 4px; }
                                    .mars-qr-label {
                                        font-family: 'JetBrains Mono', monospace;
                                        font-size: 7px;
                                        letter-spacing: 3px;
                                        text-transform: uppercase;
                                        color: var(--mr-text-faint, #5a5968);
                                        text-align: center;
                                        margin-top: 10px;
                                    }
                                </style>
                            </div>
                        </div>

                        <div class="bridge-card portlet">
                            <h3 class="bridge-card-title portlet-title"><i class="fa fa-shield-halved" style="margin-right: 6px; color: var(--mr-cyan);"></i> Security</h3>
                            <div class="portlet-body">
                                <div class="bridge-security-grid">
                                    <a class="bridge-sec-btn" href="/wallet/dashboard/hd-close">
                                        <i class="fa fa-lock" style="color: var(--mr-text-dim);"></i>
                                        <div>
                                            <div class="sec-label">Lock Wallet</div>
                                            <div class="sec-desc">Disconnect & clear keys</div>
                                        </div>
                                    </a>
                                    <a class="bridge-sec-btn" href="javascript:void(0)" onclick="onDownloadWallet()">
                                        <i class="fa fa-download" style="color: var(--mr-cyan);"></i>
                                        <div>
                                            <div class="sec-label">Export Backup</div>
                                            <div class="sec-desc">Download encrypted keyfile</div>
                                        </div>
                                    </a>
                                    <a class="bridge-sec-btn" href="javascript:void(0)" onclick="showSeedPhrase()">
                                        <i class="fa fa-eye" style="color: var(--mr-mars);"></i>
                                        <div>
                                            <div class="sec-label">View Seed</div>
                                            <div class="sec-desc">Show recovery phrase</div>
                                        </div>
                                    </a>
                                    <a class="bridge-sec-btn" href="javascript:void(0)" onclick="createNewBackup()">
                                        <i class="fa fa-key" style="color: var(--mr-green);"></i>
                                        <div>
                                            <div class="sec-label">New Password</div>
                                            <div class="sec-desc">Re-encrypt with new key</div>
                                        </div>
                                    </a>
                                </div>
                                <div style="margin-top: 14px; font-family: 'JetBrains Mono', monospace; font-size: 9px; color: var(--mr-text-faint, #5a5968); line-height: 1.8;">
                                    <div><i class="fa fa-check-circle" style="color: var(--mr-green, #34d399); margin-right: 4px;"></i> Encrypted backup stored on server</div>
                                    <div><i class="fa fa-check-circle" style="color: var(--mr-green, #34d399); margin-right: 4px;"></i> PBKDF2-SHA512 (100,000 rounds)</div>
                                </div>
                            </div>
                        </div>
                  

                    </div>



                </div>
                {{-- <div class="row">

                    <div class="col-md-12 col-sm-5">
                        <div class="portlet">

                            <h3 class="portlet-title">
                                <u>Transaction History</u>
                            </h3>

                            <div class="portlet-body">


                                <table class="table table-striped table-bordered " id="table-1"
                                    aria-describedby="table-1_info">
                                    <thead>
                                        <tr role="row">
                                            <th style="width: 250px;" class="sorting_asc" role="columnheader"
                                                tabindex="0" aria-controls="table-1" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Browser: activate to sort column descending">Transaction
                                            </th>

                                            <th style="width: 250px;" class="text-center sorting" role="columnheader"
                                                tabindex="0" aria-controls="table-1" rowspan="1" colspan="1"
                                                aria-label="CSS grade: activate to sort column ascending">Sender</th>
                                            <th style="width: 110px;" class="sorting" role="columnheader"
                                                tabindex="0" aria-controls="table-1" rowspan="1" colspan="1"
                                                aria-label="Platform(s): activate to sort column ascending">MARS
                                            </th>
                                            <th style="width: 110px;" class="text-center sorting" role="columnheader"
                                                tabindex="0" aria-controls="table-1" rowspan="1" colspan="1"
                                                aria-label="Engine version: activate to sort column ascending">USD</th>
                                            <th style="width: 110px;" class="sorting" role="columnheader"
                                                tabindex="0" aria-controls="table-1" rowspan="1" colspan="1"
                                                aria-label="Rendering engine: activate to sort column ascending">
                                                Date</th>


                                        </tr>
                                    </thead>


                                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                                        <tr class="odd">
                                            <td valign="top" colspan="5" class="">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>


                            </div>

                        </div>


                    </div>



                </div> --}}


            </div>


            <div id="basicModal" class="modal fade" aria-hidden="true" data-backdrop="static" data-keyboard="false"  style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <div style="display: flex; align-itmes: center; justify-content: center">

                                <h3 class="modal-title">Confirm Transaction</h3>

                            </div>
                        </div> <!-- /.modal-header -->

                        <div class="modal-body ">
                            <div class="row confirm-transaction">
                                <div class="col-lg-12 from">
                                    From:
                                    <h4 id="#fullname">
                                        {{ $fullname }}
                                    </h4>
                                </div>

                            </div>
                            <div class="row confirm-transaction">
                                <div class="col-lg-12 to">
                                    To:
                                    <h4 class="destination-address-modal" id="#destination-address">

                                    </h4>
                                </div>
                            </div>
                            <div class="row confirm-transaction">
                                <div class="col-lg-12 to">
                                    Estimated Fee:
                                    <h4 class="estimated-fee">
                                        N/A
                                    </h4>
                                </div>
                            </div>
                            <div class="row confirm-transaction">
                                <div class="col-lg-12 amount-modal">
                                    Total:
                                    <div style="display: flex; flex-direction: row" class="converted-value">
                                        <h3 class="symbol">$</h3>
                                        <h3 class="conversion-rate" id="conversion-rate">0.00</h3>
                                        <h5 class="currency-abbr">USD</h5>
                                    </div>
                                </div>
                            </div>

                            <div class="row" style="padding: 20px">


                                {{-- <input name="wallet" hidden id="selected_wallet" /> --}}

                                {{-- Password removed - wallet is already unlocked for this session --}}
                                <input type="hidden" id="unlock-password-tx" name="unlock-password-tx" value="session">
                                <div style="font-family: 'JetBrains Mono', monospace; font-size: 10px; color: var(--mr-text-faint, #5a5968); text-align: center; padding: 8px 0;">
                                    <i class="fa fa-shield-halved" style="color: var(--mr-green, #34d399); margin-right: 4px;"></i> Wallet unlocked — ready to sign
                                </div>
                                <p class="error-unlocking-tx"></p>


                            </div>
                        </div> <!-- /.modal-body -->

                        <div class="modal-footer"
                            style="display: flex; align-itmes: center; justify-content: center;">
                            <button type="button" class="btn-lg btn-primary" id="send-mars">Send MARS</button>



                            <img src="/assets/citizen/loading.gif" alt="enter image description here"
                                style="display: none" id="loading">
                            <div class="success-message" style="display: none">
                                <i class="fa fa-check" style="color: rgb(33, 192, 33)"></i>
                                <a class='transaction-hash-link' href="" target="_blank">
                                    <h5 class="transaction-hash">
                                    </h5>
                                </a>
                            </div>
                        </div> <!-- /.modal-footer -->

                    </div> <!-- /.modal-content -->

                </div><!-- /.modal-dialog -->

            </div>
            <!---- THE BASIC MODAL IN ACTION!!!!!!!!!!!! -->
            <!-------------------------------------------------------------------------->






        </div> <!-- .content -->

    </div> <!-- /#wrapper -->



    <div class="modal scan-popup" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div id="app">
                    <div class="sidebar">
                    </div>
                    <canvas hidden="" style="height: 100%; width: 100%; padding: 5px;" id="qr-canvas"></canvas>

                </div>
            </div>
        </div>
    </div>





    <!--------------------------------------->
    <!------------- UNLOCK WALLET ----------->
    <div id="unlockWalletModal" class="modal modal-styled fade">
        <div class="modal-dialog">

            <div class="modal-content">


                <div class="modal-header">
                    <h3 class="modal-title">Unlock Wallet</h3>
                </div> <!-- /.modal-header -->

                {{-- <form class="form account-form " id="wallet-unlocker" method="POST"
                    action="/wallet/dashboard/hd-open">
                    @csrf --}}

                <div class=""
                    style="padding: 5rem; display: flex; justify-content: center; align-items: center; flex-direction: column">
                    <div class="row">

                        <h4 class="unlock-name" style="text-align: center">Input Password to Export Wallet</h4>
                        <h2 class="unlock-addy"></h2>
                    </div>


                    <div class="row" style="width: 50%;">


                        <input name="wallet" hidden id="selected_wallet" />

                        <label for="name">Wallet Password</label>
                        <input type="password" id="unlock-password" name="unlock-password" class="form-control"
                            data-required="true" style="width: 100%">

                        <p class="error-unlocking"></p>

                        <div class="row d-flex justify-content-center text-center" style="padding-top: 5rem;">

                            <button id="unlock-wallet" type="submit" class="btn btn-primary" style=""
                                onclick="onDownloadWallet()">Unlock</button>
                        </div>
                    </div>

                </div>
                {{-- </form> --}}

            </div>

        </div>






    </div>

    <footer class="footer">
        @include('footer')
    </footer>
    <script src="/assets/wallet/js/dist/my_bundle.js"></script>
    <script src="/assets/wallet/js/libs/jquery-1.10.2.min.js"></script>
    <script src="/assets/wallet/js/libs/bootstrap.min.js"></script>
    <script src="/assets/wallet/js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="/assets/wallet/js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script src="/assets/wallet/js/mvpready-core.js"></script>
    <script src="/assets/wallet/js/mvpready-admin.js"></script>
    <script src="/assets/wallet/js/demos/table_demo.js"></script>
    <script src="/assets/wallet/js/plugins/scan/qrcode.min.js"></script>
    <script type="text/javascript">
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
    });  
    function dismissAlert(type) {
        $.ajax({
            url: '/api/dismiss',
            type: 'POST',
            data: {
                alertType: type
            },
            success: function() {
                console.log('Alert dismissed');
            },
            error: function() {
                console.log('Error dismissing alert');
            }
        });
    }

        // ============================================================
        // HD WALLET ADDRESS DISCOVERY
        // Scans BIP44 derivation paths to find all addresses with balance
        // ============================================================
        async function discoverHDAddresses(mnemonic) {
            // Server-side discovery via pebas (fast, single API call)
            // Pebas now uses bitcoinjs-lib bip32 (same as client) - one source of truth
            const seed = my_bundle.bip39.mnemonicToSeedSync(mnemonic.trim());
            const root = my_bundle.bitcoin.bip32.fromSeed(seed, Marscoin.mainnet);
            const xpub = root.derivePath("m/44'/2'/0'").neutered().toBase58();

            try {
                const resp = await $.ajax({
                    url: '/api/discover',
                    type: 'POST',
                    data: { xpub: xpub, gap_limit: 20 },
                });

                if (resp.error) throw new Error(resp.error);

                var discovered = resp.addresses || [];
                var totalBal = resp.totalBalance || 0;

                // Check legacy/stored wallet address (may have been created by older code)
                // This catches addresses not in the standard HD derivation tree
                var storedAddr = '{{ $public_addr }}'.trim();
                var civicAddr = '{{ $civic_addr ?? "" }}'.trim();
                var knownAddrs = discovered.map(function(a) { return a.address; });

                // Check stored HD wallet address
                if (storedAddr && knownAddrs.indexOf(storedAddr) === -1) {
                    try {
                        var balResp = await $.ajax({ url: '/api/balance/' + storedAddr, type: 'GET' });
                        var bal = parseFloat(balResp.balance || 0);
                        if (bal > 0) {
                            discovered.push({
                                address: storedAddr, balance: bal, unconfirmed: 0,
                                chain: 'legacy', index: 0, path: 'legacy/stored',
                            });
                            totalBal += bal;
                            console.log("Added legacy stored address:", storedAddr, bal, "MARS");
                        }
                    } catch(e) { console.log("Could not check stored address:", e.message); }
                }

                // Check civic wallet address (if different from stored and not in HD tree)
                if (civicAddr && civicAddr !== storedAddr && knownAddrs.indexOf(civicAddr) === -1) {
                    try {
                        var civicBalResp = await $.ajax({ url: '/api/balance/' + civicAddr, type: 'GET' });
                        var civicBal = parseFloat(civicBalResp.balance || 0);
                        if (civicBal > 0) {
                            discovered.push({
                                address: civicAddr, balance: civicBal, unconfirmed: 0,
                                chain: 'civic', index: 0, path: 'civic',
                            });
                            // Don't add to totalBal if already shown in civic status bar
                        }
                    } catch(e) { /* civic addr might not be trackable */ }
                }

                return {
                    discovered: discovered,
                    totalBalance: totalBal,
                    totalUnconfirmed: resp.totalUnconfirmed || 0,
                    xpub: xpub,
                };
            } catch (serverErr) {
                console.warn("Server-side discovery failed, falling back to client-side:", serverErr);
                return await discoverHDAddressesClientSide(mnemonic);
            }
        }

        // Fallback client-side discovery (40+ API calls)
        async function discoverHDAddressesClientSide(mnemonic) {
            const GAP_LIMIT = 20;
            const seed = my_bundle.bip39.mnemonicToSeedSync(mnemonic.trim());
            const root = my_bundle.bitcoin.bip32.fromSeed(seed, Marscoin.mainnet);
            const account = root.derivePath("m/44'/2'/0'").neutered();

            const discovered = [];
            let totalBalance = 0;

            for (let chain = 0; chain <= 1; chain++) {
                const chainNode = account.derive(chain);
                let consecutiveEmpty = 0;

                for (let index = 0; consecutiveEmpty < GAP_LIMIT; index++) {
                    const childNode = chainNode.derive(index);
                    const address = my_bundle.bitcoin.payments.p2pkh({
                        pubkey: childNode.publicKey,
                        network: Marscoin.mainnet,
                    }).address;

                    try {
                        const resp = await fetch(`/api/balance/${address}`);
                        const data = await resp.json();
                        const balance = parseFloat(data.balance) || 0;

                        if (balance > 0) {
                            discovered.push({ address, balance, chain: chain === 0 ? 'receiving' : 'change', index, path: `m/44'/2'/0'/${chain}/${index}` });
                            totalBalance += balance;
                            consecutiveEmpty = 0;
                            $('#hd-total-balance').text(totalBalance.toFixed(8) + ' MARS');
                        } else {
                            consecutiveEmpty++;
                        }
                    } catch (err) {
                        consecutiveEmpty++;
                    }
                }
            }

            return { discovered, totalBalance };
        }

        function renderAddressDiscovery(result) {
            const { discovered, totalBalance } = result;
            const totalUnconfirmed = result.totalUnconfirmed || 0;
            const civicAddr = '{{ $civic_addr ?? "" }}';

            // Show balance with pending indicator
            var balanceHtml = totalBalance.toFixed(8) + ' MARS';
            if (totalUnconfirmed > 0) {
                balanceHtml += '<div style="font-family:JetBrains Mono,monospace;font-size:11px;color:var(--mr-text-dim,#8a8998);margin-top:4px;">' +
                    '<i class="fa fa-clock" style="color:#f59e0b;margin-right:4px;"></i>' +
                    '<span style="color:#f59e0b;">' + totalUnconfirmed.toFixed(4) + ' MARS pending</span></div>';
            }
            $('#hd-total-balance').html(balanceHtml);

            if (discovered.length > 0) {
                // Include addresses with balance OR unconfirmed amounts
                const withBalance = discovered.filter(a => a.balance > 0 || (a.unconfirmed && a.unconfirmed > 0));
                // Sort: civic address first, then by balance descending
                withBalance.sort((a, b) => {
                    if (a.address === civicAddr) return -1;
                    if (b.address === civicAddr) return 1;
                    return b.balance - a.balance;
                });

                const html = withBalance.map(a => {
                    const isCivic = civicAddr && a.address === civicAddr;
                    const isLegacy = a.chain === 'legacy';
                    let badge = '';
                    if (isCivic) {
                        badge = '<span style="font-family:JetBrains Mono,monospace;font-size:7px;letter-spacing:1px;text-transform:uppercase;padding:2px 5px;border-radius:3px;background:rgba(200,65,37,0.15);color:var(--mr-mars,#c84125);border:1px solid rgba(200,65,37,0.25);margin-left:6px;vertical-align:middle;">Civic ID</span>';
                    } else if (isLegacy) {
                        badge = '<span style="font-family:JetBrains Mono,monospace;font-size:7px;letter-spacing:1px;text-transform:uppercase;padding:2px 5px;border-radius:3px;background:rgba(245,158,11,0.15);color:#f59e0b;border:1px solid rgba(245,158,11,0.25);margin-left:6px;vertical-align:middle;">Legacy</span>';
                    }
                    const highlighted = isCivic || isLegacy;
                    const borderColor = isCivic ? 'rgba(200,65,37,0.15)' : (isLegacy ? 'rgba(245,158,11,0.12)' : 'var(--mr-border, rgba(255,255,255,0.04))');
                    const bgColor = isCivic ? 'rgba(200,65,37,0.04)' : (isLegacy ? 'rgba(245,158,11,0.03)' : 'transparent');
                    const chainIcon = isLegacy ? '⚡' : (a.chain === 'change' ? '↳' : '→');

                    return `<div style="display:flex;justify-content:space-between;align-items:center;padding:6px 8px;margin-bottom:2px;border-radius:4px;border-bottom:1px solid ${borderColor};background:${bgColor};">
                        <span style="display:flex;align-items:center;gap:4px;min-width:0;">
                            <span style="color:var(--mr-text-faint);font-size:10px;">${chainIcon}</span>
                            <a href="https://explore.marscoin.org/address/${a.address}" target="_blank"
                               style="color:var(--mr-cyan,#00e4ff);font-family:'JetBrains Mono',monospace;font-size:11px;text-decoration:none;">${a.address.substring(0, 12)}...${a.address.substring(a.address.length - 6)}</a>
                            ${badge}
                        </span>
                        <span style="font-family:'JetBrains Mono',monospace;font-size:11px;font-weight:500;white-space:nowrap;margin-left:8px;">
                            <span style="color:var(--mr-text);">${a.balance.toFixed(4)} MARS</span>
                            ${a.unconfirmed && a.unconfirmed > 0 ? '<span style="color:#f59e0b;font-size:9px;margin-left:4px;" title="Pending confirmation"><i class="fa fa-clock"></i> +' + a.unconfirmed.toFixed(4) + '</span>' : ''}
                        </span>
                    </div>`;
                }).join('');

                $('#hd-address-list').html(html).show();
                if (withBalance.length > 1) {
                    $('#hd-toggle-addresses')
                        .html('<i class="fa fa-chevron-up" style="margin-right:4px;font-size:8px;"></i> Hide addresses')
                        .show()
                        .click(function() {
                            $('#hd-address-list').toggle();
                            $(this).html($('#hd-address-list').is(':visible')
                                ? '<i class="fa fa-chevron-up" style="margin-right:4px;font-size:8px;"></i> Hide addresses'
                                : '<i class="fa fa-chevron-down" style="margin-right:4px;font-size:8px;"></i> Show all addresses');
                        });
                }

                console.log(`HD Discovery: found ${discovered.length} addresses, ${withBalance.length} with balance, total: ${totalBalance}`);

                // Trigger Recent Activity loading with discovered addresses
                var allAddrs = withBalance.map(function(a) { return a.address; });
                window._walletDiscoveredAddresses = allAddrs;
                // Store address-to-path mapping for transaction signing
                window._walletAddressMap = {};
                discovered.forEach(function(a) {
                    window._walletAddressMap[a.address] = { chain: a.chain === 'change' ? 1 : 0, index: a.index };
                });
                if (typeof loadRecentTxs === 'function') { loadRecentTxs(allAddrs); }
            } else {
                $('#hd-total-balance').text('0.0000 MARS');
            }
        }

        $(document).ready(function() {
            const unlockedWallet = WalletKey.get()

            if(!unlockedWallet)
            {
                console.log("No Wallet Found.")
                window.location.replace("hd?key=none");
            }
            else {
                console.log("Wallet found. Starting HD address discovery...");
                // Kick off HD address scan in background
                discoverHDAddresses(unlockedWallet).then(result => {
                    renderAddressDiscovery(result);

                    // Update the nav balance widget with HD total
                    if (result.totalBalance > 0) {
                        $('.nav-wallet-balance').html(
                            '<img src="/assets/wallet/img/marscoin-350x350.png" width="20" height="20" /> ' +
                            result.totalBalance.toFixed(4) + ' MARS'
                        );
                        // Also show the wallet-is-open state if it was showing not-open
                        $('.wallet-is-not-open').hide();
                        $('.wallet-is-open').show();
                    }

                    // Check if any discovered address matches the user's civic wallet
                    const civicAddr = "{{ $civic_addr ?? '' }}";
                    if (civicAddr && result.discovered) {
                        const matchesCivic = result.discovered.find(a => a.address === civicAddr);
                        if (matchesCivic) {
                            $.post('/api/link-civic', { address: civicAddr })
                            .done(function(data) {
                                console.log("Civic wallet linked:", data);
                                if (typeof toastr !== 'undefined') {
                                    toastr.success('Civic wallet connected. All features unlocked.', '', {timeOut: 4000});
                                }
                            })
                            .fail(function(err) {
                                console.warn("Failed to link civic wallet:", err);
                            });
                        }
                    }
                }).catch(err => {
                    console.error("HD discovery failed:", err);
                    $('#hd-total-balance').text('{{ $balance ?? "0" }} MARS');
                });
            }


            let cur_currency = "MARS";
            var mars_price = 0;

            // Fetch MARS price from price.marscoin.org (via same-origin proxy)
            fetch('/api/mars-price')
                .then(function(r) { return r.json(); })
                .then(function(d) {
                    var q = d.data && d.data['154'] && d.data['154'].quote && d.data['154'].quote.USD;
                    if (q && q.price) {
                        mars_price = parseFloat(q.price);
                        console.log("Mars price: $" + mars_price);
                        $('.connectivity').hide();
                    }
                })
                .catch(function() {
                    console.log('Error fetching Mars price');
                    $('.connectivity').show();
                });

            const decryptWallet = (seed) => {
                //  console.log(seed)


            }
            const encrypted_seed = "{{ $encrypted_seed }}"
            decryptWallet(encrypted_seed)

            $(".input-placeholder").on('input', (e) => {

                var conversion = convertRate(e)

                $('.conversion-rate').text(conversion)
            })

            function convertRate(e) {

                if (cur_currency == "USD") {
                    var conversion = Math.round((e.target.value / mars_price) * 100) / 100;
                } else if (cur_currency == "MARS") {
                    var conversion = Math.round((e.target.value * mars_price) * 100) / 100;
                }
                return conversion
            }


            $('.exchange').click(() => {
                toggleCurrency()
            })

            function toggleCurrency() {
                if (cur_currency == "USD") {

                    $('.currency-abbr').html(cur_currency)


                    var conversion = Math.round(($('.input-placeholder').val() * mars_price) * 100) / 100;

                    $('.conversion-rate').text(conversion)


                    cur_currency = "MARS";

                    $('.amount-to-send').html(cur_currency)
                    $('.input-placeholder').attr("placeholder", cur_currency)
                    $('.symbol').html('$')



                } else if (cur_currency == "MARS") {
                    $('.currency-abbr').html(cur_currency)

                    var conversion = Math.round(($('.input-placeholder').val() / mars_price) * 100) / 100;

                    $('.conversion-rate').text(conversion)



                    cur_currency = "USD"
                    $('.amount-to-send').html(cur_currency)
                    $('.input-placeholder').attr("placeholder", cur_currency)
                    $('.symbol').html('')


                }

            }

            $(".copy-icon").click(() => {
                copyClipboard()
            })

            function copyClipboard() {
                /* Get the text field */
                var copyText = $(".pub-addr-text")

                //  console.log(copyText.text())

                /* Select the text field */
                copyText.select();


                /* Copy the text inside the text field */
                navigator.clipboard.writeText(copyText.text());

                /* Alert the copied text */
                //alert("Copied the text: " + copyText.text());
                $("#copy-addr").toggleClass("fa-copy fa-check", "fa-copy");

                toastr.options = {
                "positionClass": "toast-bottom-right",
                "timeOut": "3000",
                }
                toastr.success('Address copied to clipboard');

            }

            // GEN TRANSACTION HEX....

            //=================================================================
            //=================================================================

            const Marscoin = {
                mainnet: {
                    messagePrefix: "\x19Marscoin Signed Message:\n",
                    bech32: "M",
                    bip44: 2,
                    bip32: {
                        public: 0x043587cf,
                        private: 0x04358394,
                    },
                    pubKeyHash: 0x32,
                    scriptHash: 0x32,
                    wif: 0x80,
                }
            };

          

            // Get Seed given menmonic
            function getSeed(mnemonic) {
                const seed = my_bundle.bip39.mnemonicToSeedSync(mnemonic);

                return seed

            }

            // Get Public key given mnemonic
            function getXpub(mnemonic) {
                //console.log("==== [MARS] GetXPub()");

                const seed = getSeed(mnemonic.trim());
                const root = my_bundle.bitcoin.bip32.fromSeed(seed, Marscoin.mainnet);

                const path = getDerivationPath();
                const child = root.derivePath(path).neutered();
                const xpub = child.toBase58();

                return xpub;
            }

            // public address
            function getDerivationPath() {
                return "m/44'/2'/0'";
            }


            function getAddress(xpub) {
                const hdNode = HDNode.fromBase58(xpub, Marscoin.mainnet);
                const node0 = hdNode.derive(node);
                address = nodeToLegacyAddress(_node0.derive(index));

            }

            function nodeToLegacyAddress(hdNode) {
                return my_bundle.bitcoin.payments.p2pkh({
                    pubkey: hdNode.publicKey,
                    network: Marscoin.mainnet,
                }).address;
            }


            //
            // Send button is clicked....
            let tx_i_o;

            $("#send-preconfirm").click(async (e) => {
                e.preventDefault();
                $(".destination-address-modal").text($(".destination-address").val())
                let addy = $(".destination-address-modal").text()
                let amount = $(".input-placeholder").val()
                let m = addy.charAt(0) === "M"
                let len = addy.length === 34
                let pa = "{{ $public_addr }}";

                // Validate inputs
                if (!addy || !m || !len) {
                    $("#address-error").show();
                    return;
                }
                if (!amount || parseFloat(amount) <= 0) {
                    $("#amount-error").show();
                    return;
                }
                $("#address-error, #amount-error, #insufficient-error").hide();

                // Show loading state on button
                var origBtnHtml = $("#send-preconfirm").html();
                $("#send-preconfirm").html('<i class="fa fa-spinner fa-spin"></i> Preparing...').prop('disabled', true);

                // Get fee and UTXO data
                var mars_amount;
                var receiver_address = $(".destination-address").val();
                if (cur_currency == "MARS") {
                    mars_amount = $(".input-placeholder").val();
                } else if (cur_currency == "USD") {
                    mars_amount = $(".conversion-rate").text();
                }

                try {
                    const io = await sendMARS(mars_amount, receiver_address);
                    const fee = marsConvert(io.fee);
                    console.log("THE FEE: ", fee);

                    const total_amount = fee + parseFloat(mars_amount);
                    $(".estimated-fee").text(fee.toFixed(6) + " MARS");
                    // Don't overwrite the conversion-rate used for currency display
                    $(".amount-modal .conversion-rate, #conversion-rate").text("$" + (total_amount * mars_price).toFixed(4) + " USD");

                    // Restore button
                    $("#send-preconfirm").html(origBtnHtml).prop('disabled', false);

                    // Open modal programmatically (fixes double-click bug)
                    $('#basicModal').modal('show');

                    // Unbind previous click handlers to prevent stacking
                    $("#send-mars").off('click').on('click', async () => {

                        const unlockedWallet = WalletKey.get().trim();

                        if (unlockedWallet) {
                            console.log("successfully unlocked..")

                            $("#send-mars").hide()
                            $("#loading").show()

                            try {
                                const tx = await signMARS(mars_amount, io, unlockedWallet)
                                $("#loading").hide()

                                // Beautiful success state - replace entire modal body
                                $("#basicModal .modal-body").html(`
                                    <div style="text-align: center; padding: 32px 20px;">
                                        {{-- Animated blockchain blocks --}}
                                        <div class="tx-chain" style="display:flex;align-items:center;justify-content:center;gap:0;margin:0 auto 24px;overflow:hidden;">
                                            <div class="tx-block" style="width:36px;height:36px;border-radius:6px;background:var(--mr-surface-raised,#1a1a2a);border:1.5px solid var(--mr-green,#34d399);display:flex;align-items:center;justify-content:center;animation:blockSlide 0.6s ease-out 0.2s both;">
                                                <i class="fa fa-cube" style="font-size:14px;color:var(--mr-green,#34d399);"></i>
                                            </div>
                                            <div style="width:20px;height:2px;background:var(--mr-green,#34d399);animation:chainGrow 0.3s ease-out 0.7s both;"></div>
                                            <div class="tx-block" style="width:36px;height:36px;border-radius:6px;background:var(--mr-surface-raised,#1a1a2a);border:1.5px solid var(--mr-cyan,#00e4ff);display:flex;align-items:center;justify-content:center;animation:blockSlide 0.6s ease-out 0.9s both;">
                                                <i class="fa fa-cube" style="font-size:14px;color:var(--mr-cyan,#00e4ff);"></i>
                                            </div>
                                            <div style="width:20px;height:2px;background:var(--mr-cyan,#00e4ff);animation:chainGrow 0.3s ease-out 1.4s both;"></div>
                                            <div class="tx-block" style="width:44px;height:44px;border-radius:8px;background:linear-gradient(135deg,rgba(200,65,37,0.2),rgba(200,65,37,0.05));border:2px solid var(--mr-mars,#c84125);display:flex;align-items:center;justify-content:center;animation:blockSlide 0.6s ease-out 1.6s both;box-shadow:0 0 16px rgba(200,65,37,0.2);">
                                                <i class="fa fa-check" style="font-size:18px;color:var(--mr-mars,#c84125);"></i>
                                            </div>
                                            <div style="width:20px;height:2px;background:var(--mr-text-faint,#5a5968);animation:chainGrow 0.3s ease-out 2.1s both;opacity:0.4;"></div>
                                            <div class="tx-block" style="width:36px;height:36px;border-radius:6px;border:1.5px dashed var(--mr-text-faint,#5a5968);display:flex;align-items:center;justify-content:center;animation:blockSlide 0.6s ease-out 2.3s both;opacity:0.3;">
                                                <i class="fa fa-cube" style="font-size:14px;color:var(--mr-text-faint,#5a5968);"></i>
                                            </div>
                                        </div>
                                        <div style="font-family: 'Orbitron', sans-serif; font-size: 16px; font-weight: 700; color: var(--mr-green, #34d399); letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 10px; animation: successPulse 0.5s ease-out 2.5s both;">
                                            Transaction Broadcast
                                        </div>
                                        <div style="font-family: 'JetBrains Mono', monospace; font-size: 13px; color: #fff; margin-bottom: 6px;">
                                            ${mars_amount} MARS sent
                                        </div>
                                        <div style="font-family: 'JetBrains Mono', monospace; font-size: 10px; color: var(--mr-text-faint, #5a5968); margin-bottom: 20px;">
                                            Waiting for block confirmation (~2 min)
                                        </div>
                                        <a href="https://explore.marscoin.org/tx/${tx.tx_hash}" target="_blank"
                                           style="display: inline-block; font-family: 'JetBrains Mono', monospace; font-size: 10px; color: var(--mr-cyan, #00e4ff); background: var(--mr-dark, #0c0c16); padding: 12px 18px; border-radius: 8px; border: 1px solid rgba(0,228,255,0.2); text-decoration: none; word-break: break-all; max-width: 100%; transition: border-color 0.2s;">
                                            <i class="fa fa-cube" style="margin-right: 6px;"></i>${tx.tx_hash.substring(0, 24)}...
                                        </a>
                                        <div style="margin-top: 24px;">
                                            <button onclick="location.reload()" style="
                                                font-family: 'JetBrains Mono', monospace; font-size: 11px; font-weight: 500;
                                                letter-spacing: 1.5px; text-transform: uppercase; padding: 12px 28px;
                                                border-radius: 8px; border: none; cursor: pointer;
                                                background: var(--mr-mars, #c84125); color: #fff; transition: all 0.2s;
                                            " onmouseover="this.style.background='#d94e30'" onmouseout="this.style.background='var(--mr-mars, #c84125)'">
                                                <i class="fa fa-arrow-left" style="margin-right: 6px;"></i> Back to Wallet
                                            </button>
                                        </div>
                                    </div>
                                    <style>
                                        @keyframes successPulse {
                                            0% { transform: scale(0.5); opacity: 0; }
                                            50% { transform: scale(1.1); }
                                            100% { transform: scale(1); opacity: 1; }
                                        }
                                        @keyframes blockSlide {
                                            0% { transform: translateX(-20px) scale(0.7); opacity: 0; }
                                            60% { transform: translateX(3px) scale(1.05); }
                                            100% { transform: translateX(0) scale(1); opacity: 1; }
                                        }
                                        @keyframes chainGrow {
                                            0% { transform: scaleX(0); opacity: 0; }
                                            100% { transform: scaleX(1); opacity: 1; }
                                        }
                                    </style>
                                `);
                                // Hide footer, update title
                                $("#basicModal .modal-footer").hide();
                                $("#basicModal .modal-title").text("Transaction Complete");



                        } catch (e) {
                            handleError("unable to sign")
                            throw e;
                        }

                    } else {
                        $(".error-unlocking-tx").text("Wallet key not found — please re-unlock your wallet")
                        $(".error-unlocking-tx").css('color', 'var(--mr-mars, #c84125)')
                    }

                    });

                } catch (err) {
                    console.error("Send preparation failed:", err);
                    $("#send-preconfirm").html(origBtnHtml).prop('disabled', false);
                    alert("Could not prepare transaction. Please check the address and try again.");
                }

            })

            $('#basicModal').on('hidden.bs.modal', function() {
                location.reload();
            })


            const sendMARS = async (mars_amount, receiver_address) => {

                // Use client-side discovered addresses (derived with same BIP32 as genSeed)
                const allAddresses = window._walletDiscoveredAddresses || [];
                const sender_address = "{{ $public_addr }}".trim();

                // Try each discovered address until we find one with enough funds
                if (allAddresses.length > 0) {
                    for (const addr of allAddresses) {
                        try {
                            const io = await getTxInputsOutputs(addr, receiver_address, mars_amount);
                            if (io && io.inputs && io.inputs.length > 0) {
                                console.log("UTXO found on address:", addr, "inputs:", io.inputs.length);
                                return io;
                            }
                        } catch (e) {
                            console.log("No sufficient UTXOs on", addr, "- trying next...");
                        }
                    }
                }

                // Fallback: try the stored wallet address directly
                try {
                    const io = await getTxInputsOutputs(sender_address, receiver_address, mars_amount);
                    return io;
                } catch (e) {
                    handleError("get input outputs - insufficient funds across all addresses");
                    throw e;
                }
            }

            const signMARS = async (mars_amount, tx_i_o, mnemonic) => {

                const sender_address = "{{ $public_addr }}".trim()
                const seed = my_bundle.bip39.mnemonicToSeedSync(mnemonic.trim());
                // IMPORTANT: use my_bundle.bitcoin.bip32 (same as genSeed) NOT my_bundle.bip32
                const root = my_bundle.bitcoin.bip32.fromSeed(seed, Marscoin.mainnet)
                const addressMap = window._walletAddressMap || {};

                // Helper: derive key for a specific address using its HD path
                function getKeyForAddress(address) {
                    const pathInfo = addressMap[address];
                    if (pathInfo) {
                        const path = `m/44'/2'/0'/${pathInfo.chain}/${pathInfo.index}`;
                        const child = root.derivePath(path);
                        console.log(`Signing with path ${path} for address ${address}`);
                        return my_bundle.bitcoin.ECPair.fromWIF(child.toWIF(), Marscoin.mainnet);
                    }
                    // Fallback: try first address key (same derivation as genSeed)
                    console.log(`Signing with default path m/44'/2'/0'/0/0 for address ${address}`);
                    const child = root.derivePath("m/44'/2'/0'/0/0");
                    return my_bundle.bitcoin.ECPair.fromWIF(child.toWIF(), Marscoin.mainnet);
                }

                // Helper: extract the address from the UTXO's locking script
                function getAddressFromInput(input) {
                    try {
                        const rawTxBuf = my_bundle.Buffer.from(input.rawTx, 'hex');
                        const prevTx = my_bundle.bitcoin.Transaction.fromBuffer(rawTxBuf);
                        const output = prevTx.outs[input.vout];
                        const addr = my_bundle.bitcoin.address.fromOutputScript(output.script, Marscoin.mainnet);
                        return addr;
                    } catch (e) {
                        console.log("Could not extract address from input:", e.message);
                        return input.address || null;
                    }
                }

                var psbt = new my_bundle.bitcoin.Psbt({
                    network: Marscoin.mainnet,
                });
                psbt.setVersion(1)
                psbt.setMaximumFeeRate(100000);

                // Track which key to use for each input
                const inputKeys = [];

                tx_i_o.inputs.forEach((input, i) => {
                    psbt.addInput({
                        hash: input.txId,
                        index: input.vout,
                        nonWitnessUtxo: my_bundle.Buffer.from(input.rawTx, 'hex'),
                    })
                    // Determine the correct signing key for this input
                    const inputAddr = getAddressFromInput(input);
                    console.log(`Input ${i}: address=${inputAddr}, path=${addressMap[inputAddr] ? "m/44'/2'/0'/" + addressMap[inputAddr].chain + "/" + addressMap[inputAddr].index : "default 0/0"}`);
                    inputKeys.push(getKeyForAddress(inputAddr));
                })

                // For change output, use an HD address the scanner can track
                // (civic address may not be discoverable by Electrum due to v28 format)
                const discoveredAddrs = window._walletDiscoveredAddresses || [];
                const changeAddress = discoveredAddrs.length > 0 ? discoveredAddrs[0] : sender_address;

                tx_i_o.outputs.forEach(output => {
                    if (!output.address) {
                        output.address = changeAddress;
                        console.log("Change routed to HD address:", changeAddress);
                    }
                    psbt.addOutput({
                        address: output.address,
                        value: output.value,
                    })
                })

                // Sign each input with its correct key
                for (let i = 0; i < tx_i_o.inputs.length; i++) {
                    try {
                        psbt.signInput(i, inputKeys[i]);
                    } catch (signErr) {
                        // Debug: show what key we tried vs what the UTXO expects
                        const inputAddr = getAddressFromInput(tx_i_o.inputs[i]);
                        const keyPub = inputKeys[i].publicKey.toString('hex');
                        const derivedAddr = my_bundle.bitcoin.payments.p2pkh({ pubkey: inputKeys[i].publicKey, network: Marscoin.mainnet }).address;
                        console.error(`Sign failed for input ${i}:`);
                        console.error(`  UTXO address:    ${inputAddr}`);
                        console.error(`  Key derives to:  ${derivedAddr}`);
                        console.error(`  Key pubkey:      ${keyPub}`);
                        console.error(`  Match: ${inputAddr === derivedAddr}`);

                        // Try brute-force: scan first 20 receiving + 20 change paths
                        let signed = false;
                        for (let chain = 0; chain <= 1 && !signed; chain++) {
                            for (let idx = 0; idx < 20 && !signed; idx++) {
                                try {
                                    const tryChild = root.derivePath(`m/44'/2'/0'/${chain}/${idx}`);
                                    const tryKey = my_bundle.bitcoin.ECPair.fromWIF(tryChild.toWIF(), Marscoin.mainnet);
                                    psbt.signInput(i, tryKey);
                                    console.log(`  ✓ Signed with brute-force path m/44'/2'/0'/${chain}/${idx}`);
                                    signed = true;
                                } catch (e) { /* try next */ }
                            }
                        }
                        if (!signed) {
                            handleError("unable to sign");
                            throw signErr;
                        }
                    }
                }

                const txhash = psbt.finalizeAllInputs().extractTransaction().toHex()

                try {
                    const txId = await broadcastTxHash(txhash);
                    return txId;
                } catch (e) {
                    handleError("broadcasting")
                    throw e;
                }
            }

            const handleError = (str) => {
                console.log("ERROR:", str)
            }


            //===============================================================================
            //===============================================================================
            // API CALLS

            const getTxInputsOutputs = async (sender_address, receiver_address, amount) => {
                // Default options are marked with *
                if (!sender_address || !receiver_address || !amount) {
                    throw new Error("Missing inputs for tx hash call...");
                }
                //console.log(sender_address)
                //console.log(receiver_address)
                //console.log(amount)

                const url =
                    `https://pebas.marscoin.org/api/mars/utxo?sender_address=${sender_address}&receiver_address=${receiver_address}&amount=${amount}`

                try {
                    const response = await fetch(url, {
                        method: 'GET', // *GET, POST, PUT, DELETE, etc.
                    });

                    return response.json()

                } catch (e) {
                    throw e;
                }



            }

            const broadcastTxHash = async (txhashstring) => {
                if (!txhashstring) {
                    throw new Error("Missing tx hash...");
                }
                const url = "https://pebas.marscoin.org/api/mars/broadcast"
                try {
                    const config = {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            a: 1,
                            txhash: txhashstring
                        })
                    }
                    const response = await fetch(url, config)
                    if (response.ok) {
                        return response.json()
                    } else {
                        console.log(response)
                    }
                } catch (error) {
                    console.log(error)
                }

            }

            //===============================================================================
            //===============================================================================
            //===============================================================================



            const zubrinConvert = (MARS) => {
                return (MARS * 100000000)
            }

            const marsConvert = (zubrin) => {
                return (zubrin / 100000000)
            }


            const genSeed = (mnemonic) => {

                //const mnemonic = my_bundle.bip39.generateMnemonic();
                // console.log(mnemonic)

                //const root = new my_bundle.BIP84.fromMnemonic(mnemonic, null, false, 107);

                const seed = my_bundle.bip39.mnemonicToSeedSync(mnemonic);

                // console.log("seed: ", seed)


                // ROOT === xprv
                const root = my_bundle.bitcoin.bip32.fromSeed(seed, Marscoin.mainnet)

                // console.log("root: ", root.toBase58());

                //private key
                const child = root.derivePath("m/44'/2'/0'/0'");
                // console.log("child: ", child.toWIF())

                // tpub == tpub
                let tpub = child.toBase58()
                // console.log("tpub: ", tpub)


                const hdNode = my_bundle.bitcoin.bip32.fromBase58(tpub, Marscoin.mainnet)
                const node = hdNode.derive(0)
                // console.log("My node: ", node)

                // Marscoin addy here
                const addy = nodeToLegacyAddress(node.derive(0))

                const publicKey = node.publicKey.toString('hex')
                // console.log("Public Key: ", publicKey)

                // console.log("addy: ", addy)



                const resp = {
                    address: addy,
                    pubKey: publicKey,
                    prvKey: child.toWIF(),
                    xprv: root.toBase58(),
                    mnemonic: mnemonic
                }


                return resp;

            };




        });

        var qr = new QRious({
            element: document.getElementById('qrious'),
            value: '{{ $public_addr }}',
            size: 200,
            foreground: '#c84125',
            background: '#0c0c16',
            level: 'M',
            padding: 12
        });

        function scan() {
            $('.scan-popup').modal('show');
        }

        const video = document.createElement("video");
        const canvasElement = document.getElementById("qr-canvas");
        const canvas = canvasElement.getContext("2d");
        const outputData = document.getElementById("outputData");
        const btnScanQR = document.getElementById("btn-scan-qr");

        let scanning = false;

        $('.scan-popup').on('shown.bs.modal', function(e) {

            console.log("Here");
            navigator.mediaDevices
                .getUserMedia({
                    video: {
                        facingMode: "environment"
                    }
                })
                .then(function(stream) {
                    scanning = true;
                    canvasElement.hidden = false;
                    video.setAttribute("playsinline",
                        true); // required to tell iOS safari we don't want fullscreen
                    video.srcObject = stream;
                    video.play();
                    tick();
                    scan2();
                });

        });




        qrcode.callback = res => {
            if (res) {
                res = res.replace("bitcoin:", "");
                $("#recipient").val(res);
                scanning = false;
                $('.scan-popup').modal('hide');

                video.srcObject.getTracks().forEach(track => {
                    track.stop();
                });


                canvasElement.hidden = true;
                btnScanQR.hidden = false;
                $('.scan-popup').modal('hide');

            }
        };


        function tick() {
            canvasElement.height = video.videoHeight;
            canvasElement.width = video.videoWidth;
            canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);

            scanning && requestAnimationFrame(tick);
        }

        function scan2() {
            try {
                qrcode.decode();
            } catch (e) {
                setTimeout(scan2, 300);
            }
        }


        function download(content, fileName, contentType) {
            // function to download wallet keys as json export...


            // const password = $("#unlock-password").val().replace(/\s+/g, '');


            // var unlockedSeed = unlockWallet(password, content)
            // console.log(content/)

            if (content) {
                $(".error-unlocking").text("Success!")

                let json = {
                    key: content
                }


                const a = document.createElement("a");
                const file = new Blob([JSON.stringify(json)], {
                    type: contentType
                });
                a.href = URL.createObjectURL(file);
                a.download = fileName;
                a.click();

                window.location.reload()

            } else {
               $(".error-unlocking").text("invalid password...")
                return false
            }


        }



        function onDownloadWallet() {
            const mnemonic = WalletKey.get();
            if (!mnemonic) {
                alert("No wallet key found. Please unlock your wallet first.");
                return;
            }

            // Prompt for an export password
            const exportPassword = prompt(
                "Enter a password to encrypt your backup file.\n\n" +
                "This password will be required to restore from this backup.\n" +
                "Leave empty for an unencrypted backup (not recommended)."
            );

            if (exportPassword === null) return; // User cancelled

            if (exportPassword.trim() !== '') {
                // Encrypted export
                try {
                    const hashed = hashPassword(exportPassword.trim());
                    const encrypted = my_bundle.encrypt(mnemonic.trim(), hashed, iv);
                    const json = {
                        version: 2,
                        encrypted: true,
                        pbkdf2_rounds: PBKDF2_ROUNDS,
                        data: encrypted,
                        address: "{{ $public_addr ?? '' }}"
                    };
                    downloadFile(JSON.stringify(json, null, 2), "marswallet-encrypted.json", "application/json");
                    alert("Encrypted backup saved. Keep your backup password safe - you'll need it to restore.");
                } catch (e) {
                    alert("Failed to encrypt backup: " + e.message);
                }
            } else {
                // Unencrypted export (legacy, with warning)
                if (!confirm("WARNING: This will save your seed phrase in plain text. Anyone with access to this file can steal your funds. Continue?")) {
                    return;
                }
                const json = {
                    version: 1,
                    encrypted: false,
                    key: mnemonic.trim()
                };
                downloadFile(JSON.stringify(json, null, 2), "marswallet-key.json", "text/plain");
            }
        }

        function downloadFile(content, fileName, contentType) {
            const a = document.createElement("a");
            const file = new Blob([content], { type: contentType });
            a.href = URL.createObjectURL(file);
            a.download = fileName;
            a.click();
            URL.revokeObjectURL(a.href);
        }

        // grab user input password. unlock wallet...

        function unlockWallet(user_password, encrypted_seed) {

            const hashed = hashPassword(user_password);


            const user_wallet = "{{ $public_addr }}"
            let iv = "{{ json_encode($iv) }}".replace("]", "").replace("[", "").split(",");
            //console.log("hashed:", hashed)

            //const encrypted = my_bundle.encrypt("face they lemon ignore link crop above thing buffalo tide category soup", hashed)
            //console.log("Encrypted: ", encrypted)

            const decrypted = my_bundle.decrypt(encrypted_seed, hashed, iv).trim()

            // console.log("Encrypted SEED: {{ $encrypted_seed }}")
            // console.log("MNEM:", decrypted)


            const response = genSeed(decrypted)




            console.log("response:", response.address)

            console.log(decrypted)

            console.log("user_wallet:",user_wallet)
            if (response.address == user_wallet) {

                console.log("success...")


                return decrypted;
                //      console.error("Item Succesfully locally stored")
            } else {

                console.log("failure...")

                // validated = false
                // e.preventDefault();
                // window.location.reload()

                return false;
                // $(".wallet-getter").attr("action", "/wallet/failwallet")
            }



        }




        const PBKDF2_ROUNDS = 100000;
        const PBKDF2_LEGACY_ROUNDS = 1;

        const hashPassword = (passcode) => {
            const ret = my_bundle.pbkdf2.pbkdf2Sync(
                passcode,
                "{{ $SALT }}", PBKDF2_ROUNDS, 16, 'sha512').toString('hex')
            return ret
        }

        const hashPasswordLegacy = (passcode) => {
            const ret = my_bundle.pbkdf2.pbkdf2Sync(
                passcode,
                "{{ $SALT }}", PBKDF2_LEGACY_ROUNDS, 16, 'sha512').toString('hex')
            return ret
        }



        // LTC Derivation Path
        const Marscoin = {
            mainnet: {
                messagePrefix: "\x19Marscoin Signed Message:\n",
                bech32: "M",
                bip44: 2,
                bip32: {
                    public: 0x043587cf,
                    private: 0x04358394,
                },
                pubKeyHash: 0x32,
                scriptHash: 0x32,
                wif: 0x80,
            },
        };


        function nodeToLegacyAddress(hdNode) {
            return my_bundle.bitcoin.payments.p2pkh({
                pubkey: hdNode.publicKey,
                network: Marscoin.mainnet,
            }).address;
        }
        const genSeed = (mnemonic) => {
            // console.log("SALT: {{ $SALT }}")
            //mnemonic = "invite feature forget axis radar stone bind squirrel dog crash trap equip"

            //const mnemonic = my_bundle.bip39.generateMnemonic();
            //  console.log(mnemonic)

            //const root = new my_bundle.BIP84.fromMnemonic(mnemonic, null, false, 107);

            const seed = my_bundle.bip39.mnemonicToSeedSync(mnemonic.trim());


            // ROOT === xprv
            const root = my_bundle.bitcoin.bip32.fromSeed(seed, Marscoin.mainnet)


            //private key
            const child = root.derivePath("m/44'/2'/0'").neutered();
            //console.log("child: ", child)

            // tpub == tpub
            let tpub = child.toBase58()


            const hdNode = my_bundle.bip32.fromBase58(tpub, Marscoin.mainnet)
            const node = hdNode.derive(0)

            // Marscoin addy here
            const addy = nodeToLegacyAddress(node.derive(0))


            const publicKey = node.publicKey.toString('hex')

            //console.log("addy: ", addy)

            const resp = {
                address: addy,
                pubKey: publicKey,
                xprv: root.toBase58(),
                mnemonic: mnemonic
            }


            return resp;

        };


    // ===================================================================
    // New Security Features: View Seed & Re-encrypt
    // ===================================================================
    function showSeedPhrase() {
        var mnemonic = WalletKey.get();
        if (!mnemonic) {
            alert('Wallet key not found. Please unlock your wallet first.');
            return;
        }
        var confirmed = confirm('WARNING: Your seed phrase gives full access to your funds. Never share it with anyone. Continue?');
        if (!confirmed) return;

        var overlay = document.createElement('div');
        overlay.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.85);z-index:10000;display:flex;align-items:center;justify-content:center;';
        overlay.innerHTML = '<div style="background:var(--mr-surface,#12121e);border:2px solid var(--mr-mars,#c84125);border-radius:12px;padding:32px;max-width:500px;width:90%;text-align:center;">' +
            '<div style="font-family:Orbitron,sans-serif;font-size:13px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--mr-mars,#c84125);margin-bottom:16px;"><i class="fa fa-exclamation-triangle" style="margin-right:6px;"></i> Recovery Seed Phrase</div>' +
            '<div style="background:var(--mr-dark,#0c0c16);border:1px solid var(--mr-border-bright,rgba(255,255,255,0.1));border-radius:8px;padding:20px;margin-bottom:20px;">' +
            '<div style="font-family:JetBrains Mono,monospace;font-size:15px;font-weight:500;color:#fff;line-height:2.2;word-spacing:8px;letter-spacing:0.5px;">' + mnemonic.trim() + '</div>' +
            '</div>' +
            '<div style="font-family:JetBrains Mono,monospace;font-size:9px;color:var(--mr-text-faint,#5a5968);margin-bottom:16px;">Write these words down and store them in a safe place. Anyone with these words can access your funds.</div>' +
            '<button onclick="this.closest(\'div[style*=fixed]\').remove()" style="background:var(--mr-mars,#c84125);border:none;color:#fff;padding:10px 24px;border-radius:6px;font-family:JetBrains Mono,monospace;font-size:11px;letter-spacing:1px;text-transform:uppercase;cursor:pointer;">Close</button>' +
            '</div>';
        document.body.appendChild(overlay);
    }

    function createNewBackup() {
        var mnemonic = WalletKey.get();
        if (!mnemonic) {
            alert('Wallet key not found. Please unlock your wallet first.');
            return;
        }
        var newPassword = prompt('Enter a NEW password to re-encrypt your wallet backup.\n\nThis will update the server-stored encrypted backup with your new password.');
        if (!newPassword || newPassword.trim() === '') return;
        var confirmPassword = prompt('Confirm new password:');
        if (newPassword !== confirmPassword) {
            alert('Passwords do not match. Please try again.');
            return;
        }

        try {
            var iv = "{{ json_encode($iv) }}".replace("]", "").replace("[", "").split(",");
            iv = new Uint8Array(iv);
            var hashed = my_bundle.pbkdf2.pbkdf2Sync(newPassword.trim(), "{{ $SALT }}", 100000, 16, 'sha512').toString('hex');
            var encrypted = my_bundle.encrypt(mnemonic.trim(), hashed, iv);

            $.post('/wallet/createwallet', {
                password: encrypted,
                public_addr: '{{ $public_addr }}',
                wallet_name: 'Re-encrypted'
            }).done(function() {
                if (typeof toastr !== 'undefined') {
                    toastr.success('Wallet backup re-encrypted with your new password.', 'Backup Updated');
                } else {
                    alert('Wallet backup has been re-encrypted with your new password.');
                }
            }).fail(function() {
                alert('Failed to update backup. Please try again.');
            });
        } catch (err) {
            alert('Encryption failed: ' + err.message);
            console.error('Re-encryption error:', err);
        }
    }

    // Update bridge hero balance when HD discovery completes
    (function() {
        var balEl = document.getElementById('hd-total-balance');
        var heroEl = document.getElementById('bridge-balance-display');
        if (!balEl || !heroEl) return;
        var observer = new MutationObserver(function() {
            var txt = balEl.textContent.trim();
            if (txt && txt.indexOf('Scanning') === -1 && txt.indexOf('Discovering') === -1) {
                var num = parseFloat(txt.replace(/[^0-9.]/g, ''));
                if (!isNaN(num) && num > 0) {
                    heroEl.textContent = num.toFixed(4);
                } else if (txt.indexOf('MARS') === -1) {
                    heroEl.textContent = txt;
                }
            }
        });
        observer.observe(balEl, { childList: true, subtree: true, characterData: true });
    })();

    </script>

@livewireScripts
</body>

</html>
