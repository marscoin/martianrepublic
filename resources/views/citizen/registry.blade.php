<html lang="en" class="no-js">
<head>
    <title>Marscoin Wallet</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald:400,300,700">
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@700&family=Orbitron:wght@500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/assets/wallet/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-admin.css?v=2">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-flat.css">
    <link rel="stylesheet" href="/assets/wallet/js/plugins/fileupload/bootstrap-fileupload.css">
    <link rel="shortcut icon" href="/assets/favicon.ico">
    <link rel="stylesheet" href="/assets/wallet/js/plugins/magnific/magnific-popup.css">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/assets/wallet/css/dashboard/dashboard.css?v=4.4">
    <style>
    /* ============================================ */
    /* THE CIVIC HALL: Citizen Registry Redesign    */
    /* ============================================ */
    html, body { background: #06060c !important; color: var(--mr-text, #e0dfe6) !important; }
    .footer { z-index: 100; position: relative; }
    .civic-page { min-height: 100vh; display: flex; flex-direction: column; }
    .civic-page .content { flex: 1; }
    .orbitron { font-family: 'Orbitron', sans-serif; font-weight: 800; }

    /* -- Hero -- */
    .civic-hero {
        padding: 28px 0 20px;
        position: relative;
    }
    .civic-hero::after {
        content: '';
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.08) 20%, var(--mr-cyan, #00e4ff) 50%, rgba(255,255,255,0.08) 80%, transparent);
    }
    .civic-status {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: var(--mr-cyan, #00e4ff);
        margin-bottom: 4px;
    }
    .civic-status .dot {
        display: inline-block; width: 6px; height: 6px; border-radius: 50%;
        background: var(--mr-green, #34d399);
        margin-right: 8px; vertical-align: middle;
        animation: civicPulse 2s infinite;
    }
    .civic-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 28px; font-weight: 800;
        color: #fff; letter-spacing: 2px;
        text-transform: uppercase; margin: 0;
    }

    /* -- Progress Tracker -- */
    .civic-progress {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0;
        padding: 20px 0;
        margin: 20px 0;
        background: var(--mr-surface, #12121e);
        border: 1px solid var(--mr-border, rgba(255,255,255,0.06));
        border-radius: 12px;
        flex-wrap: wrap;
    }
    .civic-step {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 16px;
    }
    .civic-step-icon {
        width: 36px; height: 36px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 14px;
        border: 2px solid;
        transition: all 0.3s;
    }
    .civic-step-icon.done { background: rgba(52,211,153,0.12); border-color: var(--mr-green, #34d399); color: var(--mr-green); }
    .civic-step-icon.current { background: rgba(0,228,255,0.12); border-color: var(--mr-cyan, #00e4ff); color: var(--mr-cyan); animation: civicPulse 2s infinite; }
    .civic-step-icon.locked { background: rgba(138,137,152,0.08); border-color: var(--mr-border-bright, rgba(255,255,255,0.12)); color: var(--mr-text-faint, #5a5968); }
    .civic-step-label {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px; letter-spacing: 1px;
        text-transform: uppercase;
    }
    .civic-step-label.done { color: var(--mr-green, #34d399); }
    .civic-step-label.current { color: var(--mr-cyan, #00e4ff); }
    .civic-step-label.locked { color: var(--mr-text-faint, #5a5968); }
    .civic-step-sub {
        font-family: 'JetBrains Mono', monospace;
        font-size: 8px; color: var(--mr-text-faint, #5a5968);
    }
    .civic-step-connector {
        width: 32px; height: 2px;
        background: var(--mr-border-bright, rgba(255,255,255,0.12));
    }
    .civic-step-connector.done { background: var(--mr-green, #34d399); }

    /* -- Tab Navigation -- */
    .civic-tabs {
        display: flex;
        gap: 4px;
        margin-bottom: 24px;
        border-bottom: 1px solid var(--mr-border, rgba(255,255,255,0.06));
        padding-bottom: 0;
        overflow-x: auto;
    }
    .civic-tabs .nav-pills { display: flex; gap: 4px; margin: 0; padding: 0; border: none; }
    .civic-tabs .nav-pills > li { float: none; }
    .civic-tabs .nav-pills > li > a {
        font-family: 'JetBrains Mono', monospace !important;
        font-size: 10px !important;
        letter-spacing: 1.5px !important;
        text-transform: uppercase !important;
        color: var(--mr-text-dim, #8a8998) !important;
        background: transparent !important;
        border: none !important;
        border-bottom: 2px solid transparent !important;
        border-radius: 0 !important;
        padding: 10px 16px !important;
        transition: all 0.2s !important;
        white-space: nowrap;
    }
    .civic-tabs .nav-pills > li > a:hover {
        color: #fff !important;
        border-bottom-color: rgba(255,255,255,0.2) !important;
    }
    .civic-tabs .nav-pills > li.active > a,
    .civic-tabs .nav-pills > li.active > a:hover {
        color: #fff !important;
        background: transparent !important;
        border-bottom-color: var(--mr-cyan, #00e4ff) !important;
    }
    .civic-tabs .badge {
        font-family: 'JetBrains Mono', monospace !important;
        font-size: 9px !important;
        background: var(--mr-mars, #c84125) !important;
        color: #fff !important;
        border-radius: 3px !important;
        padding: 2px 6px !important;
        margin-left: 6px;
    }

    /* -- Cards / Portlets -- */
    .civic-card {
        background: var(--mr-surface, #12121e);
        border: 1px solid var(--mr-border, rgba(255,255,255,0.06));
        border-radius: 10px;
        padding: 24px;
        margin-bottom: 20px;
    }
    .civic-card-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 12px; font-weight: 700;
        letter-spacing: 1.5px; text-transform: uppercase;
        color: #fff; margin-bottom: 16px;
    }
    .portlet {
        background: var(--mr-surface, #12121e) !important;
        border: 1px solid var(--mr-border, rgba(255,255,255,0.06)) !important;
        border-radius: 10px !important;
        padding: 24px !important;
        margin-bottom: 20px !important;
        box-shadow: none !important;
    }
    .portlet-title {
        font-family: 'Orbitron', sans-serif !important;
        font-size: 12px !important; font-weight: 700 !important;
        letter-spacing: 1.5px !important; text-transform: uppercase !important;
        color: #fff !important; border: none !important;
        text-decoration: none !important; margin-bottom: 16px !important;
    }
    .portlet-title u { text-decoration: none !important; }

    /* -- Section label -- */
    .civic-section {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px; letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--mr-text-dim, #8a8998);
        margin-bottom: 14px; padding-bottom: 8px;
        border-bottom: 1px solid var(--mr-border, rgba(255,255,255,0.06));
    }

    /* -- Citizen Table -- */
    .civic-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .civic-table thead th {
        font-family: 'JetBrains Mono', monospace !important;
        font-size: 9px !important; letter-spacing: 1.5px;
        text-transform: uppercase;
        color: var(--mr-text-dim) !important;
        border-bottom: 1px solid var(--mr-border) !important;
        background: transparent !important;
        padding: 8px 12px !important;
    }
    .civic-table tbody td {
        border-bottom: 1px solid var(--mr-border, rgba(255,255,255,0.04)) !important;
        color: var(--mr-text) !important;
        background: transparent !important;
        padding: 10px 12px !important;
        vertical-align: middle !important;
    }
    .civic-table tbody tr:hover td {
        background: rgba(255,255,255,0.02) !important;
    }
    .table { color: var(--mr-text) !important; }
    .table > thead > tr > th { border-bottom-color: var(--mr-border) !important; color: var(--mr-text-dim) !important; }
    .table > tbody > tr > td { border-top-color: var(--mr-border, rgba(255,255,255,0.04)) !important; }
    .table-striped > tbody > tr:nth-child(odd) > td { background: rgba(255,255,255,0.015) !important; }

    /* -- Citizen Avatar -- */
    .civic-avatar {
        width: 40px; height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--mr-border-bright, rgba(255,255,255,0.1));
    }

    /* -- Citizen Status Badge -- */
    .civic-badge {
        font-family: 'JetBrains Mono', monospace;
        font-size: 8px; letter-spacing: 1px;
        text-transform: uppercase;
        padding: 3px 8px; border-radius: 3px;
        display: inline-block;
    }
    .civic-badge.citizen { background: rgba(52,211,153,0.12); color: var(--mr-green); border: 1px solid rgba(52,211,153,0.25); }
    .civic-badge.public { background: rgba(0,228,255,0.1); color: var(--mr-cyan); border: 1px solid rgba(0,228,255,0.2); }
    .civic-badge.applicant { background: rgba(245,158,11,0.1); color: #f59e0b; border: 1px solid rgba(245,158,11,0.2); }
    .label-success { background: rgba(52,211,153,0.15) !important; color: var(--mr-green) !important; font-family: 'JetBrains Mono', monospace !important; font-size: 9px !important; }
    .label-info { background: rgba(0,228,255,0.12) !important; color: var(--mr-cyan) !important; }
    .label-warning { background: rgba(245,158,11,0.12) !important; color: #f59e0b !important; }

    /* -- Buttons -- */
    .btn-primary {
        background: var(--mr-mars, #c84125) !important;
        border-color: var(--mr-mars) !important;
        font-family: 'JetBrains Mono', monospace !important;
        font-size: 11px !important; letter-spacing: 1px !important;
        text-transform: uppercase !important;
        border-radius: 6px !important;
        transition: all 0.2s !important;
    }
    .btn-primary:hover { background: #d94e30 !important; box-shadow: 0 4px 16px rgba(200,65,37,0.3) !important; }
    .btn-default, .btn-secondary {
        background: var(--mr-dark, #0c0c16) !important;
        border: 1px solid var(--mr-border-bright, rgba(255,255,255,0.12)) !important;
        color: var(--mr-text-dim) !important;
        font-family: 'JetBrains Mono', monospace !important;
        font-size: 11px !important;
        border-radius: 6px !important;
    }
    .btn-success { background: rgba(52,211,153,0.15) !important; border-color: rgba(52,211,153,0.3) !important; color: var(--mr-green) !important; }
    .btn-danger { background: rgba(239,68,68,0.12) !important; border-color: rgba(239,68,68,0.25) !important; color: #ef4444 !important; }

    /* -- Forms -- */
    .form-control {
        background: var(--mr-dark, #0c0c16) !important;
        border: 1px solid var(--mr-border-bright, rgba(255,255,255,0.1)) !important;
        color: #fff !important;
        border-radius: 6px !important;
        font-family: 'JetBrains Mono', monospace !important;
        font-size: 13px !important;
        transition: border-color 0.2s !important;
    }
    .form-control:focus {
        border-color: var(--mr-cyan, #00e4ff) !important;
        box-shadow: 0 0 0 2px rgba(0,228,255,0.1) !important;
    }
    label { color: var(--mr-text-dim) !important; }
    textarea.form-control { min-height: 80px; }

    /* -- Modals -- */
    .modal-content {
        background: var(--mr-surface, #12121e) !important;
        border: 1px solid var(--mr-border-bright, rgba(255,255,255,0.1)) !important;
        border-radius: 12px !important;
        color: var(--mr-text) !important;
        box-shadow: 0 24px 80px rgba(0,0,0,0.6) !important;
    }
    .modal-header {
        background: var(--mr-dark, #0c0c16) !important;
        border-bottom: 1px solid var(--mr-border) !important;
        border-radius: 12px 12px 0 0 !important;
        padding: 16px 24px !important;
    }
    .modal-title {
        font-family: 'Orbitron', sans-serif !important;
        font-size: 13px !important; font-weight: 600 !important;
        letter-spacing: 1.5px !important; text-transform: uppercase !important;
        color: #fff !important;
    }
    .modal-body { background: var(--mr-surface, #12121e) !important; }
    .modal-footer { background: var(--mr-surface, #12121e) !important; border-top: 1px solid var(--mr-border) !important; }
    .modal-header .close { color: var(--mr-text-dim) !important; text-shadow: none !important; }
    .modal-backdrop { background: #000 !important; opacity: 0.85 !important; }
    .modal-backdrop.in { opacity: 0.85 !important; }
    .modal.fade.in { background: rgba(0,0,0,0.85); }
    .modal { z-index: 10500 !important; }
    .modal-dialog { z-index: 10501; }
    .modal-body-box { background: var(--mr-dark, #0c0c16) !important; border-radius: 8px; padding: 12px 16px; margin-bottom: 12px; }
    .modal-body-box h5 { font-family: 'JetBrains Mono', monospace !important; font-size: 9px !important; letter-spacing: 1.5px; text-transform: uppercase; color: var(--mr-text-dim) !important; margin-bottom: 6px; }
    .modal-body-box p { font-family: 'JetBrains Mono', monospace !important; font-size: 12px !important; color: var(--mr-cyan) !important; word-break: break-all; }
    .modal-cost { font-family: 'Orbitron', sans-serif !important; font-size: 16px !important; font-weight: 600 !important; color: #fff !important; }
    .modal-category { color: var(--mr-text-dim) !important; }

    /* -- Alerts -- */
    .alert { border-radius: 8px !important; }
    .alert-info { background: rgba(0,228,255,0.08) !important; border-color: rgba(0,228,255,0.2) !important; color: var(--mr-cyan) !important; }
    .alert-danger { background: rgba(200,65,37,0.1) !important; border-color: rgba(200,65,37,0.25) !important; color: var(--mr-mars) !important; }
    .alert-success { background: rgba(52,211,153,0.1) !important; border-color: rgba(52,211,153,0.25) !important; color: var(--mr-green) !important; }

    /* -- Sidebar/List Group -- */
    .list-group-item {
        background: var(--mr-surface, #12121e) !important;
        border-color: var(--mr-border) !important;
        color: var(--mr-text-dim) !important;
    }
    .list-group-item.active, .list-group-item:hover {
        background: var(--mr-surface-raised, #1a1a2a) !important;
        border-color: var(--mr-border-bright) !important;
        color: #fff !important;
    }

    /* -- Tab Content -- */
    .tab-content { color: var(--mr-text) !important; }
    .tab-pane { color: var(--mr-text) !important; }
    .nav-tabs { border-bottom-color: var(--mr-border) !important; }
    .nav-tabs > li > a { color: var(--mr-text-dim) !important; border: none !important; }
    .nav-tabs > li.active > a { color: #fff !important; background: transparent !important; border-bottom: 2px solid var(--mr-cyan) !important; }

    /* -- Thumbnail/Images -- */
    .thumbnail { background: var(--mr-dark) !important; border-color: var(--mr-border) !important; border-radius: 8px !important; }
    img.thumbnail, .thumbnail img { border-radius: 6px; }

    /* -- Profile specific -- */
    .profile-header { color: #fff !important; }
    .profile-header h2, .profile-header h3, .profile-header h4 { color: #fff !important; }

    /* -- Wallet Lock Message -- */
    .wallet-lock-msg {
        text-align: center; padding: 60px 20px;
        border: 1px dashed var(--mr-border-bright, rgba(255,255,255,0.1));
        border-radius: 12px; background: var(--mr-surface);
    }

    /* -- Footer -- */
    .footer { border-top: 1px solid var(--mr-border) !important; background: #06060c !important; }

    /* -- Animations -- */
    @keyframes civicPulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }
    @keyframes civicFadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .civic-fade-1 { animation: civicFadeIn 0.5s ease-out 0.1s both; }
    .civic-fade-2 { animation: civicFadeIn 0.5s ease-out 0.25s both; }
    .civic-fade-3 { animation: civicFadeIn 0.5s ease-out 0.4s both; }

    /* -- Misc -- */
    h1, h2, h3, h4, h5, h6 { color: #fff !important; }
    p { color: var(--mr-text) !important; }
    a { color: var(--mr-cyan, #00e4ff); }
    a.btn, a.gate-btn, a.vault-action-btn, a[style*="background: var(--mr-mars"] { color: #fff !important; }
    a:hover { color: #fff !important; }
    hr { border-color: var(--mr-border) !important; }
    .well { background: var(--mr-dark) !important; border-color: var(--mr-border) !important; border-radius: 8px !important; }
    .panel { background: var(--mr-surface) !important; border-color: var(--mr-border) !important; }
    .panel-heading { background: var(--mr-dark) !important; border-color: var(--mr-border) !important; color: #fff !important; }

    @media (max-width: 767px) {
        .civic-title { font-size: 20px; }
        .civic-progress { flex-direction: column; gap: 8px; }
        .civic-step-connector { width: 2px; height: 16px; }
        .civic-tabs .nav-pills > li > a { padding: 8px 10px !important; font-size: 9px !important; }
    }
    </style>
    <script>var current_blob = null;</script>
    @livewireStyles
</head>

<body class="civic-page">
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
        @include('wallet.mainnav', array('active'=>'citizen'))
        <div class="content">

            <div class="container">

                {{-- CIVIC HALL HERO --}}
                <div class="civic-hero civic-fade-1">
                    <div class="civic-status"><span class="dot"></span>Civic Registry — Active</div>
                    <h1 class="civic-title">Citizen</h1>
                </div>

                <?php if($wallet_open){ ?>

                {{-- Citizenship Progress Tracker (only show if NOT yet a full citizen) --}}
                @if(!$isCitizen)
                <div class="portlet civic-fade-1" style="padding: 20px 24px;">
                    <div style="display: flex; align-items: center; gap: 0; justify-content: center; flex-wrap: wrap;">
                        {{-- Step 1: Account --}}
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: rgba(16,185,129,0.2); border: 2px solid #34d399; display: flex; align-items: center; justify-content: center;">
                                <i class="fa fa-check" style="color: #34d399; font-size: 14px;"></i>
                            </div>
                            <span style="font-size: 13px; color: #34d399; font-weight: 500;">Account</span>
                        </div>
                        <div style="width: 40px; height: 2px; background: #34d399; margin: 0 4px;"></div>

                        {{-- Step 2: Wallet --}}
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: rgba(16,185,129,0.2); border: 2px solid #34d399; display: flex; align-items: center; justify-content: center;">
                                <i class="fa fa-check" style="color: #34d399; font-size: 14px;"></i>
                            </div>
                            <span style="font-size: 13px; color: #34d399; font-weight: 500;">Wallet</span>
                        </div>
                        <div style="width: 40px; height: 2px; background: {{ $isGP || $isCitizen ? '#34d399' : 'var(--mr-border, rgba(255,255,255,0.1))' }}; margin: 0 4px;"></div>

                        {{-- Step 3: Application --}}
                        <div style="display: flex; align-items: center; gap: 8px;">
                            @if($isGP || $isCitizen)
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: rgba(16,185,129,0.2); border: 2px solid #34d399; display: flex; align-items: center; justify-content: center;">
                                <i class="fa fa-check" style="color: #34d399; font-size: 14px;"></i>
                            </div>
                            <span style="font-size: 13px; color: #34d399; font-weight: 500;">Application</span>
                            @else
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: rgba(0,228,255,0.15); border: 2px solid var(--mr-cyan, #00e4ff); display: flex; align-items: center; justify-content: center;">
                                <span style="color: var(--mr-cyan, #00e4ff); font-size: 14px; font-weight: 700;">3</span>
                            </div>
                            <span style="font-size: 13px; color: var(--mr-cyan, #00e4ff); font-weight: 500;">Application</span>
                            @endif
                        </div>
                        <div style="width: 40px; height: 2px; background: {{ $isCitizen ? '#34d399' : 'var(--mr-border, rgba(255,255,255,0.1))' }}; margin: 0 4px;"></div>

                        {{-- Step 4: Citizen --}}
                        <div style="display: flex; align-items: center; gap: 8px;">
                            @if($isCitizen)
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: rgba(16,185,129,0.2); border: 2px solid #34d399; display: flex; align-items: center; justify-content: center;">
                                <i class="fa fa-check" style="color: #34d399; font-size: 14px;"></i>
                            </div>
                            <span style="font-size: 13px; color: #34d399; font-weight: 500;">Citizen</span>
                            @elseif($isGP)
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: rgba(0,228,255,0.15); border: 2px solid var(--mr-cyan, #00e4ff); display: flex; align-items: center; justify-content: center;">
                                <span style="color: var(--mr-cyan, #00e4ff); font-size: 14px; font-weight: 700;">4</span>
                            </div>
                            <span style="font-size: 13px; color: var(--mr-cyan, #00e4ff); font-weight: 500;">{{ count($endorsed ?? []) }}/3 Endorsements</span>
                            @else
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: rgba(255,255,255,0.04); border: 2px solid var(--mr-border, rgba(255,255,255,0.1)); display: flex; align-items: center; justify-content: center;">
                                <span style="color: var(--mr-text-secondary, #8a8998); font-size: 14px; font-weight: 700;">4</span>
                            </div>
                            <span style="font-size: 13px; color: var(--mr-text-secondary, #8a8998); font-weight: 500;">Citizen</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <div class="portlet civic-fade-2" style="padding: 0 !important; border: none !important; background: transparent !important;">
                    <div class="portlet-body" style="padding: 0;">

                        <div class="civic-tabs">
                        <ul id="myTab1" class="nav nav-pills">
                            <li class="active">
                                <?php if(!$isCitizen && !$isGP){?>
                                    <a href="#new" data-toggle="tab">Join Mars!</a>
                                <?php }else{ ?>
                                    <a href="#new" data-toggle="tab">Welcome back, Martian!</a>
                                <?php } ?>
                            </li>
                            <li class="">
                                <a href="#citizens" data-toggle="tab">All Citizens</a>
                            </li>

                            <li class="">
                                <a href="#all" data-toggle="tab">General Public</a>
                            </li>
                            <li class="">
                                <a href="#applicants" data-toggle="tab">Applicants</a>
                            </li>
                        </ul>
                        </div>{{-- /.civic-tabs --}}
                        <div id="myTab1Content" class="tab-content civic-fade-3" style="margin-top: 24px;">

                            <div class="tab-pane fade active in" id="new">
                                <?php if(!$isCitizen && !$isGP){?>
                                    <div style="text-align: center; padding: 48px 24px;">
                                        <div style="width: 80px; height: 80px; border-radius: 20px; background: rgba(0,228,255,0.08); border: 1px solid rgba(0,228,255,0.2); display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                                            <i class="fa fa-rocket" style="font-size: 32px; color: var(--mr-cyan, #00e4ff);"></i>
                                        </div>
                                        <div style="font-family: 'Orbitron', sans-serif; font-size: 20px; font-weight: 700; color: #fff; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 10px;">
                                            Join the Martian Republic
                                        </div>
                                        <div style="font-family: 'JetBrains Mono', monospace; font-size: 12px; color: var(--mr-text-dim, #8a8998); max-width: 440px; margin: 0 auto 28px; line-height: 1.7;">
                                            Register as a member of the General Martian Public. Take a photo, record a liveness video, and publish your identity to the blockchain.
                                        </div>
                                        <a href="/citizen/join" style="display: inline-flex; align-items: center; gap: 10px; padding: 14px 36px; background: var(--mr-mars, #c84125); border-radius: 10px; font-family: 'JetBrains Mono', monospace; font-size: 13px; font-weight: 500; letter-spacing: 1.5px; text-transform: uppercase; color: #fff !important; text-decoration: none !important; transition: all 0.2s; animation: ctaPulse 2s ease-in-out infinite;" onmouseover="this.style.background='#d94e30'" onmouseout="this.style.background='var(--mr-mars)'">
                                            <i class="fa fa-rocket"></i> Begin Registration
                                        </a>
                                        <style>@keyframes ctaPulse { 0%,100% { box-shadow: 0 0 0 0 rgba(200,65,37,0.4); } 50% { box-shadow: 0 0 20px 6px rgba(200,65,37,0.15); } }</style>
                                        <div style="margin-top: 24px; font-family: 'JetBrains Mono', monospace; font-size: 9px; color: var(--mr-text-faint, #5a5968);">
                                            <i class="fa fa-clock" style="margin-right: 4px;"></i> Takes about 5 minutes · Requires camera access · ~0.03 MARS network fee
                                        </div>
                                    </div>
                                <?php }else{ ?>
                                    @include('citizen.profile')
                                <?php } ?>
                            </div>
                            <!-- Show citizens allegable to vote -->
                            <div class="tab-pane fade " id="citizens">
                                @include('citizen.allcitizens')
                            </div>
                            <!-- Show the general public -->
                            <div class="tab-pane fade" id="all">
                                @include('citizen.allpublic')
                            </div>
                             <!-- Show all applicants -->
                             <div class="tab-pane fade" id="applicants">
                                @include('citizen.allapplicants')
                            </div>
   
                        </div>
                    </div>
                </div>
            <?php }else{ ?>
                <div class="portlet" style="text-align: center; padding: 40px 20px;">
                    <i class="fa fa-lock" style="font-size: 48px; color: var(--mr-text-secondary, #8a8998); margin-bottom: 16px;"></i>
                    <h3 style="margin-bottom: 12px;">Wallet Required</h3>
                    <p style="color: var(--mr-text-secondary, #8a8998); margin-bottom: 20px;">Please unlock your civic wallet to access the Citizen platform.</p>
                    <a href="/wallet/dashboard/hd" class="btn btn-lg btn-primary"><i class="fa fa-unlock-alt"></i> Unlock Wallet</a>
                </div>
            <?php } ?>    

                       
            </div> <!-- /.container -->
        </div> <!-- .content -->
    </div> <!-- /#wrapper -->
    <footer class="footer" style="border-top: 1px solid var(--mr-border, rgba(255,255,255,0.06)); padding: 0; height: 60px; background: var(--mr-void, #06060c);">
        @include('footer')
    </footer>
    <script src="/assets/wallet/js/libs/jquery-1.10.2.min.js"></script>
    <script src="/assets/wallet/js/libs/bootstrap.min.js"></script>
    <script src="/assets/wallet/js/plugins/flot/jquery.flot.js"></script>
    <script src="/assets/wallet/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="/assets/wallet/js/plugins/flot/jquery.flot.pie.js"></script>
    <script src="/assets/wallet/js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="/assets/wallet/js/plugins/fileupload/bootstrap-fileupload.js"></script>
    <script src="/assets/wallet/js/plugins/magnific/jquery.magnific-popup.min.js"></script>
    <script src="/assets/wallet/js/mvpready-core.js"></script>
    <script src="/assets/wallet/js/mvpready-admin.js"></script>
    <script src="/assets/wallet/js/md5.min.js"></script>
    <script src="/assets/wallet/js/dist/my_bundle.js"></script>
    <script src="/assets/wallet/js/sha256.js"></script>

<script>

function imgError(image) {
    image.onerror = "";
    image.src = "/assets/citizen/generic_profile.jpg";
    return true;
}

function rejectApplication(userId, causeOfRejection) 
{

    if (!confirm('This is only available to Citizens. Are you sure you want to reject this application? A public entry will be posted in the Forum.')) {
        return; // Stop if user does not confirm
    }
    const postData = {
        applicantUserId: userId,
        field: causeOfRejection
    };

    fetch('/api/rejection', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        body: JSON.stringify(postData)
    })
    .then(response => response.json())
    .then(data => {
        // Handle response data
        console.log('Success:', data);
        location.reload();
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}


$('#donateModal').on('show.bs.modal', function (event) {
    var applicant = $(event.relatedTarget);  
    var donatefor = applicant.data('donate-for'); 
    var donateto = applicant.data('donate-to');
    var donateid = applicant.data('donate-id');
    $('#donate-for').val(donatefor); 
    $('#donate-to').val(donateto); 
    $('#donate-id').val(donateid);
    $("#confirm-donate-btn").attr("data-donate-to", donateto);
    $("#confirm-donate-btn").attr("data-donate-id", donateid);
});


$(document).ready(function() {

var mem = WalletKey.get();
if (!mem || mem == ""){
    alert("Coul not retrieve wallet key. Please disconnect and reconnect your wallet.")
}


$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});  

    $('.input-form').blur(function() {   
        firstName = $('#firstname').val();
        $("#s_firstname").text(firstName);
        lastName = $('#lastname').val();
        $("#s_lastname").text(lastName);
        displayname = $('#displayname').val();
        $("#s_displayname").text(displayname);
        shortbio = $('#shortbio').val();
        $("#s_shortbio").text(shortbio);
});

$("#saveprofilebutton").click(function() {
    pic = $("#photo").attr("src");
    event.preventDefault();
    $.post("/api/permapinpic", {"type": "profile_pic", "picture": pic, "address": '<?=$public_address?>'} , function(data) {
        if(data){
            cid = data.Hash;
            $("#pin_message").text("Pinned!").fadeIn().delay(2000).fadeOut("slow");
            $("#s_ipfs_profile_pic").text("https://ipfs.marscoin.org/ipfs/"+ cid);
            $("#photo").attr("src", "https://ipfs.marscoin.org/ipfs/"+ cid);
        }
    });
});


$("#lastname").blur(function() {
    firstname = $("#firstname").val();
    lastname = $("#lastname").val();
    $.post("/api/setfullname", {firstname: firstname, lastname: lastname} , function(data) {
        
    });
});

$(".cacheme").blur(function() {
    displayname = $("#displayname").val();
    shortbio = $("#shortbio").val();
    $.post("/api/cacheonboarding", {displayname: displayname, shortbio: shortbio, publicaddress: '<?=$public_address?>'} , function(data) {
        
    });
});


$("#savevideo").click(function() {
    vid = $("#finished-video").attr("src");
    event.preventDefault();
    console.log(vid);
    var formData = new FormData()
    formData.append('file', current_blob)
    formData.append('address', '<?=$public_address?>')
    $.ajax({
        url:"/api/permapinvideo",
        type:"POST",
        data: formData,
        processData: false,
        contentType: false,
        success:function(data){
            cid = data.Hash;
            $("#s_ipfs_liveness_vid").text("https://ipfs.marscoin.org/ipfs/"+ cid);
            $("#finished-video").attr("src", "https://ipfs.marscoin.org/ipfs/"+ cid);
        },
        error: function(data){
            console.log(data);
        }   
    });
});

$("#publish").click(async (e) => {
    event.preventDefault();

    if($('#firstname').val() == ""){
        alert("First Name missing - required");
        return false;
    }
    if($('#lastname').val() == ""){
        alert("Last Name missing - required");
        return false;
    }
    if($('#displayname').val() == ""){
        alert("Display Name missing - required");
        return false;
    }
    if($('#shortbio').val() == ""){
        alert("Short bio missing - required");
        return false;
    }
    if($('#s_ipfs_profile_pic').val() == "< IPFS_LINK >"){
        alert("Please capture your profile picture first - required");
        return false;
    }
    if($('#s_ipfs_liveness_vid').val() == "< IPFS_LINK >"){
        alert("Please capture your short introductory video first - required");
        return false;
    }
    

    //api
    $("#publish_progress").show();
    $("#publish_progress_message").text("Publishing...").delay(2500).fadeOut();
    $("#publish_progress_message").text("Generating passport...").delay(2500).fadeOut();
    var obj = new Object();
    obj.data = {};
    obj.meta = {};
    obj.data.firstName = $('#firstname').val();
    obj.data.lastName = $('#lastname').val();
    obj.data.displayname = $('#displayname').val();
    obj.data.shortbio = $('#shortbio').val();
    obj.data.picture = $('#s_ipfs_profile_pic').text();
    obj.data.video = $('#s_ipfs_liveness_vid').text();
    var jsonString = JSON.stringify(obj.data);
    $("#publish_progress_message").text("Generating data hash...");
    var m = sha256(jsonString);
    obj.meta.hash = m;
    var jsonString = JSON.stringify(obj);
    $("#publish_progress_message").text("Writing data to IPFS and cache...");
    const data = await doAjax("/api/permapinjson", {"type": "data", "payload": jsonString, "address": '<?=$public_address?>'});
    cid = data.Hash;

    message = "GP_"+cid;
    const io = await sendMARS(1, "<?=$public_address?>");

    //const fee = marsConvert(io.fee);
    const fee = 0.01
    //console.log("THE FEE: ", fee);
    const mars_amount = 0.01
    const total_amount = fee + parseInt(mars_amount)
    $(".estimated-fee").text("$ " + fee)
    $(".conversion-rate").text(total_amount)

    try {
        const tx = await signMARS(message, mars_amount, io);
        $("#publish_progress_message").show().text("Published successfully...");
        $("#publish_progress_message").show().text(tx.tx_hash);
        const data = await doAjax("/api/setfeed", {"type": "GP", "txid": tx.tx_hash, "embedded_link": "https://ipfs.marscoin.org/ipfs/"+cid, "address": '<?=$public_address?>'});
        if(data.Hash){
            if(!alert('Submitted to Marscoin Blockchain successfully')){window.location.reload();}
        }

    } catch (e) {
        throw e;
    }
})


async function doAjax(ajaxurl, args) {
    try {
        const result = await $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: args
        });
        return result;
    } catch (error) {
        console.error(error);
        if (error.responseJSON) {
            return error.responseJSON;
        }
        return { error: error.statusText || 'Request failed' };
    }
}



$("#signedpublishbtn").click(async (e) => {
    event.preventDefault();
    $("#signedpublishprogress").show();
    posting = $('#signedpublishpost').val();
    var obj = new Object();
    obj.data = {};
    obj.meta = {};
    obj.data.post = posting;
    var jsonString = JSON.stringify(obj.data);
    var m = sha256(jsonString);
    obj.meta.hash = m;
    var jsonString = JSON.stringify(obj);
    utcnow = new Date().getTime();
    const data = await doAjax("/api/permapinjson", {"type": "signedpost_"+utcnow, "payload": jsonString, "address": '<?=$public_address?>'});
    cid = data.Hash;

    message = "SP_"+cid;
    const io = await sendMARS(1, "<?=$public_address?>");
    const fee = 0.01
    const mars_amount = 0.01
    const total_amount = fee + parseInt(mars_amount)

    try {
        const tx = await signMARS(message, mars_amount, io);
        $("#signedpublishhash").show().text(tx.tx_hash);
        $("#signedpublishhash").attr("href", 'https://explore.marscoin.org/tx/'+tx.tx_hash);
        const data = await doAjax("/api/setfeed", {"type": "SP", "txid": tx.tx_hash, message: posting, "embedded_link": "https://ipfs.marscoin.org/ipfs/"+cid, "address": '<?=$public_address?>'});
        if(data.Hash){
            $("#signedpublishprogress").hide();
            $('#signedpublishpost').val('');
        }
    } catch (e) {
        throw e;
    }
})


$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
  e.target // newly activated tab
  //alert(e.target)
  //alert(e.target.id)
  if(e.target.hash == "#profile-1")
  {
    $("#top_help_text").html("Welcome to the <br><b>Martian Citizen Registry</b>.  <br><br>In a first step simply register your profile.  <br><br>This allows others to endorse you and elevate you to Citizen status which in turn gives you the ability to launch public proposals and vote.<br><br> There are two quick parts to this application: a) Some basic information about you and b) A short video clip proving you are human<br><br> In a last step your application will be uploaded to a decentralized public file system known as IPFS. <br> Your <b>Martian Identity</b> is then automatically connected to your public Marscoin address. <br><br> Think of this as a voter database that is transparent, incorruptible, decentralized and efficient.")
  }
  if(e.target.hash == "#profile-2")
  {
    $("#top_help_text").html("<p>As part of your <b>Martian Citizen Registry</b> profile, a small video clip will be included that proves that you are human (<b>Proof of Humanity</b>). <br><br>Please record a short clip of yourself saying the following words: <br><br><b>\"I herewith declare that I, (your name), am human and a member of the Martian Republic.\"</b> while <i>holding your Marscoin address into the camera</i>. <br><br><a target='_blank' href='/citizen/printout'>Print Me!</a> <br><br> <a href=''>Example 1</a>  <a href=''>Example 2</a></p>")
  }
  if(e.target.hash == "#profile-3")
  {
    $("#top_help_text").html("<p><b>Summary</b> <br><br>The following information will be published to IPFS - a shared public file storage system - and anchored into the Marscoin blockchain to establish your decentralized Martian Identity.</p>")
  }
})



$('.dynamic-vote-modal').on('hidden.bs.modal', function () {
    location.reload();
})


$(".endorse-btn").click(async (e)=>
{

if (parseFloat("{{ $balance }}") < 0.1) {
    alert("Not enough Marscoin to endorse. You need at least 0.1 MARS.")
    return;
}else{

    let id = e.target.getAttribute("data-endorse")
    let address = e.target.getAttribute("data-address")
    let name = e.target.getAttribute("data-name")
    console.log(address)

    $("#endorse-title").text("Endorse: " + name)
    $("#endorse-address").text(address)

    // const io = await sendMARS(1, "<?= $public_address ?>");
    // console.log(io)
    // const fee = marsConvert(io.fee)
    // console.log("THE FEE: ", fee);
    // const mars_amount = 0.001;
    // const total_amount = fee + parseInt(mars_amount);
    $("#endorse-cost").text("0.1 MARS (paid as network fee)")
    $("#confirm-endorse-btn").attr("data-confirm", address);
    $("#confirm-endorse-btn").attr("data-endorse", id);
    $(".modal-message").show()
}

})


$("#confirm-endorse-btn").click(async (e)=>
{
    $("#confirm-loading").show();
    address  = e.target.getAttribute("data-confirm")
    id  = e.target.getAttribute("data-endorse")
    console.log("confirming..." + address)

    var obj = new Object();
    obj.data = {};
    obj.meta = {};
    obj.data.message = "Citizen <?=$public_address?> herewith endorses " + address + ". May you live long and prosper!";
    var jsonString = JSON.stringify(obj.data);
    var m = sha256(jsonString);
    obj.meta.hash = m;
    var jsonString = JSON.stringify(obj);
    console.log("Writing data to IPFS and cache...");
    const data = await doAjax("/api/permapinjson", {"type": "endorsement", "payload": jsonString, "address": '<?=$public_address?>'});
    cid = data.Hash;
    message = "ED_"+cid;

    const io = await sendMARS(0.1, "<?= $public_address ?>");
    try {
        const tx = await signMARS(message, 1.1, io);
        $("#publish_progress_message").show().text("Published successfully...");
        if(tx.tx_hash){
            $("#confirm-success-message").show()
            $("#confirm-transaction-hash").text(tx.tx_hash)
            $("#confirm-loading").hide();
            const edata = await doAjax("/api/setendorsed", {id: id});
            if (edata.error) {
                $("#modal-message-error").text(edata.error).show();
                $("#modal-message-success").hide();
                return;
            }
            const data = await doAjax("/api/setfeed", {"type": "ED", "txid": tx.tx_hash, "embedded_link": "https://ipfs.marscoin.org/ipfs/"+cid, "message": address, "address": '<?=$public_address?>'});
            if(data.Hash){
                var msg = 'Endorsement submitted to Blockchain successfully!';
                if (edata.promoted) {
                    msg += ' This person has been promoted to Citizen status!';
                } else {
                    msg += ' (' + edata.endorse_cnt + '/' + edata.threshold + ' endorsements toward citizenship)';
                }
                if(!alert(msg)){window.location.reload();}
            }
        }

    } catch (e) {
        $("#confirm-loading").hide();
        $("#modal-message-error").text("Endorsement failed: " + (e.message || "Unknown error")).show();
        console.error("Endorsement error:", e);
    }

})



$("#confirm-donate-btn").click(async (e)=>
{
    $("#donate-loading").show();
    address  = e.target.getAttribute("data-donate-to")
    id  = e.target.getAttribute("data-donate-id")
    amount  = $('#donate-amount').val();
    console.log("confirming..." + address)
    const io = await sendMARS(amount, address);
    try {
        const tx = await signMARSRegular(amount, io);
        if(tx.tx_hash){
            $("#donate-success-message").show()
            $("#donate-transaction-hash").text(tx.tx_hash)
            $("#donate-loading").hide();
            toastr.options = {
                "positionClass": "toast-bottom-right",
                "timeOut": "3000",
                onHidden: function() {
                    $('#donateModal').modal('hide');
                }
            }
            toastr.success("Donated " + amount + " MARS");
        }

    } catch (e) {
        toastr.options = {
                "positionClass": "toast-bottom-right",
                "timeOut": "3000",
            }
        toastr.error(e);
        throw e;
    }

})


////////////////////////////

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


const sendMARS = async (mars_amount, receiver_address) => {
    const sender_address = "<?=$public_address?>".trim()

    try {
        const io = await getTxInputsOutputs(sender_address, receiver_address,
            mars_amount)

        return io
    } catch (e) {
        handleError()
        throw e;
    }

    return null
}

// Find the signing key for an address
// Tries BOTH bip32 implementations since they derive different keys from same seed
async function findSigningKeyForAddress(mnemonic, targetAddress) {
    // CRITICAL: trim the mnemonic - the wallet unlock stores it with a trailing space
    // which produces a completely different PBKDF2 seed
    mnemonic = mnemonic.trim();
    const seed = my_bundle.bip39.mnemonicToSeedSync(mnemonic);
    const paths = ["m/44'/2'/0'", "m/44'/107'/0'"];

    // Try my_bundle.bip32 first (this is what pebas and genSeed's xpub step use)
    for (const basePath of paths) {
        try {
            const root = my_bundle.bip32.fromSeed(seed, Marscoin.mainnet);
            const account = root.derivePath(basePath);
            for (let chain = 0; chain <= 1; chain++) {
                for (let index = 0; index < 20; index++) {
                    const child = account.derive(chain).derive(index);
                    const addr = my_bundle.bitcoin.payments.p2pkh({
                        pubkey: child.publicKey, network: Marscoin.mainnet
                    }).address;
                    if (index === 0 && chain === 0) console.log(`bip32 ${basePath}/0/0 = ${addr}`);
                    if (addr === targetAddress) {
                        console.log(`Found via my_bundle.bip32 at ${basePath}/${chain}/${index}`);
                        return my_bundle.bitcoin.ECPair.fromWIF(child.toWIF(), Marscoin.mainnet);
                    }
                }
            }
        } catch(e) { console.warn(`bip32 ${basePath}:`, e.message); }
    }

    // Try my_bundle.bitcoin.bip32 (different derivation)
    for (const basePath of paths) {
        try {
            const root = my_bundle.bitcoin.bip32.fromSeed(seed, Marscoin.mainnet);
            const account = root.derivePath(basePath);
            for (let chain = 0; chain <= 1; chain++) {
                for (let index = 0; index < 20; index++) {
                    const child = account.derive(chain).derive(index);
                    const addr = my_bundle.bitcoin.payments.p2pkh({
                        pubkey: child.publicKey, network: Marscoin.mainnet
                    }).address;
                    if (index === 0 && chain === 0) console.log(`bitcoin.bip32 ${basePath}/0/0 = ${addr}`);
                    if (addr === targetAddress) {
                        console.log(`Found via bitcoin.bip32 at ${basePath}/${chain}/${index}`);
                        return my_bundle.bitcoin.ECPair.fromWIF(child.toWIF(), Marscoin.mainnet);
                    }
                }
            }
        } catch(e) { console.warn(`bitcoin.bip32 ${basePath}:`, e.message); }
    }

    return null;
}

const signMARS = async (message, mars_amount, tx_i_o) => {

    if(!WalletKey.get())
    {
        alert("Please unlock your wallet first.");
        return;
    }

    const mnemonic = WalletKey.get();
    const sender_address = "<?=$public_address?>".trim()

    // Use findSigningKeyForAddress helper (replicates genSeed two-library derivation)
    var key = await findSigningKeyForAddress(mnemonic, sender_address);
    if (!key) {
        alert("Could not find the signing key for address " + sender_address + ". Your seed phrase may not match this wallet.");
        return;
    }

    const zubs = zubrinConvert(mars_amount)
    var psbt = new my_bundle.bitcoin.Psbt({
        network: Marscoin.mainnet,
    });
    psbt.setVersion(1)
    psbt.setMaximumFeeRate(10000000);

    unspent_vout = 0
    var data = my_bundle.Buffer(message)
    const embed = my_bundle.bitcoin.payments.embed({ data: [data] });
    
    psbt.addOutput({
    script: embed.output,
    value: 0,
    })
    
    tx_i_o.inputs.forEach((input, i) => {
        psbt.addInput({
            hash: input.txId,
            index: input.vout,
            nonWitnessUtxo: my_bundle.Buffer.from(input.rawTx, 'hex'),
        })
    })

    tx_i_o.outputs.forEach(output => {
        if (!output.address) {
            output.address = sender_address
        }

        psbt.addOutput({
            address: output.address,
            value: output.value,
        })
    })

    // Derive the address from the key to verify it matches
    const derivedAddr = my_bundle.bitcoin.payments.p2pkh({pubkey: key.publicKey, network: Marscoin.mainnet}).address;
    console.log("Signing with key for address:", derivedAddr, "expected:", sender_address);

    for (let i = 0; i < tx_i_o.inputs.length; i++) {
        try{
            psbt.signInput(i, key);
        } catch (e) {
            console.error("signInput failed for input " + i + ":", e.message);
            // If address mismatch, the mnemonic might derive a different address
            if (derivedAddr !== sender_address) {
                alert("Your wallet key derives address " + derivedAddr + " but your civic wallet is " + sender_address + ". These don't match - you may need to reconnect with the correct seed phrase.");
            } else {
                alert("Signing failed: " + e.message + ". Please try reconnecting your wallet.");
            }
            throw e;
        }
    }

    const tx = psbt.finalizeAllInputs().extractTransaction();
    const txhash = tx.toHex()
    console.log(txhash)

    try {
        const txId = await broadcastTxHash(txhash);
        return txId;

    } catch (e) {
        handleError()
        throw e;
    }

}


const signMARSRegular = async (mars_amount, tx_i_o) => {

if(!WalletKey.get()) {
    alert("Please unlock your wallet first.");
    return;
}
const mnemonic = WalletKey.get();
const sender_address = "<?=$public_address?>".trim()

var key = findSigningKeyForAddress(mnemonic, sender_address);
if (!key) {
    alert("Could not find signing key for " + sender_address + ". Your seed phrase may not match this wallet.");
    return;
}
const zubs = zubrinConvert(mars_amount)
var psbt = new my_bundle.bitcoin.Psbt({
    network: Marscoin.mainnet,
});
psbt.setVersion(1)
psbt.setMaximumFeeRate(100000);
tx_i_o.inputs.forEach((input, i) => {
    psbt.addInput({
        hash: input.txId,
        index: input.vout,
        nonWitnessUtxo: my_bundle.Buffer.from(input.rawTx, 'hex'),
    })
})
tx_i_o.outputs.forEach(output => {
    if (!output.address) {
        output.address = sender_address
    }

    psbt.addOutput({
        address: output.address,
        value: output.value,
    })
})
for (let i = 0; i < tx_i_o.inputs.length; i++) {
    psbt.signInput(i, key);
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


const handleError = () => {
    console.log("PANIC AN ERROR!!!!!!!!")
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
            method: 'GET',
        });

        return response.json()

    } catch (e) {
        throw e;
    }



}

const broadcastTxHash = async (txhash) => {
    if (!txhash) {
        throw new Error("Missing tx hash...");
    }

    const url = 'https://pebas.marscoin.org/api/mars/broadcast?txhash='+txhash
    try {
        const response = await fetch(url, {
            method: 'GET'
        });
        const shorthash =  response.json() 
        return shorthash;
    } catch (e) {
        throw e;
    }


}


const zubrinConvert = (MARS) => {
    return (MARS * 100000000)
}

const marsConvert = (zubrin) => {
    return (zubrin / 100000000)
}


});

</script>
@livewireScripts
</body>

</html>
