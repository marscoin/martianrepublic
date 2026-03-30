<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <title>Infrastructure Registry - Martian Republic</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="BADS Infrastructure Registry - Certified instruments and chain of trust">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/assets/wallet/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-admin.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-flat.css">
    <link rel="shortcut icon" href="/assets/favicon.ico">
    @livewireStyles

    <style>
    /* ============================================
       INFRASTRUCTURE REGISTRY — BADS Instruments
       Martian Republic Mission Control Aesthetic
       ============================================ */
    html, body { background: #06060c !important; }
    .footer { z-index: 100; position: relative; clear: both; }
    #wrapper { overflow: hidden; }

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

    .registry-page {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }
    .registry-page .content { flex: 1; }
    .registry-page .footer { margin-top: auto; }

    /* ---- Hero Bar ---- */
    .hall-hero {
        position: relative;
        padding: 48px 0 32px;
        overflow: hidden;
    }
    .hall-hero::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background:
            radial-gradient(ellipse at 30% 50%, rgba(200,65,37,0.06) 0%, transparent 60%),
            radial-gradient(ellipse at 70% 20%, rgba(0,228,255,0.03) 0%, transparent 50%);
        pointer-events: none;
    }
    .hall-hero::after {
        content: '';
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--mr-border-bright) 20%, var(--mr-mars) 50%, var(--mr-border-bright) 80%, transparent);
    }

    .hall-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
    }
    .hall-subtitle {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: var(--mr-mars);
        margin-bottom: 8px;
    }
    .hall-subtitle .dot {
        display: inline-block;
        width: 6px; height: 6px;
        border-radius: 50%;
        background: var(--mr-green);
        margin-right: 6px;
        animation: pulse 2s infinite;
        vertical-align: middle;
    }
    .hall-title {
        font-family: 'Orbitron', sans-serif;
        font-weight: 800;
        font-size: 36px;
        letter-spacing: 2px;
        color: #fff;
        text-transform: uppercase;
        margin: 0;
    }

    .hall-actions {
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }
    .btn-new-proposal {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: var(--mr-mars) !important;
        color: #fff !important;
        padding: 14px 28px;
        border-radius: 8px;
        font-family: 'Orbitron', sans-serif;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        text-decoration: none;
        border: 1px solid transparent;
        transition: all 0.3s ease;
    }
    .btn-new-proposal:hover, .btn-new-proposal:focus {
        background: transparent !important;
        border-color: var(--mr-mars) !important;
        color: var(--mr-mars) !important;
        box-shadow: 0 0 30px rgba(200,65,37,0.2);
        text-decoration: none !important;
    }
    .btn-new-proposal i { font-size: 14px; }

    .observer-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--mr-surface);
        border: 1px solid var(--mr-border);
        border-radius: 8px;
        padding: 12px 20px;
        font-size: 13px;
        color: var(--mr-text-dim);
    }
    .observer-badge i { color: var(--mr-cyan); }
    .observer-badge a { color: var(--mr-cyan); text-decoration: none; }
    .observer-badge a:hover { color: #fff; }

    /* ---- Stats Bar ---- */
    .stats-bar {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin: 28px 0 0;
    }
    .stat-card {
        background: var(--mr-surface);
        border: 1px solid var(--mr-border);
        border-radius: 10px;
        padding: 20px 24px;
        text-align: center;
    }
    .stat-card-icon {
        font-size: 20px;
        margin-bottom: 8px;
        opacity: 0.7;
    }
    .stat-card-value {
        font-family: 'Orbitron', sans-serif;
        font-size: 28px;
        font-weight: 800;
        color: #fff;
        line-height: 1;
        margin-bottom: 6px;
    }
    .stat-card-label {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--mr-text-dim);
    }

    /* ---- Filter Tabs ---- */
    .hall-filters {
        display: flex;
        gap: 2px;
        margin: 32px 0 28px;
        background: var(--mr-border);
        border-radius: 8px;
        overflow: hidden;
        flex-wrap: wrap;
    }
    .hall-filter {
        flex: 1;
        background: var(--mr-surface);
        border: none;
        padding: 14px 16px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: var(--mr-text-dim);
        cursor: pointer;
        transition: all 0.25s ease;
        text-align: center;
        position: relative;
        min-width: 100px;
    }
    .hall-filter:first-child { border-radius: 8px 0 0 8px; }
    .hall-filter:last-child { border-radius: 0 8px 8px 0; }
    .hall-filter:hover {
        background: var(--mr-surface-raised);
        color: var(--mr-text);
    }
    .hall-filter.active {
        background: var(--mr-surface-raised);
        color: #fff;
    }
    .hall-filter.active::after {
        content: '';
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 2px;
        background: var(--mr-mars);
    }
    .hall-filter .count {
        display: inline-block;
        background: var(--mr-border);
        border-radius: 10px;
        padding: 1px 7px;
        font-size: 10px;
        margin-left: 4px;
        color: var(--mr-text-dim);
    }
    .hall-filter.active .count {
        background: var(--mr-mars-glow);
        color: var(--mr-mars);
    }

    /* ---- Instrument Cards ---- */
    .instrument-list { margin-bottom: 48px; }

    .instrument-card {
        background: var(--mr-surface);
        border: 1px solid var(--mr-border);
        border-radius: 10px;
        padding: 28px;
        margin-bottom: 16px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        text-decoration: none;
        display: block;
        color: inherit;
    }
    .instrument-card:hover {
        border-color: var(--mr-border-bright);
        background: var(--mr-surface-raised);
        transform: translateY(-1px);
        text-decoration: none;
        color: inherit;
    }
    .instrument-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; bottom: 0;
        width: 3px;
        border-radius: 10px 0 0 10px;
    }

    .instrument-card-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 12px;
    }
    .instrument-card-header-left {
        display: flex;
        align-items: center;
        gap: 16px;
        flex: 1;
        min-width: 0;
    }
    .instrument-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
        border: 1px solid var(--mr-border);
    }
    .instrument-info { flex: 1; min-width: 0; }
    .instrument-name {
        font-family: 'DM Sans', sans-serif;
        font-size: 17px;
        font-weight: 600;
        color: #fff;
        margin: 0 0 2px;
        line-height: 1.3;
    }
    .instrument-type {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        color: var(--mr-text-dim);
        letter-spacing: 0.5px;
    }

    .instrument-serial {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--mr-text-dim);
        background: var(--mr-dark);
        border: 1px solid var(--mr-border);
        border-radius: 4px;
        padding: 4px 10px;
        white-space: nowrap;
    }

    .instrument-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        padding: 4px 12px;
        border-radius: 4px;
        white-space: nowrap;
    }
    .badge-active {
        background: rgba(52,211,153,0.1);
        color: var(--mr-green);
        border: 1px solid rgba(52,211,153,0.2);
    }
    .badge-revoked {
        background: rgba(239,68,68,0.1);
        color: var(--mr-red);
        border: 1px solid rgba(239,68,68,0.2);
    }
    .badge-calibration_due, .badge-calibration_expired {
        background: rgba(245,158,11,0.1);
        color: var(--mr-amber);
        border: 1px solid rgba(245,158,11,0.2);
    }

    .instrument-meta {
        display: flex;
        gap: 20px;
        align-items: center;
        flex-wrap: wrap;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid var(--mr-border);
    }
    .instrument-meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: var(--mr-text-dim);
    }
    .instrument-meta-item i { font-size: 11px; opacity: 0.7; }
    .instrument-meta-item .meta-label { color: var(--mr-text-dim); }
    .instrument-meta-item .meta-value { color: var(--mr-text); }
    .instrument-meta-item a { color: var(--mr-cyan); text-decoration: none; }
    .instrument-meta-item a:hover { color: #fff; }

    /* ---- Sidebar ---- */
    .sidebar-card {
        background: var(--mr-surface);
        border: 1px solid var(--mr-border);
        border-radius: 10px;
        padding: 24px;
        margin-bottom: 16px;
    }
    .sidebar-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 11px;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--mr-text-dim);
        margin-bottom: 16px;
    }
    .sidebar-text {
        font-size: 13px;
        line-height: 1.8;
        color: var(--mr-text-dim);
    }
    .sidebar-text strong { color: var(--mr-text); }
    .sidebar-text a { color: var(--mr-cyan); text-decoration: none; }
    .sidebar-text a:hover { color: #fff; }

    .sidebar-stat-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid var(--mr-border);
    }
    .sidebar-stat-row:last-child { border-bottom: none; }
    .sidebar-stat-label {
        font-size: 12px;
        color: var(--mr-text-dim);
    }
    .sidebar-stat-value {
        font-family: 'Orbitron', sans-serif;
        font-size: 14px;
        font-weight: 700;
        color: #fff;
    }

    .committee-item {
        padding: 12px 0;
        border-bottom: 1px solid var(--mr-border);
    }
    .committee-item:last-child { border-bottom: none; }
    .committee-item-name {
        font-family: 'DM Sans', sans-serif;
        font-size: 13px;
        font-weight: 600;
        color: var(--mr-text);
        margin-bottom: 4px;
    }
    .committee-item-name a { color: var(--mr-text); text-decoration: none; }
    .committee-item-name a:hover { color: var(--mr-cyan); }
    .committee-item-stats {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        color: var(--mr-text-dim);
        letter-spacing: 0.5px;
    }
    .committee-item-stats span { margin-right: 12px; }

    /* ---- Empty State ---- */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: var(--mr-surface);
        border: 1px solid var(--mr-border);
        border-radius: 10px;
    }
    .empty-state i {
        font-size: 48px;
        color: var(--mr-text-dim);
        margin-bottom: 16px;
        opacity: 0.5;
    }
    .empty-state h3 {
        font-family: 'Orbitron', sans-serif;
        font-size: 16px;
        color: var(--mr-text);
        margin-bottom: 8px;
        letter-spacing: 1px;
    }
    .empty-state p {
        color: var(--mr-text-dim);
        font-size: 14px;
    }

    /* ---- Responsive ---- */
    @media (max-width: 991px) {
        .hall-header { flex-direction: column; }
        .hall-filters { flex-wrap: wrap; }
        .hall-filter { flex: 0 0 calc(33.333% - 2px); }
        .stats-bar { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 768px) {
        .hall-title { font-size: 24px; }
        .hall-filter { flex: 0 0 calc(50% - 2px); }
        .instrument-card { padding: 20px; }
        .stats-bar { grid-template-columns: 1fr 1fr; }
        .instrument-card-header { flex-direction: column; }
    }

    /* ---- Animations ---- */
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .instrument-card { animation: fadeIn 0.4s ease-out both; }
    .instrument-card:nth-child(1) { animation-delay: 0.05s; }
    .instrument-card:nth-child(2) { animation-delay: 0.1s; }
    .instrument-card:nth-child(3) { animation-delay: 0.15s; }
    .instrument-card:nth-child(4) { animation-delay: 0.2s; }
    .instrument-card:nth-child(5) { animation-delay: 0.25s; }
    .instrument-card:nth-child(6) { animation-delay: 0.3s; }
    </style>
</head>

<body class="registry-page">
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
        @include('wallet.mainnav', ['active' => 'inventory'])

        <div class="content">
            <div class="container">

                @if(session('success'))
                <div class="alert alert-success alert-dismissible" role="alert" style="background:rgba(52,211,153,0.1);border-color:rgba(52,211,153,0.2);color:var(--mr-green);">
                    <button type="button" class="close" data-dismiss="alert" style="color:var(--mr-green);"><span>&times;</span></button>
                    {{ session('success') }}
                </div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible" role="alert" style="background:rgba(239,68,68,0.1);border-color:rgba(239,68,68,0.2);color:var(--mr-red);">
                    <button type="button" class="close" data-dismiss="alert" style="color:var(--mr-red);"><span>&times;</span></button>
                    {{ session('error') }}
                </div>
                @endif

                @if($wallet_open)

                {{-- HERO BAR --}}
                <div class="hall-hero">
                    <div class="hall-header">
                        <div class="hall-title-block">
                            <div class="hall-subtitle">
                                <span class="dot"></span> Infrastructure Registry &mdash; Active
                            </div>
                            <h1 class="hall-title">Instruments</h1>
                        </div>
                        <div class="hall-actions">
                            @if($isDeputy)
                                <a href="{{ route('instruments.create') }}" class="btn-new-proposal">
                                    <i class="fa-solid fa-certificate"></i> Certify Device
                                </a>
                            @else
                                <div class="observer-badge">
                                    <i class="fa-solid fa-shield-halved"></i>
                                    Deputy access required to certify instruments
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- STATS BAR --}}
                    <div class="stats-bar">
                        <div class="stat-card">
                            <div class="stat-card-icon" style="color: var(--mr-cyan);"><i class="fa-solid fa-users-rectangle"></i></div>
                            <div class="stat-card-value">{{ $stats['committees'] }}</div>
                            <div class="stat-card-label">Committees</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-card-icon" style="color: var(--mr-amber);"><i class="fa-solid fa-user-shield"></i></div>
                            <div class="stat-card-value">{{ $stats['deputies'] }}</div>
                            <div class="stat-card-label">Deputies</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-card-icon" style="color: var(--mr-mars);"><i class="fa-solid fa-microchip"></i></div>
                            <div class="stat-card-value">{{ $stats['instruments'] }}</div>
                            <div class="stat-card-label">Instruments</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-card-icon" style="color: var(--mr-green);"><i class="fa-solid fa-link"></i></div>
                            <div class="stat-card-value">{{ $stats['attestations'] }}</div>
                            <div class="stat-card-label">Attestations</div>
                        </div>
                    </div>
                </div>

                {{-- FILTER TABS --}}
                <div class="hall-filters" id="category-filters">
                    <button class="hall-filter active" data-category="all">
                        All
                        <span class="count">{{ $instruments->count() }}</span>
                    </button>
                    @foreach($categories as $catKey => $catInfo)
                        @php $catCount = $categoryCounts[$catKey] ?? 0; @endphp
                        @if($catCount > 0)
                        <button class="hall-filter" data-category="{{ $catKey }}">
                            <i class="fa-solid {{ $catInfo['icon'] }}" style="margin-right:4px;"></i>
                            {{ $catInfo['label'] }}
                            <span class="count">{{ $catCount }}</span>
                        </button>
                        @endif
                    @endforeach
                </div>

                {{-- MAIN CONTENT --}}
                <div class="row">
                    <div class="col-md-9">
                        <div class="instrument-list">
                            @if($instruments->isEmpty())
                                <div class="empty-state">
                                    <i class="fa-solid fa-microchip"></i>
                                    <h3>No Instruments Registered</h3>
                                    <p>The infrastructure registry is empty. Deputies can certify colony instruments to begin building the chain of trust.</p>
                                </div>
                            @else
                                @foreach($instruments as $instrument)
                                    @php
                                        $catInfo = $categories[$instrument->device_category] ?? ['icon' => 'fa-microchip', 'color' => '#8a8998', 'label' => 'Unknown'];
                                        $statusClass = $instrument->status;
                                    @endphp
                                    <a href="{{ route('instruments.show', $instrument->id) }}" class="instrument-card" data-category="{{ $instrument->device_category }}" style="border-left: 3px solid {{ $catInfo['color'] }};">
                                        <div class="instrument-card-header">
                                            <div class="instrument-card-header-left">
                                                <div class="instrument-icon" style="background: {{ $catInfo['color'] }}15; color: {{ $catInfo['color'] }};">
                                                    <i class="fa-solid {{ $catInfo['icon'] }}"></i>
                                                </div>
                                                <div class="instrument-info">
                                                    <h3 class="instrument-name">{{ $instrument->device_type_name }}</h3>
                                                    <span class="instrument-type">{{ $catInfo['label'] }} &middot; {{ $instrument->make }} {{ $instrument->model }}</span>
                                                </div>
                                            </div>
                                            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                                                <span class="instrument-serial">
                                                    <i class="fa-solid fa-barcode" style="margin-right:4px;"></i>
                                                    {{ $instrument->serial }}
                                                </span>
                                                <span class="instrument-status-badge badge-{{ $statusClass }}">
                                                    @if($instrument->status === 'active')
                                                        <i class="fa-solid fa-circle" style="font-size:6px;"></i> Active
                                                    @elseif($instrument->status === 'revoked')
                                                        <i class="fa-solid fa-xmark"></i> Revoked
                                                    @elseif($instrument->status === 'calibration_due')
                                                        <i class="fa-solid fa-clock"></i> Cal. Due
                                                    @else
                                                        <i class="fa-solid fa-exclamation-triangle"></i> {{ ucfirst(str_replace('_', ' ', $instrument->status)) }}
                                                    @endif
                                                </span>
                                            </div>
                                        </div>

                                        <div class="instrument-meta">
                                            @if($instrument->location)
                                            <div class="instrument-meta-item">
                                                <i class="fa-solid fa-location-dot"></i>
                                                <span class="meta-value">{{ $instrument->location }}</span>
                                            </div>
                                            @endif

                                            @if($instrument->certifiedBy)
                                            <div class="instrument-meta-item">
                                                <i class="fa-solid fa-user-shield" style="color:var(--mr-amber);"></i>
                                                <span class="meta-label">Certified by</span>
                                                <span class="meta-value">{{ $instrument->certifiedBy->user->fullname ?? 'Unknown' }}</span>
                                                @if($instrument->certifiedBy->committee)
                                                    <span class="meta-label">&middot; {{ $instrument->certifiedBy->committee->name }}</span>
                                                @endif
                                            </div>
                                            @endif

                                            @if($instrument->created_at)
                                            <div class="instrument-meta-item">
                                                <i class="fa-solid fa-clock"></i>
                                                <span class="meta-value" style="font-family:'JetBrains Mono',monospace;font-size:11px;">
                                                    {{ $instrument->created_at->format('Y-m-d H:i') }}
                                                </span>
                                            </div>
                                            @endif
                                        </div>
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    {{-- SIDEBAR --}}
                    <div class="col-md-3">
                        {{-- Chain of Trust --}}
                        <div class="sidebar-card">
                            <div class="sidebar-title">Chain of Trust</div>
                            <div class="sidebar-text">
                                <strong>BADS</strong> (Blockchain-Attested Data System) ensures every sensor reading on Mars is traceable to a democratically authorized source.
                                <br><br>
                                <strong>Congress</strong> passes a bill establishing an oversight committee.
                                The committee appoints <strong>deputies</strong> who certify <strong>instruments</strong>.
                                Instruments submit <strong>attestations</strong> &mdash; Merkle-rooted batches of readings anchored to the Marscoin blockchain.
                            </div>
                        </div>

                        {{-- Committees --}}
                        <div class="sidebar-card">
                            <div class="sidebar-title">Oversight Committees</div>
                            @if($committees->isEmpty())
                                <div class="sidebar-text">No active committees yet. A congressional proposal must first establish an oversight committee.</div>
                            @else
                                @foreach($committees as $committee)
                                <div class="committee-item">
                                    <div class="committee-item-name">
                                        <a href="{{ route('committees.index') }}">{{ $committee->name }}</a>
                                    </div>
                                    <div class="committee-item-stats">
                                        <span><i class="fa-solid fa-user-shield" style="color:var(--mr-amber);margin-right:3px;"></i> {{ $committee->deputies_count }} deputies</span>
                                        <span><i class="fa-solid fa-microchip" style="color:var(--mr-mars);margin-right:3px;"></i> {{ $committee->instruments_count }} instruments</span>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>

                        {{-- Learn --}}
                        <div class="sidebar-card">
                            <div class="sidebar-title">Learn</div>
                            <div class="sidebar-text">
                                <a href="/academy"><i class="fa-solid fa-graduation-cap" style="margin-right:6px;"></i> Learn about BADS in the Academy</a>
                                <br><br>
                                Understand how Mars colony infrastructure data integrity is maintained through democratic oversight and cryptographic attestation.
                            </div>
                        </div>
                    </div>
                </div>

                @else
                {{-- Wallet not open --}}
                <div style="text-align:center;padding:80px 20px;background:var(--mr-surface);border:1px solid var(--mr-border);border-radius:10px;margin-top:40px;">
                    <i class="fa-solid fa-lock" style="font-size:48px;color:var(--mr-text-dim);margin-bottom:16px;display:block;"></i>
                    <h3 style="font-family:'Orbitron',sans-serif;font-size:16px;color:var(--mr-text);margin-bottom:8px;letter-spacing:1px;">Wallet Required</h3>
                    <p style="color:var(--mr-text-dim);font-size:14px;margin-bottom:20px;">Please unlock your civic wallet to access the Infrastructure Registry.</p>
                    <a href="/wallet/dashboard/hd" style="display:inline-flex;align-items:center;gap:8px;background:var(--mr-mars);color:#fff;padding:14px 28px;border-radius:8px;font-family:'Orbitron',sans-serif;font-size:12px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;text-decoration:none;">
                        <i class="fa-solid fa-unlock-keyhole"></i> Unlock Wallet
                    </a>
                </div>
                @endif

            </div>
        </div>
    </div>

    <footer class="footer">
        @include('footer')
    </footer>

    <script src="/assets/wallet/js/libs/jquery-1.10.2.min.js"></script>
    <script src="/assets/wallet/js/libs/bootstrap.min.js"></script>
    <script src="/assets/wallet/js/mvpready-core.js"></script>
    <script src="/assets/wallet/js/mvpready-admin.js"></script>

    <script>
    $(document).ready(function() {
        // Category filter tabs
        $('#category-filters .hall-filter').click(function() {
            var category = $(this).data('category');

            // Update active tab
            $('#category-filters .hall-filter').removeClass('active');
            $(this).addClass('active');

            // Filter cards
            if (category === 'all') {
                $('.instrument-card').show();
            } else {
                $('.instrument-card').each(function() {
                    if ($(this).data('category') === category) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }
        });
    });
    </script>
</body>
</html>
