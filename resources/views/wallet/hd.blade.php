<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <title>Marscoin Wallet</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ Session::token() }}">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald:400,300,700">
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@700&family=Orbitron:wght@500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/assets/wallet/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/bootstrap.css">
    <link rel="stylesheet" href="/assets/wallet/css/hd/hd.css?v=2.1">
    <link rel="stylesheet" href="/assets/wallet/js/plugins/magnific/magnific-popup.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-admin.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-admin-extended.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-flat.css">
    <link rel="stylesheet" href="/assets/wallet/css/jquery.steps.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" integrity="sha512-1cK78a1o+ht2JcaW6g8OXYwqpev9+6GqOkz9xmBN9iUUhIndKtxwILGWYOSibOKjLsEdjyjZvYDq/cZwNeak0w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="shortcut icon" href="/favicon.ico">

    <script src="/assets/wallet/js/dist/bundle.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
    /* ============================================ */
    /* THE VAULT: Wallet Hub Redesign               */
    /* ============================================ */
    body { background: var(--mr-void, #06060c) !important; color: var(--mr-text, #e0dfe6); }

    /* -- Page structure -- */
    .vault-page { min-height: 100vh; display: flex; flex-direction: column; }
    .vault-page .content { flex: 1; }

    .vault-hero {
        padding: 32px 0 20px;
        position: relative;
    }
    .vault-hero::after {
        content: '';
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--mr-border-bright, rgba(255,255,255,0.12)) 30%, var(--mr-mars, #c84125) 50%, var(--mr-border-bright, rgba(255,255,255,0.12)) 70%, transparent);
    }
    .vault-status {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: var(--mr-mars, #c84125);
        margin-bottom: 6px;
    }
    .vault-status .status-dot {
        display: inline-block;
        width: 6px; height: 6px;
        border-radius: 50%;
        background: var(--mr-green, #34d399);
        margin-right: 8px;
        animation: vaultPulse 2s infinite;
        vertical-align: middle;
    }
    .vault-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 28px;
        font-weight: 800;
        color: #fff;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin: 0;
    }

    /* -- Action bar -- */
    .vault-actions {
        display: flex;
        gap: 12px;
        margin-top: 24px;
        margin-bottom: 28px;
    }
    .vault-action-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 6px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        font-weight: 500;
        letter-spacing: 1px;
        text-transform: uppercase;
        text-decoration: none !important;
        cursor: pointer;
        transition: all 0.25s ease;
        border: 1px solid;
    }
    .vault-action-btn.primary {
        background: var(--mr-mars, #c84125);
        border-color: var(--mr-mars, #c84125);
        color: #fff !important;
    }
    .vault-action-btn.primary:hover {
        background: #d94e30;
        box-shadow: 0 0 20px rgba(200,65,37,0.3);
        transform: translateY(-1px);
    }
    .vault-action-btn.secondary {
        background: transparent;
        border-color: var(--mr-border-bright, rgba(255,255,255,0.15));
        color: var(--mr-text, #e0dfe6) !important;
    }
    .vault-action-btn.secondary:hover {
        border-color: var(--mr-cyan, #00e4ff);
        color: var(--mr-cyan, #00e4ff);
        box-shadow: 0 0 20px rgba(0,228,255,0.1);
    }

    /* -- Section labels -- */
    .vault-section-label {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--mr-text-dim, #8a8998);
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 1px solid var(--mr-border, rgba(255,255,255,0.06));
    }

    /* -- Wallet Cards: Credit Card Style -- */
    .vault-cards {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }
    .vault-card {
        position: relative;
        background: linear-gradient(135deg, var(--mr-surface, #12121e) 0%, var(--mr-surface-raised, #1a1a2a) 100%);
        border: 1px solid var(--mr-border, rgba(255,255,255,0.06));
        border-radius: 12px;
        padding: 24px;
        overflow: hidden;
        transition: border-color 0.3s, box-shadow 0.3s, transform 0.2s;
        cursor: default;
    }
    .vault-card:hover {
        border-color: rgba(255,255,255,0.12);
        box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        transform: translateY(-2px);
    }
    .vault-card.unlocked { border-color: rgba(52,211,153,0.25); }
    .vault-card.unlocked:hover { border-color: rgba(52,211,153,0.4); box-shadow: 0 8px 32px rgba(52,211,153,0.08); }
    .vault-card::before {
        content: '';
        position: absolute;
        top: 0; right: 0;
        width: 120px; height: 120px;
        background: radial-gradient(circle at top right, rgba(200,65,37,0.06), transparent 70%);
        pointer-events: none;
    }
    .vault-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 16px;
    }
    .vault-card-chip {
        width: 36px; height: 26px;
        background: linear-gradient(135deg, #c8a84e 0%, #d4b85a 40%, #a08030 100%);
        border-radius: 4px;
        position: relative;
        overflow: hidden;
    }
    .vault-card-chip::after {
        content: '';
        position: absolute;
        top: 4px; left: 6px;
        width: 24px; height: 18px;
        border: 1px solid rgba(0,0,0,0.15);
        border-radius: 2px;
    }
    .vault-card-chip::before {
        content: '';
        position: absolute;
        top: 50%; left: 0; right: 0;
        height: 1px;
        background: rgba(0,0,0,0.12);
    }
    .vault-card-status {
        font-family: 'JetBrains Mono', monospace;
        font-size: 9px;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        padding: 3px 8px;
        border-radius: 3px;
    }
    .vault-card-status.locked {
        background: rgba(200,65,37,0.12);
        color: var(--mr-mars, #c84125);
        border: 1px solid rgba(200,65,37,0.25);
    }
    .vault-card-status.active-status {
        background: rgba(52,211,153,0.12);
        color: var(--mr-green, #34d399);
        border: 1px solid rgba(52,211,153,0.25);
    }
    .vault-card-type {
        font-family: 'Orbitron', sans-serif;
        font-size: 13px;
        font-weight: 600;
        color: #fff;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-bottom: 8px;
    }
    .vault-card-addr {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        color: var(--mr-cyan, #00e4ff);
        letter-spacing: 0.5px;
        margin-bottom: 16px;
        word-break: break-all;
        opacity: 0.8;
    }
    .vault-card-balance {
        font-family: 'Orbitron', sans-serif;
        font-size: 22px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 4px;
    }
    .vault-card-balance .unit {
        font-size: 11px;
        font-weight: 400;
        color: var(--mr-text-dim, #8a8998);
        margin-left: 4px;
    }
    .vault-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 16px;
        padding-top: 14px;
        border-top: 1px solid var(--mr-border, rgba(255,255,255,0.06));
    }
    .vault-card-meta {
        font-family: 'JetBrains Mono', monospace;
        font-size: 9px;
        color: var(--mr-text-faint, #5a5968);
        letter-spacing: 0.5px;
    }
    .vault-card-actions {
        display: flex;
        gap: 6px;
    }
    .vault-btn-sm {
        font-family: 'JetBrains Mono', monospace;
        font-size: 9px;
        letter-spacing: 0.5px;
        padding: 5px 10px;
        border-radius: 4px;
        border: 1px solid var(--mr-border-bright, rgba(255,255,255,0.12));
        background: var(--mr-dark, #0c0c16);
        color: var(--mr-text-dim, #8a8998);
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none !important;
    }
    .vault-btn-sm:hover { border-color: var(--mr-cyan, #00e4ff); color: var(--mr-cyan, #00e4ff); }
    .vault-btn-sm.unlock { border-color: rgba(52,211,153,0.3); color: var(--mr-green, #34d399); }
    .vault-btn-sm.unlock:hover { background: rgba(52,211,153,0.1); border-color: var(--mr-green, #34d399); }
    .vault-btn-sm.danger { border-color: rgba(239,68,68,0.25); color: #ef4444; }
    .vault-btn-sm.danger:hover { background: rgba(239,68,68,0.1); border-color: #ef4444; }

    /* -- Empty State -- */
    .vault-empty {
        text-align: center;
        padding: 60px 20px;
        border: 1px dashed var(--mr-border-bright, rgba(255,255,255,0.1));
        border-radius: 12px;
        background: var(--mr-surface, #12121e);
    }
    .vault-empty-icon {
        font-size: 48px;
        color: var(--mr-text-faint, #5a5968);
        margin-bottom: 20px;
    }
    .vault-empty h3 {
        font-family: 'Orbitron', sans-serif;
        font-size: 16px;
        font-weight: 600;
        color: #fff;
        letter-spacing: 1px;
        margin-bottom: 8px;
    }
    .vault-empty p {
        font-family: 'JetBrains Mono', monospace;
        font-size: 12px;
        color: var(--mr-text-dim, #8a8998);
        margin-bottom: 24px;
    }

    /* -- Modal Overrides -- */
    .modal-styled .modal-content {
        background: var(--mr-surface, #12121e) !important;
        border: 1px solid var(--mr-border-bright, rgba(255,255,255,0.1)) !important;
        border-radius: 12px !important;
        color: var(--mr-text, #e0dfe6) !important;
        box-shadow: 0 24px 80px rgba(0,0,0,0.6) !important;
    }
    .modal-styled .modal-header {
        background: var(--mr-dark, #0c0c16) !important;
        border-bottom: 1px solid var(--mr-border, rgba(255,255,255,0.06)) !important;
        border-radius: 12px 12px 0 0 !important;
        padding: 16px 24px !important;
    }
    .modal-styled .modal-title {
        font-family: 'Orbitron', sans-serif !important;
        font-size: 14px !important;
        font-weight: 600 !important;
        letter-spacing: 1.5px !important;
        text-transform: uppercase !important;
        color: #fff !important;
    }
    .modal-styled .modal-header .close {
        color: var(--mr-text-dim) !important;
        opacity: 0.7 !important;
        text-shadow: none !important;
    }
    .modal-styled .modal-body {
        background: var(--mr-surface, #12121e) !important;
        padding: 24px !important;
    }
    .modal-styled .form-control {
        background: var(--mr-dark, #0c0c16) !important;
        border: 1px solid var(--mr-border-bright, rgba(255,255,255,0.1)) !important;
        color: #fff !important;
        border-radius: 6px !important;
        font-family: 'JetBrains Mono', monospace !important;
        font-size: 13px !important;
        padding: 10px 14px !important;
        transition: border-color 0.2s !important;
    }
    .modal-styled .form-control:focus {
        border-color: var(--mr-cyan, #00e4ff) !important;
        box-shadow: 0 0 0 2px rgba(0,228,255,0.1) !important;
        outline: none !important;
    }
    .modal-styled label {
        font-family: 'JetBrains Mono', monospace !important;
        font-size: 10px !important;
        letter-spacing: 1.5px !important;
        text-transform: uppercase !important;
        color: var(--mr-text-dim, #8a8998) !important;
        margin-bottom: 6px !important;
    }
    .modal-styled .btn-primary {
        background: var(--mr-mars, #c84125) !important;
        border-color: var(--mr-mars, #c84125) !important;
        font-family: 'JetBrains Mono', monospace !important;
        font-size: 11px !important;
        letter-spacing: 1px !important;
        text-transform: uppercase !important;
        padding: 10px 24px !important;
        border-radius: 6px !important;
        transition: all 0.2s !important;
    }
    .modal-styled .btn-primary:hover {
        background: #d94e30 !important;
        box-shadow: 0 0 16px rgba(200,65,37,0.3) !important;
    }
    .modal-styled .btn-secondary, .modal-styled .btn-default {
        background: var(--mr-dark, #0c0c16) !important;
        border: 1px solid var(--mr-border-bright, rgba(255,255,255,0.12)) !important;
        color: var(--mr-text-dim, #8a8998) !important;
        font-family: 'JetBrains Mono', monospace !important;
        font-size: 11px !important;
        border-radius: 6px !important;
    }

    /* -- Entropy box -- */
    .mouse-box {
        width: 100% !important;
        height: 200px !important;
        position: relative !important;
        background: var(--mr-dark, #0c0c16) !important;
        border: 1px solid var(--mr-border-bright, rgba(255,255,255,0.1)) !important;
        border-radius: 8px !important;
        overflow: hidden;
        cursor: crosshair;
        margin-bottom: 16px;
    }
    .mouse-box::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            repeating-linear-gradient(0deg, transparent, transparent 19px, rgba(255,255,255,0.02) 19px, rgba(255,255,255,0.02) 20px),
            repeating-linear-gradient(90deg, transparent, transparent 19px, rgba(255,255,255,0.02) 19px, rgba(255,255,255,0.02) 20px);
        pointer-events: none;
    }
    .dot {
        position: absolute !important;
        width: 3px !important;
        height: 3px !important;
        border-radius: 50% !important;
        background: var(--mr-cyan, #00e4ff) !important;
        pointer-events: none !important;
        z-index: 1000 !important;
        box-shadow: 0 0 4px var(--mr-cyan, #00e4ff);
    }
    #progress-counter {
        font-family: 'Orbitron', sans-serif !important;
        color: var(--mr-mars, #c84125) !important;
    }
    .progress {
        background: var(--mr-dark, #0c0c16) !important;
        border-radius: 4px !important;
        height: 6px !important;
        overflow: hidden;
        border: 1px solid var(--mr-border, rgba(255,255,255,0.06));
    }
    .progress-bar-primary {
        background: var(--mr-mars, #c84125) !important;
    }

    /* -- Mnemonic display -- */
    .mnemonic {
        background: var(--mr-dark, #0c0c16) !important;
        border: 2px solid var(--mr-mars, #c84125) !important;
        border-radius: 8px !important;
        padding: 20px !important;
        margin: 16px 0 !important;
        position: relative;
    }
    .mnemonic::before {
        content: '\f023';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        top: 8px; right: 12px;
        color: rgba(200,65,37,0.3);
        font-size: 14px;
    }
    .mnemonic-text {
        font-family: 'JetBrains Mono', monospace !important;
        font-size: 15px !important;
        font-weight: 500 !important;
        color: #fff !important;
        line-height: 2 !important;
        word-spacing: 8px;
        letter-spacing: 0.5px;
    }

    /* -- Seed inputs -- */
    .seed-input {
        background: var(--mr-dark, #0c0c16) !important;
        border: 1px solid var(--mr-border-bright, rgba(255,255,255,0.1)) !important;
        color: #fff !important;
        font-family: 'JetBrains Mono', monospace !important;
        font-size: 13px !important;
        padding: 10px 12px !important;
        border-radius: 6px !important;
        width: 120px !important;
        margin: 4px !important;
        text-align: center !important;
        transition: border-color 0.2s !important;
    }
    .seed-input:focus {
        border-color: var(--mr-cyan, #00e4ff) !important;
        box-shadow: 0 0 0 2px rgba(0,228,255,0.1) !important;
        outline: none !important;
    }
    .seed-input::placeholder {
        color: var(--mr-text-faint, #5a5968) !important;
    }

    /* Modal body padding + overflow with hidden scrollbar */
    .modal-styled .modal-body, #styledModal .modal-body, #modalLogin .modal-body {
        padding-bottom: 40px !important;
        overflow-y: auto !important;
        max-height: 75vh !important;
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* IE/Edge */
    }
    .modal-styled .modal-body::-webkit-scrollbar,
    #styledModal .modal-body::-webkit-scrollbar,
    #modalLogin .modal-body::-webkit-scrollbar {
        display: none; /* Chrome/Safari */
    }
    #styledModal .tab-content, #modalLogin .tab-content {
        padding-bottom: 24px !important;
    }

    /* -- Tab pills in modals -- */
    .nav-pills.nav-stacked > li > a {
        background: var(--mr-dark, #0c0c16) !important;
        color: var(--mr-text-dim, #8a8998) !important;
        border: 1px solid var(--mr-border, rgba(255,255,255,0.06)) !important;
        font-family: 'JetBrains Mono', monospace !important;
        font-size: 11px !important;
        border-radius: 6px !important;
        margin-bottom: 4px !important;
        transition: all 0.2s !important;
    }
    .nav-pills.nav-stacked > li.active > a,
    .nav-pills.nav-stacked > li.active > a:hover {
        background: var(--mr-surface-raised, #1a1a2a) !important;
        border-color: var(--mr-mars, #c84125) !important;
        color: #fff !important;
    }
    .nav-pills.nav-stacked > li.disabled > a {
        opacity: 0.4 !important;
        cursor: not-allowed !important;
    }

    /* -- Modal internal layout fixes -- */
    .next-btn {
        text-align: right;
        padding: 20px 0 24px;
        margin-top: 12px;
    }
    .next-btn .btn-primary {
        padding: 10px 28px !important;
        font-family: 'JetBrains Mono', monospace !important;
        font-size: 12px !important;
        letter-spacing: 1.5px !important;
        text-transform: uppercase !important;
        background: var(--mr-mars, #c84125) !important;
        border-color: var(--mr-mars) !important;
        color: #fff !important;
        border-radius: 6px !important;
    }
    .title-help h2 {
        font-family: 'Orbitron', sans-serif !important;
        font-size: 14px !important;
        font-weight: 600 !important;
        letter-spacing: 1px !important;
        text-transform: uppercase !important;
        color: #fff !important;
        margin: 0 !important;
    }
    .title-help .btn {
        background: var(--mr-surface-raised) !important;
        border-color: var(--mr-border-bright) !important;
        color: var(--mr-text-dim) !important;
    }
    .password-encrypt { margin-top: 12px; }
    .password-encrypt label {
        font-family: 'JetBrains Mono', monospace !important;
        font-size: 10px !important;
        letter-spacing: 1px !important;
        text-transform: uppercase !important;
        color: var(--mr-text-dim) !important;
        margin-top: 12px !important;
    }
    .password-encrypt .form-control {
        background: var(--mr-dark) !important;
        border: 1px solid var(--mr-border-bright) !important;
        color: #fff !important;
        border-radius: 6px !important;
        font-family: 'JetBrains Mono', monospace !important;
    }
    .password-encrypt .form-control:focus {
        border-color: var(--mr-cyan) !important;
        box-shadow: 0 0 0 2px rgba(0,228,255,0.1) !important;
    }
    .btn-group .btn.active {
        background: var(--mr-mars) !important;
        border-color: var(--mr-mars) !important;
        color: #fff !important;
    }
    /* Tooltip/popover styling */
    .popover { background: var(--mr-surface, #12121e) !important; border-color: var(--mr-border-bright) !important; }
    .popover-title { background: var(--mr-dark) !important; color: #fff !important; border-color: var(--mr-border) !important; font-family: 'Orbitron', sans-serif !important; font-size: 11px !important; }
    .popover-content { color: var(--mr-text-dim) !important; font-family: 'JetBrains Mono', monospace !important; font-size: 11px !important; }
    .popover .arrow:after { border-right-color: var(--mr-surface) !important; }
    .tooltip-inner { background: var(--mr-surface) !important; color: var(--mr-text) !important; font-family: 'JetBrains Mono', monospace !important; font-size: 11px !important; border: 1px solid var(--mr-border-bright) !important; }
    /* Tab content in modals */
    .stacked-content { color: var(--mr-text) !important; }
    .stacked-content h2 { font-family: 'Orbitron', sans-serif !important; font-size: 14px !important; font-weight: 600 !important; color: #fff !important; letter-spacing: 1px; text-transform: uppercase; }
    .stacked-content p { color: var(--mr-text-dim) !important; font-family: 'JetBrains Mono', monospace !important; font-size: 12px !important; }
    .success h4 { color: var(--mr-green) !important; font-family: 'JetBrains Mono', monospace !important; }
    /* Pub addr in wallet generation step */
    .stacked-content .pub-addr { display: flex; align-items: center; gap: 8px; }
    .stacked-content .pub-addr h3 { font-family: 'JetBrains Mono', monospace !important; font-size: 12px !important; color: var(--mr-cyan) !important; word-break: break-all; }

    /* -- Portlet overrides -- */
    .portlet { background: transparent !important; border: none !important; box-shadow: none !important; }
    .portlet-title { display: none !important; } /* Hidden - replaced by vault-hero */
    .icon-stat, .icon-stat-footer, .icon-stat-label, .icon-stat-value { all: unset; display: block; }

    /* -- Wallet card override (the old .wallet-card class) -- */
    .wallet-card { all: unset; display: block; }

    /* -- Footer -- */
    .footer {
        border-top: 1px solid var(--mr-border, rgba(255,255,255,0.06)) !important;
        padding: 20px 0 !important;
        background: var(--mr-void, #06060c) !important;
    }

    /* -- Misc -- */
    h5 { font-size: 14px; }
    .pub-addr { display: flex; align-items: center; gap: 8px; }
    .copy-icon { color: var(--mr-text-dim) !important; cursor: pointer; transition: color 0.2s; }
    .copy-icon:hover { color: var(--mr-cyan, #00e4ff) !important; }
    .title-help { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; }
    .title-help h2 {
        font-family: 'Orbitron', sans-serif !important;
        font-size: 14px !important;
        font-weight: 600 !important;
        letter-spacing: 1px !important;
        text-transform: uppercase !important;
        color: #fff !important;
        margin: 0 !important;
    }
    .password-encrypt label {
        display: block;
        margin-top: 12px;
    }
    .btn-group .btn { font-family: 'JetBrains Mono', monospace !important; font-size: 11px !important; }

    @keyframes vaultPulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
    }
    @keyframes vaultFadeIn {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .vault-fade-1 { animation: vaultFadeIn 0.5s ease-out 0.15s both; }
    .vault-fade-2 { animation: vaultFadeIn 0.5s ease-out 0.3s both; }
    .vault-fade-3 { animation: vaultFadeIn 0.5s ease-out 0.45s both; }

    @media (max-width: 767px) {
        .vault-title { font-size: 20px; }
        .vault-cards { grid-template-columns: 1fr; }
        .vault-actions { flex-wrap: wrap; }
        .seed-input { width: 90px !important; }
    }
    </style>
    <script src="/assets/wallet/js/plugins/scan/qrcode-gen.min.js"></script>
    @livewireStyles
</head>

<body class="vault-page">
    <div id="wrapper">
        <header class="navbar navbar-inverse" role="banner">
            <div class="container">
                <div class="navbar-header">
                    @include('wallet.header')
                </div> <!-- /.navbar-header -->
                <nav class="collapse navbar-collapse" role="navigation">
                    @include('wallet.navbarleft')
                    @include('wallet.navbarright')
                </nav>
            </div> <!-- /.container -->
        </header>
        @include('wallet.mainnav', ['active' => 'wallet'])

        <div class="content">

            <div class="container">

                {{-- VAULT HERO --}}
                <div class="vault-hero">
                    <div class="vault-status"><span class="status-dot"></span>Secure Vault — Active</div>
                    <h1 class="vault-title">Wallet</h1>
                </div>

                {{-- ACTION BAR --}}
                <div class="vault-actions">
                    <a data-toggle="modal" href="#styledModal" class="vault-action-btn primary demo-element" data-backdrop="static" data-keyboard="false">
                        <i class="fa fa-plus"></i> New Wallet
                    </a>
                    <a data-backdrop="static" data-keyboard="false" data-toggle="modal" href="#modalLogin" class="vault-action-btn secondary demo-element">
                        <i class="fa fa-link"></i> Connect Wallet
                    </a>
                </div>

                <div class="portlet" style="display:none;">
                    <h3 class="portlet-title">
                        <u>Select Wallet</u>
                    </h3>
                </div>

                    {{-- Render Civic Wallet --}}
                    @if ($wallets || $civic_wallet)
                    <div class="vault-section-label">Your Wallets</div>
                    <div class="vault-cards">
                    @endif

                    @if ($civic_wallet)
                        <div class="vault-card vault-fade-1 {{ $civic_wallet->id == $wallet_open ? 'unlocked' : '' }}">
                            <div class="vault-card-header">
                                <div class="vault-card-chip"></div>
                                <span class="vault-card-status {{ $civic_wallet->id == $wallet_open ? 'active-status' : 'locked' }}">
                                    <i class="fa-solid {{ $civic_wallet->id == $wallet_open ? 'fa-unlock' : 'fa-lock' }}" style="margin-right: 4px;"></i>
                                    {{ $civic_wallet->id == $wallet_open ? 'Unlocked' : 'Locked' }}
                                </span>
                            </div>
                            <div class="vault-card-type">
                                <i class="fa fa-id-card" style="margin-right: 6px; color: var(--mr-mars);"></i>
                                @if(!$general_public && !@$citizen) Civic — Applicant
                                @elseif($general_public && !$citizen) Civic — Public
                                @elseif($citizen) Civic — Citizen
                                @endif
                            </div>
                            <div class="vault-card-addr">{{ $civic_wallet->public_addr }}</div>
                            <div class="vault-card-balance">{{ number_format($civic_balance, 4) }}<span class="unit">MARS</span></div>
                            <div class="vault-card-footer">
                                <div class="vault-card-meta">
                                    @if(isset($civic_wallet->opened_at))
                                        Opened {{ \Carbon\Carbon::parse($civic_wallet->opened_at)->diffForHumans() }}
                                    @elseif(isset($civic_wallet->created_at))
                                        Created {{ \Carbon\Carbon::parse($civic_wallet->created_at)->diffForHumans() }}
                                    @endif
                                </div>
                                <div class="vault-card-actions">
                                    <button type="button" class="vault-btn-sm unlock unlock-wallet-button" data-wallet-id="{{ $civic_wallet->id }}" data-toggle="modal" href="#unlockWalletModal" data-keyboard="false" data-wallet='{{ json_encode($civic_wallet, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_SLASHES) }}' id={{ $civic_wallet->public_addr }}>
                                        <i class="fa fa-lock-open"></i> Unlock
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Render all existing wallets --}}
                    @if ($wallets)
                        @foreach ($wallets as $wallet)
                            <div class="vault-card vault-fade-2 {{ $wallet->id == $wallet_open ? 'unlocked' : '' }}">
                                <div class="vault-card-header">
                                    <div class="vault-card-chip"></div>
                                    <span class="vault-card-status {{ $wallet->id == $wallet_open ? 'active-status' : 'locked' }}">
                                        <i class="fa-solid {{ $wallet->id == $wallet_open ? 'fa-unlock' : 'fa-lock' }}" style="margin-right: 4px;"></i>
                                        {{ $wallet->id == $wallet_open ? 'Unlocked' : 'Locked' }}
                                    </span>
                                </div>
                                <div class="vault-card-type">
                                    <i class="fa fa-wallet" style="margin-right: 6px; color: var(--mr-cyan);"></i>
                                    <span class="wallet-name">{{ $wallet->wallet_type }}</span>
                                </div>
                                <div class="vault-card-addr">{{ $wallet->public_addr }}</div>
                                <div class="vault-card-balance">{{ number_format($wallet->balance, 4) }}<span class="unit">MARS</span></div>
                                <div class="vault-card-footer">
                                    <div class="vault-card-meta">
                                        @if(isset($wallet->opened_at))
                                            Opened {{ $wallet->opened_at->diffForHumans() }}
                                        @elseif(isset($wallet->created_at))
                                            Created {{ $wallet->created_at->diffForHumans() }}
                                        @endif
                                    </div>
                                    <div class="vault-card-actions">
                                        <button type="button" class="vault-btn-sm unlock unlock-wallet-button" data-wallet-id="{{ $wallet->id }}" data-toggle="modal" @if($wallet->wallet_type == "Imported") href="#modalLogin" @else href="#unlockWalletModal" @endif data-keyboard="false" data-wallet='{{ json_encode($wallet, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_SLASHES) }}' id={{ $wallet->public_addr }}>
                                            <i class="fa fa-lock-open"></i> Unlock
                                        </button>
                                        <button type="button" class="vault-btn-sm rename-wallet-button" data-wallet-name="{{$wallet->wallet_type}}" data-wallet-id="{{ $wallet->id }}" data-toggle="modal" href="#renameWalletModal">
                                            <i class="fa fa-edit"></i> Rename
                                        </button>
                                        <button type="button" class="vault-btn-sm danger delete-wallet-button" data-wallet-id="{{ $wallet->id }}">
                                            <i class="fa fa-times"></i> Forget
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    @if ($wallets || $civic_wallet)
                    </div>{{-- /.vault-cards --}}
                    @else
                        {{-- Empty state --}}
                        <div class="vault-empty vault-fade-1">
                            <div class="vault-empty-icon"><i class="fa fa-shield-halved"></i></div>
                            <h3>No Wallets Found</h3>
                            <p>Create a new Marscoin wallet or connect an existing one to get started.</p>
                            <div style="display: flex; gap: 12px; justify-content: center;">
                                <a data-toggle="modal" href="#styledModal" class="vault-action-btn primary demo-element" data-backdrop="static" data-keyboard="false">
                                    <i class="fa fa-plus"></i> New Wallet
                                </a>
                                <a data-backdrop="static" data-keyboard="false" data-toggle="modal" href="#modalLogin" class="vault-action-btn secondary demo-element">
                                    <i class="fa fa-link"></i> Connect Wallet
                                </a>
                            </div>
                        </div>
                    @endif



            </div> <!-- /.container -->


        </div> <!-- .content -->


    </div> <!-- /#wrapper -->
    <!--------------------------------------->
    <div id="unlockWalletModal" class="modal modal-styled fade" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" style="width: 600px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title">Unlock Wallet &#8220;<span class="unlock-name"></span>&#8221;</h3>
                </div>
                <form class="form account-form " id="wallet-unlocker" method="POST" action="/wallet/dashboard/hd-open">
                    @csrf
                    <div class="" style="padding: 3rem; padding-top: 0px; display: flex; justify-content: center; flex-direction: column">
                        <div class="row">
                            <h2 class="unlock-addy"></h2>
                        </div>
                        <div class="row">
                            <input name="wallet" hidden id="selected_wallet"/>
                            <label for="name">Wallet Password</label>
                            <input type="password" id="unlock-password" name="unlock-password" class="form-control"
                                data-required="true" style="width: 100%">
                            <div id="unlock-error" style="display: none; margin-top: 12px; padding: 10px 14px; background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); border-radius: 6px; color: #ef4444; font-size: 14px;">
                                <i class="fa fa-exclamation-circle"></i> <span id="unlock-error-text"></span>
                            </div>
                            <div class="row d-flex justify-content-center text-center" style="padding-top: 3rem;">
                                <button id="unlock-wallet" type="submit" class="btn btn-primary">Unlock</button>
                                <img src="/assets/citizen/loading.gif" alt="Processing..." style="display: none; height: 30px; margin-left: 10px;" id="unlock-loading">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--------------------------------------->
    <div id="renameWalletModal" class="modal modal-styled fade" data-keyboard="true">
        <div class="modal-dialog" style="width: 600px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title">Rename Wallet</h3>
                </div>
                <div class="modal-body" style="padding: 3rem; display: flex; justify-content: center;flex-direction: column; height: 300px;">
                    <div class="form-group">
                        <label>Current Name</label>
                        <input type="text" class="form-control" id="currentName" disabled>
                    </div>
                    <div class="form-group">
                        <label>New Name</label>
                        <input type="text" class="form-control" id="newName" required>
                    </div>
                    <div class="row d-flex justify-content-center text-center" style="padding-top: 5rem;">
                                    <button type="button" class="btn btn-primary" onclick="renameWallet()"  
                                        style="">Save Changes</button>
                    </div>
                
                </div>
            </div>
        </div>
    </div>

    


    <!------- OPEN WALLET Modal Start ------->
    <div id="styledModal" class="modal modal-styled fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 class="modal-title">Open MARS Wallet</h3>
                </div>
                <form class="form account-form" method="POST" action="/wallet/createwallet">
                    @csrf
                    <div class="modal-body">
                        <div class="col-md-3 col-sm-5">
                            <ul id="myTab" class="nav nav-pills nav-stacked">
                                <li class="active tab-1 tab">
                                    <a href="#entropy" data-toggle="tab"><i class="fa fa-puzzle-piece"></i>
                                        &nbsp;&nbsp;Entropy
                                    </a>
                                </li>
                                <li class="disabled tab-2 tab">
                                    <a href="#mnemonic" data-toggle="tab"><i class="fa fa-key disabled"></i>
                                        &nbsp;&nbsp;Mnemonic
                                    </a>
                                </li>
                                <li class="disabled tab-3 tab">
                                    <a href="#done" data-toggle="tab"><i class="fa fa-rocket disabled"></i>
                                        &nbsp;&nbsp;Generate Wallet
                                    </a>
                                </li>
                            </ul>
                        </div> 
                        <div id="myTabContent" class="col-md-9 col-sm-7 tab-content stacked-content">

                            {{-- <form class="" method="POST" action="/wallet/createwallet"> --}}

                            <div class="tab-pane fade active in" id="entropy">
                                <div>
                                    <div class="title-help">
                                        <h2> Generate Entropy </h2>
                                        <a class="btn btn-default demo-element ui-popover" data-toggle="tooltip"
                                            data-placement="right" data-trigger="hover"
                                            data-content="Generate randomness by wiggling your mouse inside the box. The more you wiggle the more random your private key will be. The more random your key is, the more secure it will be."
                                            title="" data-original-title="Entropy" href="#"><i
                                                class="fa fa-question-circle"></i></a>

                                    </div>


                                    <p> <strong>Note: </strong>
                                        Wiggle your mouse inside the box to create extra randomness when generating your
                                        wallets
                                        private key
                                    </p>

                                    <div class="container mouse-box">
                                    </div>

                                    <div id="progress-counter" style="font-size: 48px; position: absolute; top: 40%; left: 50%; transform: translate(-50%, -50%); z-index: 1001; color: #d74b4b;font-weight: 800">0%</div>


                                    <div class="progress progress-striped active">
                                        <div id="entropy-progress" class="progress-bar progress-bar-primary"
                                            role="progressbar" aria-valuenow="0" aria-valuemin="0"
                                            aria-valuemax="100" style="width: 0%">
                                            <span class="sr-only">0% Complete (primary)</span>
                                        </div>
                                    </div>

                                    <div class="success" style="display: none">
                                        <strong>
                                            <h4 class="success-message"> Randomness Complete <i
                                                    class="fa fa-check-circle-o" style="color: rgb(37, 206, 37)"></i>
                                            </h4>

                                        </strong>
                                    </div>

                                </div>

                                <div class="next-btn">
                                    <button href="#mnemonic" id="next-entropy" type="button" class="btn btn-primary" style="display: none">Next</button>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="mnemonic" style="display: none" href="#mnemonic">
                                <div>
                                    <div class="title-help">

                                        <h2> Mnemonic </h2>

                                        <a class="btn btn-default left-margin  demo-element ui-popover"
                                            data-toggle="tooltip" data-placement="right" data-trigger="hover"
                                            data-content="This seed phrase is the key to your wallet. Write it down and store it somewhere safely or you can lose your funds."
                                            title="" data-original-title="Mnemonic = Seed Phrase"
                                            href="#"><i class="fa fa-question-circle"></i></a>
                                    </div>
                                    <p> <strong>Note: </strong> Write down your mnemonic. Without it you will not be
                                        able to
                                        log
                                        back in to your wallet. </p>

                                    <div class="mnemonic">
                                        <h2 class="mnemonic-text"></h2>
                                    </div>
                                    <div class="title-help">
                                        <h2> Backup Wallet </h2>
                                        <a class="btn btn-default left-margin demo-element ui-popover"
                                            data-toggle="tooltip" data-placement="right" data-trigger="hover"
                                            data-content="We will encrypt your mnemonic with a password you create in your browser. MartianRepublic.org will never have access to your wallet."
                                            title="" data-original-title="Backup Your Wallet" href="#"><i
                                                class="fa fa-question-circle"></i></a>
                                        {{-- <span> (Optional) </span> --}}

                                    </div>
                                    <div>
                                        <div class="btn-group " data-toggle="buttons">
                                            <label id="backup-phrase" class="btn btn-default active"
                                                style="width: 125px;">
                                                <input type="radio" name="options" id="option1"> Backup Phrase
                                            </label>
                                            {{-- <label id="no-backup-phrase" class="btn btn-default"
                                                style="width: 125px;">
                                                <input type="radio" name="options" id="option2"> No Backup
                                            </label> --}}
                                        </div>
                                        <div class="form-group password-encrypt-cont">
                                            <div class="password-encrypt">
                                                <label for="name">Password</label>
                                                <input type="password" id="password" name="password"
                                                    class="form-control parsley-validated" data-required="true" autocomplete="new-password">
                                                <label for="name">Re-Type Password</label>
                                                <input type="password" id="re-password" name="re-password"
                                                    class="form-control parsley-validated" data-required="true"  autocomplete="new-password">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="next-btn" style="margin-top: -10px;">
                                    <button id="next-mnemonic" type="button" class="btn btn-primary ">Next</button>
                                </div>
                            </div> <!-- /.tab-pane -->

                            <div class="tab-pane fade" id="done">
                                <h2>Open Wallet</h2>
                                <input class="addr" id="public_addr" name="public_addr" style="display: none" />
                                <p>Public Address</p>
                                <div class="pub-addr">
                                    <h3 class="addr" name="public_addr" id="publicAddr"></h3>
                                    <i class="fa fa-copy copy-icon" onclick="copyToClipboard()"> </i>
                                </div>
                                <div class="row">
                                    <p>Wallet Name</p>
                                    <input placeholder="MARS" class="form-control" name="wallet_name" maxlength="500" placeholder="Enter wallet name" value="MARS" />
                                </div>
                                <div class="row d-flex justify-content-center text-center" style="padding-top: 10px;">
                                    <button id="make-wallet" type="submit" class="btn btn-primary" style="">Open Wallet</button>
                                </div>
                            </div> <!-- /.tab-pane -->
                            {{-- </form > --}}
                        </div>
                    </div> <!-- /.modal-body -->
                </form>
                <!-- /.modal-footer -->
            </div> <!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!------- OPEN WALLET Modal End ------->
    <!------------------------------------->

    <div id="modalLogin" class="modal modal-styled fade">

        <div class="modal-dialog">

            <div class="modal-content">


                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 class="modal-title">Marscoin Wallet Login</h3>
                </div> <!-- /.modal-header -->


                {{-- <form class="form account-form" method="POST" action="/wallet/getwallet"> --}}

                <div class="modal-body ">

                    <div class="col-md-3 col-sm-5">

                        <ul id="loginTabs" class="nav nav-pills nav-stacked">
                            <li class="active">
                                <a href="#seedPhrase" data-toggle="tab"><i class="fa fa-puzzle-piece"></i>
                                    &nbsp;&nbsp;Unlock with Mnemonic
                                </a>
                            </li>
                            @if (!empty($encrypted_seed) && $encrypted_seed !== 'UNSET')
                                <li>
                                    <a href="#passwordLogin" data-toggle="tab"><i class="fa fa-key"></i>
                                        &nbsp;&nbsp;Unlock with Password
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a href="#importWallet" data-toggle="tab"><i class="fa fa-upload"></i>
                                    &nbsp;&nbsp;Unlock with Keyfile
                                </a>
                            </li>

                        </ul>

                    </div> <!-- /.col -->


                    <div id="loginTabsContent" class="col-md-9 col-sm-7 tab-content stacked-content">

                        {{-- <form class="" method="POST" action="/wallet/createwallet"> --}}


                        <div class="tab-pane fade active in" id="seedPhrase">
                            <form class="form account-form wallet-getter-mnem" method="GET" action="">
                                <div class="row"
                                    style="display: flex; align-items: center; justify-content: center">
                                    <h3> Input seed phrase (mnemonic) in order </h3>
                                </div>
                                <div class="row"
                                    style="display: flex; align-items: center; justify-content: center">
                                    <div class="col-lg-10"
                                        style='width: 100%; display: flex; align-items: center; justify-content: center; flex-wrap: wrap; margin: 30px'>

                                        {{-- <label for="wallet-login-1">1</label> --}}
                                        <input name="wallet-login-1" id="wallet-login-1" class="seed-input"
                                            style="" placeholder="1." />

                                        {{-- <label for="wallet-login-2">2</label> --}}
                                        <input name="wallet-login-2" id="wallet-login-2" class="seed-input"
                                            style="" placeholder="2." />

                                        {{-- <label for="wallet-login-1">3</label> --}}
                                        <input name="wallet-login-3" id="wallet-login-3" class="seed-input"
                                            style="" placeholder="3." />

                                        {{-- <label for="wallet-login-1">4</label> --}}
                                        <input name="wallet-login-4" id="wallet-login-4" class="seed-input"
                                            style="" placeholder="4." />

                                        {{-- <label for="wallet-login-1">5</label> --}}
                                        <input name="wallet-login-5" id="wallet-login-5" class="seed-input"
                                            style="" placeholder="5." />

                                        {{-- <label for="wallet-login-1">6</label> --}}
                                        <input name="wallet-login-6" id="wallet-login-6" class="seed-input"
                                            style="" placeholder="6." />

                                        {{-- <label for="wallet-login-1">7</label> --}}
                                        <input name="wallet-login-7" id="wallet-login-7" class="seed-input"
                                            style="" placeholder="7." />

                                        {{-- <label for="wallet-login-1">8</label> --}}
                                        <input name="wallet-login-8" id="wallet-login-8" class="seed-input"
                                            style="" placeholder="8." />

                                        {{-- <label for="wallet-login-1">9</label> --}}
                                        <input name="wallet-login-9" id="wallet-login-9" class="seed-input"
                                            style="" placeholder="9." />

                                        {{-- <label for="wallet-login-1">10</label> --}}
                                        <input name="wallet-login-10" id="wallet-login-10" class="seed-input"
                                            style="" placeholder="10." />

                                        {{-- <label for="wallet-login-1">11</label> --}}
                                        <input name="wallet-login-11" id="wallet-login-11" class="seed-input"
                                            style="" placeholder="11." />

                                        {{-- <label for="wallet-login-1">12</label> --}}
                                        <input name="wallet-login-12" id="wallet-login-12" class="seed-input"
                                            style="" placeholder="12." />



                                    </div>
                                </div>
                                <div class="row"
                                    style="display: flex; align-items: center; justify-content: center">
                                    <div class="col-sm-12"
                                        style="display: flex; align-items: center; justify-content: center">
                                        <button id="login-wallet-mnemonic" type="button" class="btn btn-primary"
                                            style="width: 20%; margin: 30px">Unlock</button>

                                    </div>
                                </div>
                            </form>

                        </div>
                        @if (!empty($encrypted_seed) && $encrypted_seed !== 'UNSET')
                            <div class="tab-pane fade" id="passwordLogin">
                                <div style="padding: 20px 0;">
                                    <h2 style="font-family: 'Orbitron', sans-serif; font-size: 14px; font-weight: 600; letter-spacing: 1.5px; text-transform: uppercase; color: #fff; margin-bottom: 8px;">
                                        <i class="fa fa-key" style="color: var(--mr-cyan); margin-right: 8px;"></i> Unlock with Password
                                    </h2>
                                    <p style="font-family: 'JetBrains Mono', monospace; font-size: 11px; color: var(--mr-text-dim); margin-bottom: 20px;">
                                        Enter the password you set when creating or backing up your wallet.
                                    </p>
                                    <form class="form account-form wallet-getter" method="GET" action="/wallet/getwallet">
                                        <label style="font-family: 'JetBrains Mono', monospace; font-size: 10px; letter-spacing: 1.5px; text-transform: uppercase; color: var(--mr-text-dim); margin-bottom: 6px;">Wallet Password</label>
                                        <input type="password" id="wallet-password" name="password"
                                            class="form-control" data-required="true" style="margin-bottom: 16px;"
                                            placeholder="Enter your backup password">
                                        <button id="login-wallet-password" type="submit"
                                            class="btn btn-primary" style="width: 100%; padding: 12px !important;">
                                            <i class="fa fa-unlock" style="margin-right: 6px;"></i> Unlock Wallet
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif


                        <div class="tab-pane fade in" id="importWallet">
                            <div style="padding: 20px 0;">
                                <h2 style="font-family: 'Orbitron', sans-serif; font-size: 14px; font-weight: 600; letter-spacing: 1.5px; text-transform: uppercase; color: #fff; margin-bottom: 20px;">
                                    <i class="fa fa-file-import" style="color: var(--mr-cyan); margin-right: 8px;"></i> Import Keyfile
                                </h2>
                                <div style="border: 2px dashed var(--mr-border-bright, rgba(255,255,255,0.12)); border-radius: 10px; padding: 32px 24px; text-align: center; margin-bottom: 16px; transition: border-color 0.2s;" id="dropzone-area">
                                    <i class="fa fa-cloud-arrow-up" style="font-size: 32px; color: var(--mr-text-faint, #5a5968); display: block; margin-bottom: 12px;"></i>
                                    <div style="font-family: 'JetBrains Mono', monospace; font-size: 11px; color: var(--mr-text-dim, #8a8998); margin-bottom: 12px;">
                                        Select your <strong style="color: #fff;">marswallet.json</strong> backup file
                                    </div>
                                    <label for="jsonFile" style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 20px; background: var(--mr-surface-raised, #1a1a2a); border: 1px solid var(--mr-border-bright, rgba(255,255,255,0.12)); border-radius: 6px; cursor: pointer; font-family: 'JetBrains Mono', monospace; font-size: 10px; letter-spacing: 1px; text-transform: uppercase; color: var(--mr-text-dim); transition: all 0.2s;" onmouseover="this.style.borderColor='var(--mr-cyan)';this.style.color='var(--mr-cyan)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.12)';this.style.color='var(--mr-text-dim)'">
                                        <i class="fa fa-folder-open"></i> Choose File
                                    </label>
                                    <input type="file" id="jsonFile" accept=".json" style="display: none;" />
                                    <div id="selected-file-name" style="font-family: 'JetBrains Mono', monospace; font-size: 10px; color: var(--mr-text-faint); margin-top: 8px;"></div>
                                </div>
                                <button class="btn btn-primary" id="uploadButton" style="width: 100%; padding: 12px !important;">
                                    <i class="fa fa-upload" style="margin-right: 6px;"></i> Import & Unlock
                                </button>
                            </div>
                            <script>
                                document.getElementById('jsonFile').addEventListener('change', function() {
                                    var name = this.files[0] ? this.files[0].name : '';
                                    document.getElementById('selected-file-name').textContent = name;
                                    if (name) document.getElementById('dropzone-area').style.borderColor = 'var(--mr-cyan)';
                                });
                            </script>
                        </div>








                    </div>







                </div>

                {{-- </form> --}}
            </div>
        </div>
    </div>



    <!------- Footer Start ------->

    <footer class="footer" style="border-top: 1px solid var(--mr-border, rgba(255,255,255,0.06)); padding: 20px 0; background: var(--mr-void, #06060c);">
        @include('footer')
    </footer>

    <script src="/assets/wallet/js/dist/bundle.js"></script>
    <script src="/assets/wallet/js/dist/my_bundle.js"></script>
    <script src="/assets/wallet/js/libs/jquery-1.10.2.min.js"></script>
    <script src="/assets/wallet/js/libs/bootstrap.min.js"></script>
    <script src="/assets/wallet/js/jquery.slimscroll.min.js"></script>
    <script src="/assets/wallet/js/plugins/magnific/jquery.magnific-popup.min.js"></script>
    <script src="/assets/wallet/js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="/assets/wallet/js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script src="/assets/wallet/js/plugins/magnific/jquery.magnific-popup.js"></script>
    <script src="/assets/wallet/js/mvpready-core.js"></script>
    <script src="/assets/wallet/js/mvpready-admin.js"></script>
    <script src="/assets/wallet/js/mvpready-helpers.js"></script>
    <script src="/assets/wallet/js/demos/table_demo.js"></script>
    <script type="text/javascript">
    
    let selected_wallet = null;
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
    });  
    function copyToClipboard() 
    {
        const copyText = document.getElementById("publicAddr").innerText;
        const textArea = document.createElement("textarea");
        textArea.value = copyText;
        document.body.appendChild(textArea);
        textArea.select();
        textArea.setSelectionRange(0, 99999); // For mobile devices
        try {
            document.execCommand("copy");
            console.log("Text copied to clipboard");
        } catch (err) {
            console.error("Failed to copy text", err);
        }
        document.body.removeChild(textArea);
    }

    function renameWallet() {
        var walletId = $('.rename-wallet-button').data('wallet-id');
        var newName = $('#newName').val();

        $.post('/api/rename', {
            hdwallet_id: walletId,
            new_name: newName,
        }, function(data) {
            if (data.success) {
                $('#renameWalletModal').modal('hide');
                location.reload();
            }
        });
    }

    $('#renameWalletModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);  
        var walletId = button.data('wallet-id'); 
        var walletName = button.data('wallet-name');
        $('#currentName').val(walletName); 
    });

    $('#unlockWalletModal').on('show.bs.modal', function (event) {
        var card = $(event.relatedTarget);
        var walletId = card.data('wallet-id');
        $('#selected_wallet').val(walletId);

        var data = JSON.parse(card.attr('data-wallet'))

        selected_wallet = data

        $("#unlockWalletModal .unlock-name").text(data.wallet_type)
        $("#unlockWalletModal .unlock-addy").text(data.public_addr)

        // Reset error state
        $("#unlock-error").hide();
        $("#unlock-loading").hide();
        $("#unlock-password").val('').css('border-color', '');
    });

    $(document).ready(function() {

            let iv = "{{ json_encode($iv) }}".replace("]", "").replace("[", "").split(",");
            iv = new Uint8Array(iv);

        
            $('.wallet-card-link').on("click", function(e) {

                var data = JSON.parse($(this).attr('data-wallet'))
                selected_wallet = data
                $("#unlockWalletModal .unlock-name").text(data.wallet_type)
                $("#unlockWalletModal .unlock-addy").text(data.public_addr)

            })

            $("#next-mnemonic").click(() => {

                $(".dot").hide()

                // Check if the password fields are empty
                var password = $('#password').val().trim();
                var rePassword = $('#re-password').val().trim();

                if (password === '' && rePassword === '') {
                    var userConfirmed = confirm("Warning: If you do not provide a password, you will not be able to unlock the wallet by password and access may be lost unless you have written down the seed phrase or downloaded the keyfile. Do you want to continue without setting a password?");
                    if (!userConfirmed) {
                        return;
                    }
                }

                // Mnemonic confirmation - pick 3 random words for user to verify
                const mnemWords = $('.mnemonic-text').html().trim().split(/\s+/);
                const indices = [];
                while (indices.length < 3) {
                    const idx = Math.floor(Math.random() * 12);
                    if (!indices.includes(idx)) indices.push(idx);
                }
                indices.sort((a, b) => a - b);

                const confirmHtml = `
                    <div style="text-align: center; padding: 20px;">
                        <h4 style="color: var(--mr-mars, #c84125); margin-bottom: 16px;">Verify Your Seed Phrase</h4>
                        <p style="color: var(--mr-text-dim, #8a8998); margin-bottom: 24px;">
                            Please enter the following words from your seed phrase to confirm you've saved it.
                        </p>
                        <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
                            ${indices.map(idx => `
                                <div style="text-align: center;">
                                    <label style="color: var(--mr-text-dim); font-size: 12px;">Word #${idx + 1}</label>
                                    <input type="text" class="seed-input mnemonic-verify-input" data-word-index="${idx}"
                                        style="display: block; text-align: center;" placeholder="Word ${idx + 1}" autocomplete="off">
                                </div>
                            `).join('')}
                        </div>
                        <div style="margin-top: 24px;">
                            <button class="btn btn-primary" id="confirm-mnemonic-btn">Confirm & Continue</button>
                            <button class="btn btn-secondary" id="cancel-mnemonic-btn" style="margin-left: 10px;">Go Back</button>
                        </div>
                        <p id="mnemonic-verify-error" style="color: var(--mr-red, #ef4444); margin-top: 12px; display: none;">
                            Incorrect words. Please check your seed phrase and try again.
                        </p>
                    </div>`;

                // Show verification in the mnemonic tab area
                const origContent = $('#mnemonic .next-btn').html();
                $('#mnemonic .next-btn').html(confirmHtml);
                $('#next-mnemonic').hide();

                $('#confirm-mnemonic-btn').click(() => {
                    let allCorrect = true;
                    $('.mnemonic-verify-input').each(function() {
                        const idx = parseInt($(this).data('word-index'));
                        const entered = $(this).val().trim().toLowerCase();
                        if (entered !== mnemWords[idx].toLowerCase()) {
                            allCorrect = false;
                        }
                    });

                    if (allCorrect) {
                        // Verification passed - proceed to wallet generation
                        $(".tab-3").removeClass("disabled").addClass("active")
                        $(".tab-2").removeClass("active")
                        $("#mnemonic").removeClass("active in")
                        $("#done").show()
                        $("#done").addClass("active in")
                        $("#next-mnemonic").hide();
                        $("#mnemonic").hide()
                        $("#next-entropy").hide()
                    } else {
                        $('#mnemonic-verify-error').show();
                        $('.mnemonic-verify-input').css('border-color', 'var(--mr-red, #ef4444)');
                    }
                });

                $('#cancel-mnemonic-btn').click(() => {
                    $('#mnemonic .next-btn').html(origContent);
                    $('#next-mnemonic').show();
                });

            })

            $(".tab-1").click(() => {
                $("#entropy").show()
                $("#mnemonic").hide()
                $("#done").hide()
            })
            $(".tab-2").click(() => {
                $("#entropy").hide()
                $("#mnemonic").show()
                $("#done").hide()
            })
            $(".tab-3").click(() => {
                $("#entropy").hide()
                $("#done").show()
                $("#mnemonic").hide()
            })



            // Prevent going to other tabs in tab-pills
            $(".nav-pills li").on("click", function(e) {

                //console.log($(this))
                if ($(this).hasClass("disabled")) {
                    e.preventDefault();
                    return false;
                }
            });

            // ===================================================================
            // ================= Handle Password Client Encryption ui logic ======
            // ===================================================================
            $("#no-backup-phrase").click(function(e) {

                if ($(this).hasClass("active")) {
                    e.preventDefault;
                } else {

                    $(".password-encrypt").toggle();

                }
            });
            $("#backup-phrase").click(function(e) {

                if ($(this).hasClass("active")) {
                    e.preventDefault;
                } else {

                    $(".password-encrypt").toggle();
                }
            });

            if ($("#no-backup-phrase.active")) {

            } else {

            }


            $("#no-backup-phrase").click(() => {
                $('#password').val('');
                $("#re-password").val('');
            })

            // ===================================================================
            // ============== Handle Make Wallet w/ Password  ====================
            // ===================================================================

            function handleMakeWallet() {
                var mnem;
                var password;
                var re_password;
                var hashed_password;
                var hashed_re_password;

                var encrypted_mnem;

                // ensure marswallet.json conf is retreived
                //var salt = "{{ $SALT }}"


                $('#password').on('input', function() {

                    mnem = $('.mnemonic-text').html();
                    password = $('#password').val().replace(/\s+/g, '');

                    // hashed_password = my_bundle.pbkdf2.pbkdf2Sync(
                    //     password,
                    //     "{{ $SALT }}", 1, 16, 'sha512').toString('hex')

                    hashed_password = hashPassword(password)

                    // console.log("hashed-pass: ", hashed_password)

                    encrypted_mnem = my_bundle.encrypt(mnem, hashed_password, iv)

                    // console.log("enc:", encrypted_mnem)


                });


                $('#re-password').on('input', function() {

                    mnem = $('.mnemonic-text').html();
                    re_password = $("#re-password").val().replace(/\s+/g, '');

                    hashed_re_password = hashPassword(re_password)

                    //   console.log("HASHED RE-pass:", hashed_re_password)
                    //   console.log("enc-mnem:", encrypted_mnem)
                    //   console.log("dec-mnem:", my_bundle.decrypt(encrypted_mnem, hashed_re_password, iv))
                });


                // set the input as the encryption.
                $("#next-mnemonic").click(() => {
                    $("#password").val(encrypted_mnem);
                    $("#re-password").val(hashed_re_password);

                })

            }
            handleMakeWallet()

            const PBKDF2_ROUNDS = 100000;
            const PBKDF2_LEGACY_ROUNDS = 1;

            const hashPassword = (passcode) => {
                const ret = my_bundle.pbkdf2.pbkdf2Sync(
                    passcode,
                    "{{ $SALT }}", PBKDF2_ROUNDS, 16, 'sha512').toString('hex')
                return ret
            }

            // For unlocking wallets created with the old 1-round hash
            const hashPasswordLegacy = (passcode) => {
                const ret = my_bundle.pbkdf2.pbkdf2Sync(
                    passcode,
                    "{{ $SALT }}", PBKDF2_LEGACY_ROUNDS, 16, 'sha512').toString('hex')
                return ret
            }


            //
            // ===================================================================
            // ============= Generate MARS HD Wallet: input=> mnemonic ===========
            // ===================================================================


            // MARS Derivation Path
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

            const genSeed = (mnemonic) => {
                // console.log("SALT: {{ $SALT }}")
                //mnemonic = "invite feature forget axis radar stone bind squirrel dog crash 

                const seed = my_bundle.bip39.mnemonicToSeedSync(mnemonic.trim());
                const root = my_bundle.bitcoin.bip32.fromSeed(seed, Marscoin.mainnet)
                const child = root.derivePath("m/44'/2'/0'").neutered();
                let tpub = child.toBase58()
                const hdNode = my_bundle.bip32.fromBase58(tpub, Marscoin.mainnet)
                const node = hdNode.derive(0)
                const addy = nodeToLegacyAddress(node.derive(0))
                const publicKey = node.publicKey.toString('hex')
                const resp = {
                    address: addy,
                    pubKey: publicKey,
                    xprv: root.toBase58(),
                    mnemonic: mnemonic
                }
                return resp;
            };



            function nodeToLegacyAddress(hdNode) {
                return my_bundle.bitcoin.payments.p2pkh({
                    pubkey: hdNode.publicKey,
                    network: Marscoin.mainnet,
                }).address;
            }


            let captureStart = true;
            var entropy = [];


            let progress = 0;
            const maxEntropyLength = 24; // Define max entropy length

            // Update the dot styles globally
            var style = document.createElement('style');
            document.head.appendChild(style);
            style.sheet.insertRule(`
                .dot {
                    position: absolute;
                    width: 5px;
                    height: 5px;
                    border-radius: 50%;
                    background: black;
                    pointer-events: none;
                    z-index: 1000; // Make sure this is above the modal
                }`, 0);



            $(document).on("mousemove", ".mouse-box", function(e) {

                var mnemonic;
                const percent_increase = 5
                var increase = percent_increase * entropy.length

                // Entropy Logic
                const MAX_LEN = 24; // size of entropy's array
                const now = Date.now();
                if (now >= 1 && (now % 10) !== 0) return;

                // mouse movement cords
                const px = e.pageX;
                const py = e.pageY;

                var word_list_length = 2048
                var elem = $('.mouse-box')[0].getBoundingClientRect()
                var w = elem.width;
                var h = elem.height;

                var cell_dim = w / Math.sqrt(word_list_length);
                var cell_count = (w / cell_dim)

                var x_pos = (px - elem.left) / cell_dim;
                var y_pos = (py - elem.top) / cell_dim;

                //var cells = cell_dim * 

                var cell = x_pos + (cell_count * y_pos);
                var ret = Math.round(cell)


                entropy.push(ret)

                if (increase < 100) {
                    // Create and append dot
                    var dot = document.createElement('div');
                    dot.className = 'dot';
                    dot.style.left = `${e.clientX}px`;
                    dot.style.top = `${e.clientY}px`;
                    dot.style.zIndex = 1100;
                    document.body.appendChild(dot);
                    document.getElementById('progress-counter').innerText = `${increase.toFixed(1)}%`;
                }else{
                    document.getElementById('progress-counter').innerText = "100%";
                }




                // increase progress bar as entropy increases
                $("#entropy-progress").css("width", `${increase}%`)


                // Ensure enough entropy has been created
                // Once entropy is completed then...
                if (increase == 100) {


                    $("#entropy-progress").css("width", `100%`)
                    $(".success").show()
                    $("#next-entropy").show();

                    // =====================================================================================

                    // Generating seed with entropy
                    // 1) shuffle entropy
                    // 2) Select 16 numbers
                    // 3) entropyToMnemonic -> mnemonicToSeed...

                    shuffle(entropy);

                    var final_entropy = entropy.slice(0, 16)


                    mnemonic = my_bundle.bip39.entropyToMnemonic(final_entropy)

                    // use genSeed function to make seed
                    const wallet = genSeed(mnemonic)

                    //console.log(wallet)

                    $(".addr").html(wallet.address)
                    $(".addr").val(wallet.address)




                    // ======================================================================================

                    // NEXT Button dependent on entropy completing
                    $("#next-entropy").click((e) => {

                        $(".mnemonic-text").html(mnemonic);

                        $("#next").addClass("tab-2-unlocked tab-3 unlocked")

                        $(".tab-2").removeClass("disabled")
                        $(".tab-2").addClass("active")

                        $(".tab-1").removeClass("active")
                        $("#entropy").removeClass("active in")
                        $("#entropy").hide()
                        $("#mnemonic").show()
                        $("#mnemonic").addClass("active in")
                        $("#next-entropy").hide();
                        $(".dot").hide();

                    })
                }

                // =====================================================================
                // =====================================================================
                // Reset Modal on close
                $("#styledModal").on('hide.bs.modal', function() {
                    increase = 0;
                    entropy.length = 0;
                    $("#entropy-progress").css("width", `0%`)
                    $(".success-message").html("")
                    $("#next").hide();
                    $(".tab-2").addClass("disabled")
                    $(".tab-2").removeClass("active")
                    $("#entropy").addClass("active in")
                    $("#mnemonic").removeClass("active in")
                    $(".tab-1").addClass("active")
                    progress = 0; // Reset progress
                    $("#progress-counter").text('0%');
                    $("#entropy-progress").css("width", `0%`);

                });
                // =====================================================================
                // if (!validation) {
                //     e.preventDefault();
                //     return false;
                // }




                // Shuffle array helper function...
                function shuffle(array) {
                    let currentIndex = array.length,
                        temporaryValue, randomIndex;
                    // While there remain elements to shuffle...
                    while (0 !== currentIndex) {
                        // Pick a remaining element...
                        randomIndex = Math.floor(Math.random() * currentIndex);
                        currentIndex -= 1;
                        // And swap it with the current element.
                        temporaryValue = array[currentIndex];
                        array[currentIndex] = array[randomIndex];
                        array[randomIndex] = temporaryValue;
                    }
                    return array;
                }


            


            });


            // Check if the mnemonic is valid and gens a pubaddr.......
            const checkMnemonic = (mnemonic) => {

                const root = new my_bundle.BIP84.fromMnemonic(mnemonic, null, false, 107);

                const child = root.deriveAccount(0);

                const account = new my_bundle.BIP84.fromZPrv(child, false, MARSCOIN);

                let resp = {
                    address: account.getAddress(0, false, 49),
                }

            }


             // LOGIN USING MNEMONIC INPUT
            $('#login-wallet-mnemonic').click((e) => {
                e.preventDefault();

                // compile mnemonic
                var input_mnemonic = "";
                for (var i = 1; i < 13; i++) {
                    var mnem = $(`#wallet-login-${i}`).val();
                    input_mnemonic += `${mnem} `
                }

                let response;
                try {
                    response = genSeed(input_mnemonic);
                } catch (err) {
                    alert("Invalid seed phrase. Could not derive wallet address.");
                    console.error("genSeed failed:", err);
                    return false;
                }

                if (!response || !response.address) {
                    alert("Invalid seed phrase. Could not derive a valid wallet address. Please check your words and try again.");
                    return false;
                }

                if ("{{ $wallets }}") {
                    if (response.address == "{{ $public_addr }}") {
                        // Existing wallet - store mnemonic as key, then navigate
                        WalletKey.set(response.mnemonic)
                        console.log("Key stored for address:", response.address)
                        window.location.href = "/wallet/getwallet";
                        return false;
                    }
                    else if (response.address != "") {
                        // New/imported wallet - create via API, then redirect
                        var postData = {
                            password: '',
                            public_addr: response.address,
                            wallet_name: 'Imported'
                        };

                        $.post('/wallet/createwallet', postData)
                        .done(function(data) {
                            WalletKey.set(response.mnemonic);
                            location.href = "/wallet/dashboard/hd-open";
                        })
                        .fail(function(error) {
                            console.error("Error occurred: ", error);
                            alert("Failed to connect wallet. The server returned an error. Please try again.");
                        });
                        return false;
                    }
                    else {
                        $(".wallet-getter-mnem").attr("action", "/wallet/failwallet")
                    }
                }

            })

            // LOGIN USING KEYFILE
            document.getElementById('uploadButton').addEventListener('click', function() {
                var fileInput = document.getElementById('jsonFile');

                if (!fileInput.files.length) {
                    alert('Please select a file.');
                    return;
                }

                var file = fileInput.files[0];
                var reader = new FileReader();

                reader.onload = function(e) {
                    try {
                        var jsonData = JSON.parse(e.target.result);

                        if (jsonData.version === 2 && jsonData.encrypted) {
                            // Encrypted keyfile (v2) - prompt for password
                            const filePassword = prompt("This backup is encrypted. Enter the backup password:");
                            if (!filePassword) return;

                            try {
                                const rounds = jsonData.pbkdf2_rounds || PBKDF2_ROUNDS;
                                const hashed = my_bundle.pbkdf2.pbkdf2Sync(
                                    filePassword.trim(), "{{ $SALT }}", rounds, 16, 'sha512'
                                ).toString('hex');
                                const decrypted = my_bundle.decrypt(jsonData.data, hashed, iv).trim();
                                processMnemonic(decrypted);
                            } catch (decErr) {
                                alert("Failed to decrypt backup. Wrong password or corrupted file.");
                                console.error('Decryption error:', decErr);
                            }
                        } else {
                            // Unencrypted keyfile (v1/legacy)
                            var mnemonic = jsonData.key;
                            if (!mnemonic) {
                                alert("Invalid keyfile format. No key found.");
                                return;
                            }
                            processMnemonic(mnemonic);
                        }
                    } catch (error) {
                        alert('Error reading or parsing the file. Please check the file format.');
                        console.error('Error:', error);
                    }
                };

                reader.readAsText(file);
            });

            function processMnemonic(mnemonic) {
                mnemonic = mnemonic.trim();
                const response = genSeed(mnemonic);

                if (response && response.address) {
                    var postData = {
                        password: '',
                        public_addr: response.address,
                        wallet_name: 'Imported'
                    };

                    $.post('/wallet/createwallet', postData)
                    .done(function(data) {
                        WalletKey.set(response.mnemonic);
                        location.href = "/wallet/dashboard/hd-open";
                    })
                    .fail(function(error) {
                        console.error("Error occurred: ", error);
                        alert("Failed to connect wallet. The server returned an error. Please try again.");
                    });
                } else {
                    alert("Invalid seed phrase. Could not derive a valid wallet address. Please check your words and try again.");
                }
            }

            // LOGIN USING PASSWORD INPUT
            $('#login-wallet-password').click(() => {
                // console.log("SALT: {{ $SALT }}")
                // compile mnemonic

                var wallet_password = $("#wallet-password").val().replace(/\s+/g, '');
                const encrypted_mnem = "{{ $encrypted_seed }}".replace(/\s+/g, '');
                let decrypted = null;
                let usedLegacy = false;

                // Try new hash (100k rounds) first
                try {
                    const hashed = hashPassword(wallet_password);
                    decrypted = my_bundle.decrypt(encrypted_mnem, hashed, iv).trim();
                    const testResp = genSeed(decrypted);
                    if (testResp.address !== "{{ $public_addr }}") decrypted = null;
                } catch (e) { decrypted = null; }

                // Fall back to legacy (1 round)
                if (!decrypted) {
                    try {
                        const hashedLegacy = hashPasswordLegacy(wallet_password);
                        decrypted = my_bundle.decrypt(encrypted_mnem, hashedLegacy, iv).trim();
                        const testResp = genSeed(decrypted);
                        if (testResp.address !== "{{ $public_addr }}") {
                            decrypted = null;
                        } else {
                            usedLegacy = true;
                        }
                    } catch (e) { decrypted = null; }
                }

                if (decrypted) {
                    WalletKey.set(decrypted);
                    if (usedLegacy) {
                        // Re-encrypt with stronger hash on next createwallet call
                        console.log("Legacy wallet detected - upgrading encryption");
                        // Notify user about the security upgrade
                        setTimeout(() => {
                            if (typeof toastr !== 'undefined') {
                                toastr.info('Your wallet encryption has been automatically upgraded to a stronger standard.', 'Security Upgrade', {timeOut: 8000});
                            }
                        }, 2000);
                    }
                } else {
                    alert("Incorrect password. Please try again.");
                    $(".wallet-getter").attr("action", "/wallet/failwallet");
                }

            })



            // UNLOCK WALLET from list of wallets......
            // $('#unlock-wallet').click(() => {
            //     // console.log("SALT: {{ $SALT }}")
            //     // compile mnemonic
            //     console.log("unlocking...")

            //     var wallet_password = $("#unlock-password").val().replace(/\s+/g, '');
            //     // console.log(wallet_password)

            //     const hashed = hashPassword(wallet_password);


            //     const user_wallet = selected_wallet
            //     //console.log("hashed:", hashed)

            //     const encrypted_mnem = user_wallet.encrypted_seed.replace(/\s+/g, '');
            //     //const encrypted = my_bundle.encrypt("face they lemon ignore link crop above thing buffalo tide category soup", hashed)
            //     //console.log("Encrypted: ", encrypted)

            //     const decrypted = my_bundle.decrypt(encrypted_mnem, hashed, iv).trim()

            //     // console.log("Encrypted SEED: {{ $encrypted_seed }}")
            //     // console.log("MNEM:", decrypted)


            //     const response = genSeed(decrypted)ion




            //     // console.log("response:", response)
            //     if (response.address == user_wallet.public_addr) {
            //         // Logging in was successful... Opening wallet...
            //         // WalletKey.set(decrypted)
            //         localStorage.setItem("key", encrypted_mnem)


            //         //      console.error("Item Succesfully locally stored")
            //     } else {


            //         $(".wallet-getter").attr("action", "/wallet/failwallet")


            //     }
            //     // Logging in was NOT-successful... Prompting user to retry login.

            // })


            $("#unlock-wallet").click(function(e) {
                // do your validation here ...


                var validated = false;


                console.log("unlocking...")
                $("#unlock-error").hide();
                $("#unlock-loading").show();
                $("#unlock-password").css('border-color', '');

                var wallet_password = $("#unlock-password").val().replace(/\s+/g, '');
                const user_wallet = selected_wallet;
                const encrypted_mnem = user_wallet.encrypted_seed.replace(/\s+/g, '');

                // Try new PBKDF2 (100k rounds) first, then fall back to legacy (1 round)
                let decrypted = null;
                let usedLegacy = false;

                // Try new hash
                try {
                    const hashed = hashPassword(wallet_password);
                    decrypted = my_bundle.decrypt(encrypted_mnem, hashed, iv).trim();
                    const testResponse = genSeed(decrypted);
                    if (testResponse.address !== user_wallet.public_addr) {
                        decrypted = null; // Address mismatch, try legacy
                    }
                } catch (err) {
                    decrypted = null; // Decryption failed, try legacy
                }

                // Fall back to legacy hash (1 round) for wallets created before security upgrade
                if (!decrypted) {
                    try {
                        const hashedLegacy = hashPasswordLegacy(wallet_password);
                        decrypted = my_bundle.decrypt(encrypted_mnem, hashedLegacy, iv).trim();
                        const testResponse = genSeed(decrypted);
                        if (testResponse.address !== user_wallet.public_addr) {
                            decrypted = null;
                        } else {
                            usedLegacy = true;
                            console.log("Unlocked with legacy hash - will re-encrypt with stronger hash");
                        }
                    } catch (err) {
                        decrypted = null;
                    }
                }

                if (!decrypted) {
                    $("#unlock-error-text").text("Incorrect password. Please check your password and try again.");
                    $("#unlock-error").show();
                    $("#unlock-loading").hide();
                    $("#unlock-password").css('border-color', '#ef4444').focus();
                    e.preventDefault();
                    return false;
                }

                let response;
                try {
                    response = genSeed(decrypted);
                } catch (err) {
                    $("#unlock-error-text").text("Failed to derive wallet. The wallet data may be corrupted.");
                    $("#unlock-error").show();
                    $("#unlock-loading").hide();
                    e.preventDefault();
                    return false;
                }

                if (response.address == user_wallet.public_addr) {
                    flushLocalStorage()
                    WalletKey.set(decrypted)
                    $("#selected_wallet").val(JSON.stringify(selected_wallet))

                    // If unlocked with legacy hash, re-encrypt with stronger hash
                    if (usedLegacy) {
                        try {
                            const newHashed = hashPassword(wallet_password);
                            const reEncrypted = my_bundle.encrypt(decrypted, newHashed, iv);
                            $.post('/wallet/createwallet', {
                                password: reEncrypted,
                                public_addr: user_wallet.public_addr,
                                wallet_name: user_wallet.wallet_type || 'Upgraded'
                            });
                            console.log("Wallet re-encrypted with 100k PBKDF2 rounds");
                            setTimeout(() => {
                                if (typeof toastr !== 'undefined') {
                                    toastr.info('Your wallet encryption has been automatically upgraded to a stronger standard.', 'Security Upgrade', {timeOut: 8000});
                                }
                            }, 2000);
                        } catch (upgradeErr) {
                            console.warn("Failed to upgrade encryption, continuing with legacy:", upgradeErr);
                        }
                    }

                    return true;
                } else {
                    alert("Wallet unlock failed. The derived address does not match. Please check your password and try again.");
                    e.preventDefault();
                    return false;
                }

                // if (!validation) {

                // }


            });




            // test mnemonic...
            //eternal robot record fade pretty best stem movie recycle spend legend fence

            //retrieveWallet()


            const retrieveWalletPassword = () => {
                $('#login-wallet-mnemonic').click(() => {


                })



            }

            // =================================================================================================
            // =================================================================================================
            // ================================================================================================= 

            function flushLocalStorage() {


                localStorage.clear();
                WalletKey.clear();


                // fallback double check if key exists...
                if ("key" in localStorage)
                    localStorage.clear()

                return
            }


        });


        $('.delete-wallet-button').click(function() {
        var walletId = $(this).data('wallet-id');
        if (confirm('Are you sure you want to delete this wallet?')) {
            $.ajax({
                url: '/wallet/forget', // Adjust the URL to your endpoint
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token
                    hdwallet_id: walletId
                },
                success: function(result) {
                    // Remove the wallet element from the DOM or refresh the page
                    location.reload(); // This is a simple way to refresh the page
                },
                error: function(xhr, status, error) {
                    // Handle error
                    alert('Error: Wallet could not be deleted.');
                }
            });
        }
    });


</script>

<script src="/assets/wallet/js/demos/parsley.js"></script>
<script src="/assets/wallet/js/libs/jquery.steps.js"></script>
<script src="/assets/wallet/js/mvpready-core.js"></script>
<script src="/assets/wallet/js/mvpready-helpers.js"></script>
<script src="/assets/wallet/js/mvpready-admin.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js" integrity="sha512-A7AYk1fGKX6S2SsHywmPkrnzTZHrgiVT7GcQkLGDe2ev0aWb8zejytzS8wjo7PGEXKqJOrjQ4oORtnimIRZBtw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        AOS.init();
    </script>
@livewireScripts
</body>

</html>
