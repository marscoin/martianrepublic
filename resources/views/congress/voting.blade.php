<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <title>The Congress Hall - Martian Republic</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Martian Republic Congress - Direct democracy on Mars">
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
    <link rel="shortcut icon" href="/assets/favicon.ico">
    @livewireStyles

    <style>
    /* ============================================
       THE CONGRESS HALL — Voting Hub
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

    .congress-hall {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }
    .congress-hall .content { flex: 1; }
    .congress-hall .footer { margin-top: auto; }

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
    .hall-title-block {}
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

    /* ---- Filter Tabs ---- */
    .hall-filters {
        display: flex;
        gap: 2px;
        margin: 32px 0 28px;
        background: var(--mr-border);
        border-radius: 8px;
        overflow: hidden;
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

    /* ---- Proposal Cards ---- */
    .proposal-list { margin-bottom: 48px; }

    .proposal-card {
        background: var(--mr-surface);
        border: 1px solid var(--mr-border);
        border-radius: 10px;
        padding: 28px;
        margin-bottom: 16px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .proposal-card:hover {
        border-color: var(--mr-border-bright);
        background: var(--mr-surface-raised);
        transform: translateY(-1px);
    }
    .proposal-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; bottom: 0;
        width: 3px;
        border-radius: 10px 0 0 10px;
    }
    .proposal-card.status-active::before { background: var(--mr-green); }
    .proposal-card.status-passed::before { background: var(--mr-cyan); }
    .proposal-card.status-rejected::before { background: var(--mr-red); }
    .proposal-card.status-expired::before { background: var(--mr-amber); }
    .proposal-card.status-closed::before { background: var(--mr-text-dim); }

    .proposal-card-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 12px;
    }
    .proposal-id {
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
    .proposal-status-badge {
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
    .badge-passed {
        background: rgba(0,228,255,0.08);
        color: var(--mr-cyan);
        border: 1px solid rgba(0,228,255,0.15);
    }
    .badge-rejected {
        background: rgba(239,68,68,0.1);
        color: var(--mr-red);
        border: 1px solid rgba(239,68,68,0.2);
    }
    .badge-expired {
        background: rgba(245,158,11,0.1);
        color: var(--mr-amber);
        border: 1px solid rgba(245,158,11,0.2);
    }
    .badge-closed {
        background: rgba(138,137,152,0.1);
        color: var(--mr-text-dim);
        border: 1px solid rgba(138,137,152,0.2);
    }

    .proposal-title {
        font-family: 'Open Sans', sans-serif;
        font-size: 18px;
        font-weight: 700;
        color: #fff;
        margin: 0 0 6px;
        line-height: 1.4;
    }
    .proposal-title a {
        color: #fff;
        text-decoration: none;
        transition: color 0.2s;
    }
    .proposal-title a:hover { color: var(--mr-cyan); }

    .proposal-meta {
        font-size: 13px;
        color: var(--mr-text-dim);
        margin-bottom: 12px;
        line-height: 1.6;
    }
    .proposal-meta a { color: var(--mr-cyan); text-decoration: none; }
    .proposal-meta a:hover { color: #fff; }
    .proposal-meta .category {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: var(--mr-mars);
    }
    .proposal-meta .ipfs-hash {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        color: var(--mr-text-dim);
    }

    .proposal-excerpt {
        font-size: 14px;
        line-height: 1.7;
        color: var(--mr-text-dim);
        margin-bottom: 16px;
    }
    .proposal-excerpt a { color: var(--mr-cyan); text-decoration: none; font-weight: 600; }

    .proposal-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding-top: 16px;
        border-top: 1px solid var(--mr-border);
        flex-wrap: wrap;
    }

    .proposal-stats {
        display: flex;
        gap: 20px;
        align-items: center;
    }
    .proposal-stat {
        display: flex;
        align-items: center;
        gap: 6px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        color: var(--mr-text-dim);
    }
    .proposal-stat i { font-size: 12px; }
    .proposal-stat.yay { color: var(--mr-green); }
    .proposal-stat.nay { color: var(--mr-red); }

    .proposal-vote-bar {
        display: flex;
        height: 4px;
        border-radius: 2px;
        overflow: hidden;
        background: var(--mr-dark);
        min-width: 120px;
        flex: 1;
        max-width: 200px;
    }
    .proposal-vote-bar .yay-bar {
        background: var(--mr-green);
        transition: width 0.5s ease;
    }
    .proposal-vote-bar .nay-bar {
        background: var(--mr-red);
        transition: width 0.5s ease;
    }

    .proposal-discussion {
        display: flex;
        align-items: center;
        gap: 5px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        color: var(--mr-text-dim);
        text-decoration: none;
    }
    .proposal-discussion:hover { color: var(--mr-cyan); text-decoration: none; }

    /* ---- Summary for passed proposals ---- */
    .proposal-summary {
        font-size: 13px;
        line-height: 1.6;
        color: var(--mr-text-dim);
        margin-bottom: 12px;
        padding: 12px 16px;
        background: var(--mr-dark);
        border-left: 2px solid var(--mr-cyan);
        border-radius: 0 6px 6px 0;
    }

    /* ---- Countdown & Progress in cards ---- */
    .card-countdown {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        color: var(--mr-text-dim);
    }

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
    .empty-state a { color: var(--mr-cyan); text-decoration: none; }

    /* ---- Sidebar ---- */
    .hall-sidebar {
        /* no sticky - prevents footer overlap */
    }
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

    /* ---- Responsive ---- */
    @media (max-width: 991px) {
        .hall-header { flex-direction: column; }
        .hall-filters { flex-wrap: wrap; }
        .hall-filter { flex: 0 0 calc(33.333% - 2px); }
        .proposal-card-footer { flex-direction: column; align-items: flex-start; }
    }
    @media (max-width: 768px) {
        .hall-title { font-size: 24px; }
        .hall-filter { flex: 0 0 calc(50% - 2px); }
        .proposal-card { padding: 20px; }
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
    .proposal-card { animation: fadeIn 0.4s ease-out both; }
    .proposal-card:nth-child(1) { animation-delay: 0.05s; }
    .proposal-card:nth-child(2) { animation-delay: 0.1s; }
    .proposal-card:nth-child(3) { animation-delay: 0.15s; }
    .proposal-card:nth-child(4) { animation-delay: 0.2s; }
    .proposal-card:nth-child(5) { animation-delay: 0.25s; }
    </style>
</head>

<body class="congress-hall">
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

                {{-- HERO BAR --}}
                <div class="hall-hero">
                    <div class="hall-header">
                        <div class="hall-title-block">
                            <div class="hall-subtitle">
                                <span class="dot"></span> Congress Hall &mdash; Active Session
                            </div>
                            <h1 class="hall-title">Legislation</h1>
                        </div>
                        <div class="hall-actions">
                            @if($isCitizen)
                                <a href="/congress/voting/new" class="btn-new-proposal">
                                    <i class="fa-solid fa-pen-to-square"></i> Draft Proposal
                                </a>
                            @else
                                <div class="observer-badge">
                                    <i class="fa-solid fa-eye"></i>
                                    Observer Mode &mdash; <a href="/citizen/all">Become a citizen</a> to propose &amp; vote
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- FILTER TABS --}}
                <div class="hall-filters" id="hall-filters">
                    <button class="hall-filter active" data-tab="Active">
                        Active
                        @if($active->count() > 0)
                            <span class="count">{{ $active->count() }}</span>
                        @endif
                    </button>
                    <button class="hall-filter" data-tab="Passed">
                        Passed
                        @if($passed->count() > 0)
                            <span class="count">{{ $passed->count() }}</span>
                        @endif
                    </button>
                    <button class="hall-filter" data-tab="Rejected">
                        Rejected
                        @if($rejected->count() > 0)
                            <span class="count">{{ $rejected->count() }}</span>
                        @endif
                    </button>
                    <button class="hall-filter" data-tab="Expired">
                        Expired
                    </button>
                    <button class="hall-filter" data-tab="Closed">
                        Closed
                        @if($closed->count() > 0)
                            <span class="count">{{ $closed->count() }}</span>
                        @endif
                    </button>
                    <button class="hall-filter" data-tab="All">
                        All
                        @if($proposals->count() > 0)
                            <span class="count">{{ $proposals->count() }}</span>
                        @endif
                    </button>
                </div>

                {{-- MAIN CONTENT --}}
                <div class="row">
                    <div class="col-md-9">

                        {{-- ACTIVE TAB --}}
                        <div class="proposal-list tab-panel" id="tab-Active">
                            @if($active->isEmpty())
                                <div class="empty-state">
                                    <i class="fa-solid fa-scroll"></i>
                                    <h3>No Active Proposals</h3>
                                    <p>The floor is open. <a href="/congress/voting/new">Draft a new proposal</a> to begin.</p>
                                </div>
                            @else
                                @foreach($active as $proposal)
                                    <div class="proposal-card status-active">
                                        <div class="proposal-card-header">
                                            <span class="proposal-id">
                                                <i class="fa-solid fa-landmark" style="margin-right:4px;"></i>
                                                Bill #{{ $proposal->id }}
                                            </span>
                                            <span class="proposal-status-badge badge-active">
                                                <i class="fa-solid fa-circle" style="font-size:6px;"></i> Voting Open
                                            </span>
                                        </div>
                                        <h3 class="proposal-title">
                                            <a href="/congress/proposal/{{ $proposal->id }}">{{ $proposal->title }}</a>
                                        </h3>
                                        <div class="proposal-meta">
                                            Sponsored by <a target="_blank" href="/citizen/id/{{ $proposal->public_address }}">{{ $proposal->author }}</a>
                                            &middot; <span class="category">{{ str_replace("poll", "Certified Poll", $proposal->category) }}</span>
                                            &middot; <span class="ipfs-hash"><a target="_blank" href="{{ $proposal->ipfs_hash }}">{{ strtoupper(substr(str_replace("https://ipfs.marscoin.org/ipfs/", "", $proposal->ipfs_hash), 1, 8)) }} <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:9px;"></i></a></span>
                                        </div>
                                        <div class="proposal-excerpt">
                                            {{ substr($proposal->description, 0, 300) }}@if(strlen($proposal->description) > 300)... <a href="/congress/proposal/{{ $proposal->id }}">Read more</a>@endif
                                        </div>
                                        <div class="proposal-card-footer">
                                            <div class="proposal-stats">
                                                @if($proposal->mined)
                                                    @php
                                                        $endTime = \Carbon\Carbon::parse($proposal->mined)->addDays($proposal->duration);
                                                        $remaining = now()->diff($endTime);
                                                        $isExpired = now()->gt($endTime);
                                                    @endphp
                                                    <span class="proposal-stat">
                                                        <i class="fa-solid fa-clock"></i>
                                                        @if($isExpired)
                                                            Voting ended
                                                        @else
                                                            {{ $remaining->days }}d {{ $remaining->h }}h remaining
                                                        @endif
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="proposal-stats">
                                                <a href="/forum/t/{{ $proposal->discussion }}" class="proposal-discussion">
                                                    <i class="fa-solid fa-comment"></i> {{ $proposal->post_count }} comments
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        {{-- PASSED TAB --}}
                        <div class="proposal-list tab-panel" id="tab-Passed" style="display:none;">
                            @if($passed->isEmpty())
                                <div class="empty-state">
                                    <i class="fa-solid fa-check-double"></i>
                                    <h3>No Passed Proposals Yet</h3>
                                    <p>Legislation that meets its threshold will appear here.</p>
                                </div>
                            @else
                                @foreach($passed as $proposal)
                                    @php
                                        $createdAt = \Carbon\Carbon::parse($proposal->mined);
                                        $endTime = $createdAt->copy()->addDays($proposal->duration)->format('F j, Y');
                                    @endphp
                                    <div class="proposal-card status-passed">
                                        <div class="proposal-card-header">
                                            <span class="proposal-id">
                                                <i class="fa-solid fa-landmark" style="margin-right:4px;"></i>
                                                Bill #{{ $proposal->id }}
                                            </span>
                                            <span class="proposal-status-badge badge-passed">
                                                <i class="fa-solid fa-check"></i> Passed
                                            </span>
                                        </div>
                                        <h3 class="proposal-title">
                                            <a href="/congress/proposal/{{ $proposal->id }}">{{ $proposal->title }}</a>
                                        </h3>
                                        <div class="proposal-meta">
                                            Sponsored by <a target="_blank" href="/citizen/id/{{ $proposal->public_address }}">{{ $proposal->author }}</a>
                                            &middot; <span class="category">{{ str_replace("poll", "Certified Poll", $proposal->category) }}</span>
                                            &middot; <span class="ipfs-hash"><a target="_blank" href="{{ $proposal->ipfs_hash }}">{{ strtoupper(substr(str_replace("https://ipfs.marscoin.org/ipfs/", "", $proposal->ipfs_hash), 1, 8)) }} <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:9px;"></i></a></span>
                                        </div>
                                        <div class="proposal-summary">
                                            Voting lasted {{ $proposal->duration }} days ({{ $createdAt->format('M j, Y') }} &mdash; {{ $endTime }}).
                                            {{ $proposal->total_votes }} votes cast &middot;
                                            Motion carried with <strong style="color:var(--mr-green);">{{ round($proposal->yay_percent, 1) }}%</strong> in favor
                                            (threshold: {{ $proposal->threshold }}%).
                                        </div>
                                        <div class="proposal-excerpt">
                                            {{ substr($proposal->description, 0, 300) }}@if(strlen($proposal->description) > 300)... <a href="/congress/proposal/{{ $proposal->id }}">Read more</a>@endif
                                        </div>
                                        <div class="proposal-card-footer">
                                            <div class="proposal-stats">
                                                <span class="proposal-stat yay"><i class="fa-solid fa-thumbs-up"></i> {{ $proposal->yays }}</span>
                                                <span class="proposal-stat nay"><i class="fa-solid fa-thumbs-down"></i> {{ $proposal->nays }}</span>
                                                <div class="proposal-vote-bar">
                                                    <div class="yay-bar" style="width: {{ $proposal->yay_percent }}%"></div>
                                                    <div class="nay-bar" style="width: {{ $proposal->nay_percent }}%"></div>
                                                </div>
                                            </div>
                                            <a href="/forum/t/{{ $proposal->discussion }}" class="proposal-discussion">
                                                <i class="fa-solid fa-comment"></i> {{ $proposal->post_count }} comments
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        {{-- REJECTED TAB --}}
                        <div class="proposal-list tab-panel" id="tab-Rejected" style="display:none;">
                            @if($rejected->isEmpty())
                                <div class="empty-state">
                                    <i class="fa-solid fa-ban"></i>
                                    <h3>No Rejected Proposals</h3>
                                    <p>Proposals that fail to meet their threshold appear here.</p>
                                </div>
                            @else
                                @foreach($rejected as $proposal)
                                    <div class="proposal-card status-rejected">
                                        <div class="proposal-card-header">
                                            <span class="proposal-id">
                                                <i class="fa-solid fa-landmark" style="margin-right:4px;"></i>
                                                Bill #{{ $proposal->id }}
                                            </span>
                                            <span class="proposal-status-badge badge-rejected">
                                                <i class="fa-solid fa-xmark"></i> Rejected
                                            </span>
                                        </div>
                                        <h3 class="proposal-title">
                                            <a href="/congress/proposal/{{ $proposal->id }}">{{ $proposal->title }}</a>
                                        </h3>
                                        <div class="proposal-meta">
                                            Sponsored by <a target="_blank" href="/citizen/id/{{ $proposal->public_address }}">{{ $proposal->author }}</a>
                                            &middot; <span class="category">{{ str_replace("poll", "Certified Poll", $proposal->category) }}</span>
                                            &middot; <span class="ipfs-hash"><a target="_blank" href="{{ $proposal->ipfs_hash }}">{{ strtoupper(substr(str_replace("https://ipfs.marscoin.org/ipfs/", "", $proposal->ipfs_hash), 1, 8)) }} <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:9px;"></i></a></span>
                                        </div>
                                        <div class="proposal-excerpt">
                                            {{ substr($proposal->description, 0, 300) }}@if(strlen($proposal->description) > 300)... <a href="/congress/proposal/{{ $proposal->id }}">Read more</a>@endif
                                        </div>
                                        <div class="proposal-card-footer">
                                            <div class="proposal-stats">
                                                <span class="proposal-stat"><i class="fa-solid fa-calendar-xmark"></i> Rejected</span>
                                            </div>
                                            <a href="/forum/t/{{ $proposal->discussion }}" class="proposal-discussion">
                                                <i class="fa-solid fa-comment"></i> {{ $proposal->post_count }} comments
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        {{-- EXPIRED TAB --}}
                        <div class="proposal-list tab-panel" id="tab-Expired" style="display:none;">
                            @if($expired->isEmpty())
                                <div class="empty-state">
                                    <i class="fa-solid fa-hourglass-end"></i>
                                    <h3>No Expired Proposals</h3>
                                    <p>Proposals that exceed their voting window without sufficient participation appear here.</p>
                                </div>
                            @else
                                @foreach($expired as $proposal)
                                    <div class="proposal-card status-expired">
                                        <div class="proposal-card-header">
                                            <span class="proposal-id">
                                                <i class="fa-solid fa-landmark" style="margin-right:4px;"></i>
                                                Bill #{{ $proposal->id }}
                                            </span>
                                            <span class="proposal-status-badge badge-expired">
                                                <i class="fa-solid fa-hourglass-end"></i> Expired
                                            </span>
                                        </div>
                                        <h3 class="proposal-title">
                                            <a href="/congress/proposal/{{ $proposal->id }}">{{ $proposal->title }}</a>
                                        </h3>
                                        <div class="proposal-meta">
                                            Sponsored by <a target="_blank" href="/citizen/id/{{ $proposal->public_address }}">{{ $proposal->author }}</a>
                                            &middot; <span class="category">{{ str_replace("poll", "Certified Poll", $proposal->category) }}</span>
                                            &middot; <span class="ipfs-hash"><a target="_blank" href="{{ $proposal->ipfs_hash }}">{{ strtoupper(substr(str_replace("https://ipfs.marscoin.org/ipfs/", "", $proposal->ipfs_hash), 1, 8)) }} <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:9px;"></i></a></span>
                                        </div>
                                        <div class="proposal-excerpt">
                                            {{ substr($proposal->description, 0, 300) }}@if(strlen($proposal->description) > 300)... <a href="/congress/proposal/{{ $proposal->id }}">Read more</a>@endif
                                        </div>
                                        <div class="proposal-card-footer">
                                            <div class="proposal-stats">
                                                <span class="proposal-stat"><i class="fa-solid fa-hourglass-end"></i> Voting period elapsed</span>
                                            </div>
                                            <a href="/forum/t/{{ $proposal->discussion }}" class="proposal-discussion">
                                                <i class="fa-solid fa-comment"></i> {{ $proposal->post_count }} comments
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        {{-- CLOSED TAB --}}
                        <div class="proposal-list tab-panel" id="tab-Closed" style="display:none;">
                            @if($closed->isEmpty())
                                <div class="empty-state">
                                    <i class="fa-solid fa-folder-closed"></i>
                                    <h3>No Closed Proposals</h3>
                                    <p>Proposals that were administratively closed appear here.</p>
                                </div>
                            @else
                                @foreach($closed as $proposal)
                                    <div class="proposal-card status-closed">
                                        <div class="proposal-card-header">
                                            <span class="proposal-id">
                                                <i class="fa-solid fa-landmark" style="margin-right:4px;"></i>
                                                Bill #{{ $proposal->id }}
                                            </span>
                                            <span class="proposal-status-badge badge-closed">
                                                <i class="fa-solid fa-lock"></i> Closed
                                            </span>
                                        </div>
                                        <h3 class="proposal-title">
                                            <a href="/congress/proposal/{{ $proposal->id }}">{{ $proposal->title }}</a>
                                        </h3>
                                        <div class="proposal-meta">
                                            Sponsored by <a target="_blank" href="/citizen/id/{{ $proposal->public_address }}">{{ $proposal->author }}</a>
                                            &middot; <span class="category">{{ str_replace("poll", "Certified Poll", $proposal->category) }}</span>
                                            &middot; Reason: <strong style="color:var(--mr-text);">{{ $proposal->closed_reason }}</strong>
                                        </div>
                                        <div class="proposal-excerpt">
                                            {{ substr($proposal->description, 0, 300) }}@if(strlen($proposal->description) > 300)... <a href="/congress/proposal/{{ $proposal->id }}">Read more</a>@endif
                                        </div>
                                        <div class="proposal-card-footer">
                                            <div class="proposal-stats">
                                                <span class="proposal-stat"><i class="fa-solid fa-lock"></i> {{ $proposal->closed_reason }}</span>
                                            </div>
                                            <a href="/forum/t/{{ $proposal->discussion }}" class="proposal-discussion">
                                                <i class="fa-solid fa-comment"></i> {{ $proposal->post_count }} comments
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        {{-- ALL TAB --}}
                        <div class="proposal-list tab-panel" id="tab-All" style="display:none;">
                            @if($proposals->isEmpty())
                                <div class="empty-state">
                                    <i class="fa-solid fa-archive"></i>
                                    <h3>No Proposals</h3>
                                    <p>The congressional record is empty. Be the first to <a href="/congress/voting/new">submit legislation</a>.</p>
                                </div>
                            @else
                                @foreach($proposals as $proposal)
                                    @php
                                        $statusClass = 'status-active';
                                        $badgeClass = 'badge-active';
                                        $badgeLabel = 'Active';
                                        $badgeIcon = 'fa-circle';
                                        if ($proposal->status === 'passed') { $statusClass = 'status-passed'; $badgeClass = 'badge-passed'; $badgeLabel = 'Passed'; $badgeIcon = 'fa-check'; }
                                        elseif ($proposal->status === 'rejected') { $statusClass = 'status-rejected'; $badgeClass = 'badge-rejected'; $badgeLabel = 'Rejected'; $badgeIcon = 'fa-xmark'; }
                                        elseif ($proposal->status === 'closed') { $statusClass = 'status-closed'; $badgeClass = 'badge-closed'; $badgeLabel = 'Closed'; $badgeIcon = 'fa-lock'; }
                                        elseif ($proposal->status === 'expired' || !$proposal->active) { $statusClass = 'status-expired'; $badgeClass = 'badge-expired'; $badgeLabel = 'Expired'; $badgeIcon = 'fa-hourglass-end'; }
                                    @endphp
                                    <div class="proposal-card {{ $statusClass }}">
                                        <div class="proposal-card-header">
                                            <span class="proposal-id">
                                                <i class="fa-solid fa-landmark" style="margin-right:4px;"></i>
                                                Bill #{{ $proposal->id }}
                                            </span>
                                            <span class="proposal-status-badge {{ $badgeClass }}">
                                                <i class="fa-solid {{ $badgeIcon }}" @if($badgeIcon === 'fa-circle') style="font-size:6px;" @endif></i> {{ $badgeLabel }}
                                            </span>
                                        </div>
                                        <h3 class="proposal-title">
                                            <a href="/congress/proposal/{{ $proposal->id }}">{{ $proposal->title }}</a>
                                        </h3>
                                        <div class="proposal-meta">
                                            Sponsored by <a target="_blank" href="/citizen/id/{{ $proposal->public_address }}">{{ $proposal->author }}</a>
                                            &middot; <span class="category">{{ str_replace("poll", "Certified Poll", $proposal->category) }}</span>
                                        </div>
                                        <div class="proposal-excerpt">
                                            {{ substr($proposal->description, 0, 200) }}@if(strlen($proposal->description) > 200)... <a href="/congress/proposal/{{ $proposal->id }}">Read more</a>@endif
                                        </div>
                                        <div class="proposal-card-footer">
                                            <div class="proposal-stats">
                                                <a href="/forum/t/{{ $proposal->discussion }}" class="proposal-discussion">
                                                    <i class="fa-solid fa-comment"></i> {{ $proposal->post_count }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                    </div>

                    {{-- SIDEBAR --}}
                    <div class="col-md-3">
                        <div class="hall-sidebar">
                            <div class="sidebar-card">
                                <div class="sidebar-title">About Congress</div>
                                <p class="sidebar-text">
                                    The <strong>Martian Congressional Republic</strong> consists of citizens who discuss public matters
                                    in an open and transparent way. They vote on changes &mdash; including the very code that runs
                                    this application (<strong>"The Constitution"</strong>) &mdash; in an equally transparent yet
                                    fully anonymous way.
                                </p>
                                <p class="sidebar-text" style="margin-top:12px;">
                                    Every vote is cryptographically secured and can be <a href="https://explore.marscoin.org" target="_blank">audited by everyone</a>.
                                </p>
                            </div>
                            <div class="sidebar-card">
                                <div class="sidebar-title">Proposal Tiers</div>
                                <div class="sidebar-stat-row">
                                    <span class="sidebar-stat-label" style="color:var(--mr-green);">Signal</span>
                                    <span class="sidebar-stat-value" style="font-size:11px; color:var(--mr-green);">7d / 51%</span>
                                </div>
                                <div class="sidebar-stat-row">
                                    <span class="sidebar-stat-label" style="color:var(--mr-cyan);">Operational</span>
                                    <span class="sidebar-stat-value" style="font-size:11px; color:var(--mr-cyan);">14d / 60%</span>
                                </div>
                                <div class="sidebar-stat-row">
                                    <span class="sidebar-stat-label" style="color:var(--mr-amber);">Legislative</span>
                                    <span class="sidebar-stat-value" style="font-size:11px; color:var(--mr-amber);">30d / 66%</span>
                                </div>
                                <div class="sidebar-stat-row">
                                    <span class="sidebar-stat-label" style="color:var(--mr-mars);">Constitutional</span>
                                    <span class="sidebar-stat-value" style="font-size:11px; color:var(--mr-mars);">60d / 75%</span>
                                </div>
                                <div style="margin-top:12px; padding-top:12px; border-top:1px solid var(--mr-border);">
                                    <a href="/academy/governance-and-voting" style="font-family:'JetBrains Mono',monospace; font-size:10px; color:var(--mr-cyan); text-decoration:none; letter-spacing:1px;">
                                        <i class="fa-solid fa-book-open" style="margin-right:4px;"></i> LEARN MORE
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @else
                {{-- WALLET LOCKED STATE --}}
                <div style="text-align:center; padding:80px 20px;">
                    <i class="fa-solid fa-landmark" style="font-size:56px; color:var(--mr-text-dim,#8a8998); margin-bottom:20px; display:block; opacity:0.5;"></i>
                    <h2 style="font-family:'Orbitron',sans-serif; font-size:20px; color:#fff; letter-spacing:1px; margin-bottom:12px;">Wallet Required</h2>
                    <p style="color:var(--mr-text-dim,#8a8998); font-size:14px; margin-bottom:24px;">Unlock your civic wallet to enter the Martian Congress.</p>
                    <a href="/wallet/dashboard/hd" style="display:inline-flex; align-items:center; gap:10px; background:var(--mr-mars,#c84125); color:#fff; padding:14px 28px; border-radius:8px; font-family:'Orbitron',sans-serif; font-size:12px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; text-decoration:none; border:1px solid transparent; transition:all 0.3s ease;">
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
    @livewireScripts

    <script>
    // Tab switching
    document.querySelectorAll('.hall-filter').forEach(btn => {
        btn.addEventListener('click', function() {
            // Update active filter
            document.querySelectorAll('.hall-filter').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            // Show matching panel
            const tab = this.getAttribute('data-tab');
            document.querySelectorAll('.tab-panel').forEach(p => p.style.display = 'none');
            const target = document.getElementById('tab-' + tab);
            if (target) target.style.display = 'block';
        });
    });
    </script>
</body>
</html>
