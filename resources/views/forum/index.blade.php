<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <title>The Forum - Martian Republic</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="The Forum - Martian Republic governance discussion platform">
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
       THE FORUM — Governance Discussion Platform
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

    .forum-page {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }
    .forum-page .content { flex: 1; }
    .forum-page .footer { margin-top: auto; }

    /* ---- Hero Bar ---- */
    .forum-hero {
        position: relative;
        padding: 48px 0 32px;
        overflow: hidden;
    }
    .forum-hero::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background:
            radial-gradient(ellipse at 30% 50%, rgba(200,65,37,0.06) 0%, transparent 60%),
            radial-gradient(ellipse at 70% 20%, rgba(0,228,255,0.03) 0%, transparent 50%);
        pointer-events: none;
    }
    .forum-hero::after {
        content: '';
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--mr-border-bright) 20%, var(--mr-mars) 50%, var(--mr-border-bright) 80%, transparent);
    }

    .forum-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
    }
    .forum-title-block {}
    .forum-status {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: var(--mr-mars);
        margin-bottom: 8px;
        display: block;
    }
    .forum-status .dot {
        display: inline-block;
        width: 6px; height: 6px;
        border-radius: 50%;
        background: var(--mr-green);
        margin-right: 6px;
        animation: pulse 2s infinite;
        vertical-align: middle;
    }
    .forum-title {
        font-family: 'Orbitron', sans-serif;
        font-weight: 800;
        font-size: 36px;
        letter-spacing: 2px;
        color: #fff;
        text-transform: uppercase;
        margin: 0;
    }

    .forum-actions {
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }
    .btn-new-thread {
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
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .btn-new-thread:hover, .btn-new-thread:focus {
        background: transparent !important;
        border-color: var(--mr-mars) !important;
        color: var(--mr-mars) !important;
        box-shadow: 0 0 30px rgba(200,65,37,0.2);
        text-decoration: none !important;
    }
    .btn-new-thread i { font-size: 14px; }

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
    .forum-filters {
        display: flex;
        gap: 2px;
        margin: 32px 0 28px;
        background: var(--mr-border);
        border-radius: 8px;
        overflow: hidden;
    }
    .forum-filter {
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
    .forum-filter:first-child { border-radius: 8px 0 0 8px; }
    .forum-filter:last-child { border-radius: 0 8px 8px 0; }
    .forum-filter:hover {
        background: var(--mr-surface-raised);
        color: var(--mr-text);
    }
    .forum-filter.active {
        background: var(--mr-surface-raised);
        color: #fff;
    }
    .forum-filter.active::after {
        content: '';
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 2px;
        background: var(--mr-mars);
    }
    .forum-filter .count {
        display: inline-block;
        background: var(--mr-border);
        border-radius: 10px;
        padding: 1px 7px;
        font-size: 10px;
        margin-left: 4px;
        color: var(--mr-text-dim);
    }
    .forum-filter.active .count {
        background: var(--mr-mars-glow);
        color: var(--mr-mars);
    }

    /* ---- Quick Compose Bar ---- */
    .forum-quick-compose {
        display: flex;
        gap: 12px;
        align-items: center;
        background: var(--mr-surface);
        border: 1px solid var(--mr-border);
        border-radius: 10px;
        padding: 16px 20px;
        margin-bottom: 24px;
    }
    .forum-quick-compose input[type="text"] {
        flex: 1;
        background: var(--mr-dark);
        border: 1px solid var(--mr-border);
        border-radius: 6px;
        padding: 10px 16px;
        color: var(--mr-text);
        font-family: 'DM Sans', sans-serif;
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s;
    }
    .forum-quick-compose input[type="text"]:focus {
        border-color: var(--mr-border-bright);
    }
    .forum-quick-compose input[type="text"]::placeholder {
        color: var(--mr-text-dim);
    }
    .forum-quick-compose select {
        background: var(--mr-dark);
        border: 1px solid var(--mr-border);
        border-radius: 6px;
        padding: 10px 16px;
        color: var(--mr-text);
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        letter-spacing: 0.5px;
        outline: none;
        cursor: pointer;
        min-width: 160px;
    }
    .forum-quick-compose select option {
        background: var(--mr-dark);
        color: var(--mr-text);
    }
    .forum-quick-compose .btn-post {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--mr-mars);
        color: #fff;
        border: 1px solid transparent;
        border-radius: 6px;
        padding: 10px 24px;
        font-family: 'Orbitron', sans-serif;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
    }
    .forum-quick-compose .btn-post:hover {
        background: transparent;
        border-color: var(--mr-mars);
        color: var(--mr-mars);
        box-shadow: 0 0 20px rgba(200,65,37,0.15);
    }

    /* ---- Thread List ---- */
    .forum-thread {
        display: flex;
        background: var(--mr-surface);
        border: 1px solid var(--mr-border);
        border-radius: 10px;
        margin-bottom: 4px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        animation: fadeIn 0.4s ease-out both;
    }
    .forum-thread:nth-child(1) { animation-delay: 0.03s; }
    .forum-thread:nth-child(2) { animation-delay: 0.06s; }
    .forum-thread:nth-child(3) { animation-delay: 0.09s; }
    .forum-thread:nth-child(4) { animation-delay: 0.12s; }
    .forum-thread:nth-child(5) { animation-delay: 0.15s; }
    .forum-thread:nth-child(6) { animation-delay: 0.18s; }
    .forum-thread:nth-child(7) { animation-delay: 0.21s; }
    .forum-thread:nth-child(8) { animation-delay: 0.24s; }

    .forum-thread:hover {
        border-color: var(--mr-border-bright);
        background: var(--mr-surface-raised);
        transform: translateY(-1px);
    }
    .forum-thread:hover .forum-thread-accent {
        opacity: 1;
        width: 5px;
    }
    .forum-thread.is-locked {
        opacity: 0.7;
    }
    .forum-thread.is-locked:hover {
        opacity: 0.85;
    }

    .forum-thread-accent {
        width: 4px;
        min-height: 100%;
        flex-shrink: 0;
        border-radius: 10px 0 0 10px;
        opacity: 0.8;
        transition: all 0.3s ease;
    }

    .forum-thread-body {
        flex: 1;
        padding: 18px 24px;
        min-width: 0;
    }

    .forum-thread-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 6px;
    }

    .forum-thread-title {
        font-family: 'DM Sans', sans-serif;
        font-size: 16px;
        font-weight: 600;
        color: #fff;
        line-height: 1.4;
        min-width: 0;
    }
    .forum-thread-title a {
        color: #fff;
        text-decoration: none;
        transition: color 0.2s;
    }
    .forum-thread-title a:hover {
        color: var(--mr-cyan);
    }
    .forum-thread-title i {
        font-size: 12px;
        margin-right: 6px;
        color: var(--mr-text-dim);
    }
    .forum-thread-title i.fa-thumbtack {
        color: var(--mr-amber);
    }
    .forum-thread-title i.fa-lock {
        color: var(--mr-text-dim);
    }

    .forum-thread-stats {
        flex-shrink: 0;
    }
    .forum-reply-count {
        font-family: 'JetBrains Mono', monospace;
        font-size: 12px;
        color: var(--mr-text-dim);
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .forum-reply-count i {
        font-size: 12px;
    }

    .forum-thread-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .forum-category-label {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        letter-spacing: 1px;
        text-transform: uppercase;
        font-weight: 500;
    }
    .forum-author {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        color: var(--mr-text-dim);
    }
    .forum-time {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        color: var(--mr-text-dim);
    }

    /* ---- Proposal Link Badge in Thread ---- */
    .forum-proposal-link {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-top: 8px;
        padding: 6px 12px;
        background: var(--mr-dark);
        border: 1px solid var(--mr-border);
        border-radius: 6px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        color: var(--mr-text-dim);
        letter-spacing: 0.5px;
    }
    .tier-badge {
        font-family: 'JetBrains Mono', monospace;
        font-size: 9px;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        padding: 2px 8px;
        border-radius: 3px;
        font-weight: 500;
    }
    .tier-badge.tier-signal {
        background: rgba(52,211,153,0.1);
        color: var(--mr-green);
        border: 1px solid rgba(52,211,153,0.2);
    }
    .tier-badge.tier-operational {
        background: rgba(0,228,255,0.08);
        color: var(--mr-cyan);
        border: 1px solid rgba(0,228,255,0.15);
    }
    .tier-badge.tier-legislative {
        background: rgba(245,158,11,0.1);
        color: var(--mr-amber);
        border: 1px solid rgba(245,158,11,0.2);
    }
    .tier-badge.tier-constitutional {
        background: rgba(200,65,37,0.1);
        color: var(--mr-mars);
        border: 1px solid rgba(200,65,37,0.2);
    }
    .forum-proposal-status {
        color: var(--mr-green);
    }
    .forum-proposal-status.status-closed {
        color: var(--mr-text-dim);
    }
    .forum-proposal-status.status-rejected {
        color: var(--mr-red);
    }

    /* ---- Sidebar ---- */
    .forum-sidebar {}
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
        font-family: 'DM Sans', sans-serif;
        font-size: 13px;
        color: var(--mr-text-dim);
    }
    .sidebar-stat-value {
        font-family: 'JetBrains Mono', monospace;
        font-size: 12px;
        font-weight: 500;
        color: #fff;
    }

    .sidebar-cat-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 8px;
    }

    .online-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .online-user {
        display: flex;
        align-items: center;
        gap: 8px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        color: var(--mr-text-dim);
    }
    .online-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--mr-green);
        animation: pulse 2s infinite;
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

    /* ---- Responsive ---- */
    @media (max-width: 991px) {
        .forum-header { flex-direction: column; }
        .forum-filters { flex-wrap: wrap; }
        .forum-filter { flex: 0 0 calc(33.333% - 2px); }
        .forum-quick-compose { flex-direction: column; }
        .forum-quick-compose select { min-width: 100%; }
    }
    @media (max-width: 768px) {
        .forum-title { font-size: 24px; }
        .forum-filter { flex: 0 0 calc(50% - 2px); }
        .forum-thread-body { padding: 14px 16px; }
        .forum-thread-header { flex-direction: column; gap: 8px; }
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
    </style>
</head>

<body class="forum-page">
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
        @include('wallet.mainnav', array('active'=>'forum'))

        <div class="content">
            <div class="container">

                {{-- HERO BAR --}}
                <div class="forum-hero">
                    <div class="forum-header">
                        <div class="forum-title-block">
                            <span class="forum-status">
                                <span class="dot"></span> The Forum &mdash; Open Session
                            </span>
                            <h1 class="forum-title">Deliberation</h1>
                        </div>
                        <div class="forum-actions">
                            @auth
                                <button class="btn-new-thread" onclick="document.getElementById('quick-title').focus();">
                                    <i class="fa-solid fa-pen-to-square"></i> New Thread
                                </button>
                            @else
                                <div class="observer-badge">
                                    <i class="fa-solid fa-eye"></i>
                                    Observer Mode &mdash; <a href="/login">Sign in</a> to participate
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>

                {{-- CATEGORY FILTER TABS --}}
                <div class="forum-filters" id="forum-filters">
                    <button class="forum-filter active" data-cat="all">
                        All
                        @if(isset($threads) && $threads->count() > 0)
                            <span class="count">{{ $threads->count() }}</span>
                        @endif
                    </button>
                    @if(isset($categories))
                        @foreach($categories as $category)
                            <button class="forum-filter" data-cat="{{ $category->id }}">
                                {{ $category->title }}
                                @if($category->thread_count > 0)
                                    <span class="count">{{ $category->thread_count }}</span>
                                @endif
                            </button>
                        @endforeach
                    @endif
                </div>

                {{-- QUICK COMPOSE BAR --}}
                @auth
                <div class="forum-quick-compose">
                    <input type="text" placeholder="Start a new thread..." id="quick-title">
                    <select id="quick-category">
                        <option value="">Select category...</option>
                        @if(isset($categories))
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->title }}</option>
                            @endforeach
                        @endif
                    </select>
                    <button class="btn-post" id="quick-submit">
                        <i class="fa-solid fa-paper-plane"></i> Post
                    </button>
                </div>
                @endauth

                {{-- MAIN CONTENT --}}
                <div class="row">
                    <div class="col-md-9">

                        @if(isset($threads) && $threads->count() > 0)

                            {{-- Pinned threads first --}}
                            @foreach($threads->where('pinned', true) as $thread)
                                @php
                                    $catColors = [
                                        1 => '#00e4ff',
                                        2 => '#f59e0b',
                                        3 => '#34d399',
                                        4 => '#c84125',
                                        5 => '#a78bfa',
                                    ];
                                    $catColor = $catColors[$thread->category_id] ?? 'var(--mr-cyan)';
                                @endphp
                                <div class="forum-thread {{ $thread->locked ? 'is-locked' : '' }}" data-cat="{{ $thread->category_id }}">
                                    <div class="forum-thread-accent" style="background: {{ $catColor }};"></div>
                                    <div class="forum-thread-body">
                                        <div class="forum-thread-header">
                                            <div class="forum-thread-title">
                                                <i class="fa-solid fa-thumbtack"></i>
                                                @if($thread->locked) <i class="fa-solid fa-lock"></i> @endif
                                                <a href="/forum/t/{{ $thread->id }}-{{ Str::slug($thread->title) }}">{{ $thread->title }}</a>
                                            </div>
                                            <div class="forum-thread-stats">
                                                <span class="forum-reply-count">{{ $thread->reply_count ?? 0 }} <i class="fa-regular fa-comment"></i></span>
                                            </div>
                                        </div>
                                        <div class="forum-thread-meta">
                                            <span class="forum-category-label" style="color: {{ $catColor }};">{{ $thread->category_title ?? 'General' }}</span>
                                            <span class="forum-author">{{ $thread->author_name ?? 'Anonymous' }}</span>
                                            <span class="forum-time">{{ $thread->created_at->diffForHumans() }}</span>
                                        </div>
                                        @if($thread->proposal)
                                        <div class="forum-proposal-link">
                                            @php
                                                $tierClass = 'tier-signal';
                                                $tierName = $thread->proposal->category ?? 'Signal';
                                                if (stripos($tierName, 'operational') !== false) $tierClass = 'tier-operational';
                                                elseif (stripos($tierName, 'legislative') !== false) $tierClass = 'tier-legislative';
                                                elseif (stripos($tierName, 'constitutional') !== false) $tierClass = 'tier-constitutional';
                                            @endphp
                                            <span class="tier-badge {{ $tierClass }}">{{ strtoupper($tierName) }}</span>
                                            <span>Bill #{{ $thread->proposal->id }}</span>
                                            <span class="forum-proposal-status {{ $thread->proposal->active ? '' : 'status-closed' }}">
                                                {{ $thread->proposal->active ? 'VOTING OPEN' : strtoupper($thread->proposal->status ?? 'CLOSED') }}
                                            </span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            {{-- Regular threads --}}
                            @foreach($threads->where('pinned', false) as $thread)
                                @php
                                    $catColors = [
                                        1 => '#00e4ff',
                                        2 => '#f59e0b',
                                        3 => '#34d399',
                                        4 => '#c84125',
                                        5 => '#a78bfa',
                                    ];
                                    $catColor = $catColors[$thread->category_id] ?? 'var(--mr-cyan)';
                                @endphp
                                <div class="forum-thread {{ $thread->locked ? 'is-locked' : '' }}" data-cat="{{ $thread->category_id }}">
                                    <div class="forum-thread-accent" style="background: {{ $catColor }};"></div>
                                    <div class="forum-thread-body">
                                        <div class="forum-thread-header">
                                            <div class="forum-thread-title">
                                                @if($thread->locked) <i class="fa-solid fa-lock"></i> @endif
                                                <a href="/forum/t/{{ $thread->id }}-{{ Str::slug($thread->title) }}">{{ $thread->title }}</a>
                                            </div>
                                            <div class="forum-thread-stats">
                                                <span class="forum-reply-count">{{ $thread->reply_count ?? 0 }} <i class="fa-regular fa-comment"></i></span>
                                            </div>
                                        </div>
                                        <div class="forum-thread-meta">
                                            <span class="forum-category-label" style="color: {{ $catColor }};">{{ $thread->category_title ?? 'General' }}</span>
                                            <span class="forum-author">{{ $thread->author_name ?? 'Anonymous' }}</span>
                                            <span class="forum-time">{{ $thread->created_at->diffForHumans() }}</span>
                                        </div>
                                        @if($thread->proposal)
                                        <div class="forum-proposal-link">
                                            @php
                                                $tierClass = 'tier-signal';
                                                $tierName = $thread->proposal->category ?? 'Signal';
                                                if (stripos($tierName, 'operational') !== false) $tierClass = 'tier-operational';
                                                elseif (stripos($tierName, 'legislative') !== false) $tierClass = 'tier-legislative';
                                                elseif (stripos($tierName, 'constitutional') !== false) $tierClass = 'tier-constitutional';
                                            @endphp
                                            <span class="tier-badge {{ $tierClass }}">{{ strtoupper($tierName) }}</span>
                                            <span>Bill #{{ $thread->proposal->id }}</span>
                                            <span class="forum-proposal-status {{ $thread->proposal->active ? '' : 'status-closed' }}">
                                                {{ $thread->proposal->active ? 'VOTING OPEN' : strtoupper($thread->proposal->status ?? 'CLOSED') }}
                                            </span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                        @else
                            <div class="empty-state">
                                <i class="fa-solid fa-comments"></i>
                                <h3>No Threads Yet</h3>
                                <p>The Forum is quiet. Start a new thread to begin the discussion.</p>
                            </div>
                        @endif

                    </div>

                    {{-- SIDEBAR --}}
                    <div class="col-md-3">
                        <div class="forum-sidebar">

                            {{-- Online Now --}}
                            <div class="sidebar-card">
                                <div class="sidebar-title">Online Now</div>
                                @if(isset($onlineUsers) && count($onlineUsers) > 0)
                                    <div class="online-list">
                                        @foreach($onlineUsers as $user)
                                            <div class="online-user">
                                                <span class="online-dot"></span>
                                                {{ $user->name ?? $user->email }}
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="sidebar-text">No users currently online.</p>
                                @endif
                            </div>

                            {{-- Categories --}}
                            <div class="sidebar-card">
                                <div class="sidebar-title">Categories</div>
                                @if(isset($categories))
                                    @php
                                        $catColors = [
                                            1 => '#00e4ff',
                                            2 => '#f59e0b',
                                            3 => '#34d399',
                                            4 => '#c84125',
                                            5 => '#a78bfa',
                                        ];
                                    @endphp
                                    @foreach($categories as $category)
                                        <div class="sidebar-stat-row">
                                            <span class="sidebar-stat-label">
                                                <span class="sidebar-cat-dot" style="background: {{ $catColors[$category->id] ?? 'var(--mr-text-dim)' }};"></span>
                                                {{ $category->title }}
                                            </span>
                                            <span class="sidebar-stat-value">{{ $category->thread_count ?? 0 }}</span>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            {{-- About The Forum --}}
                            <div class="sidebar-card">
                                <div class="sidebar-title">About The Forum</div>
                                <p class="sidebar-text">
                                    <strong>The Forum</strong> is where citizens of the Martian Republic deliberate on public matters,
                                    debate proposals before they go to vote, and shape the future of the colony through open discourse.
                                </p>
                                <p class="sidebar-text" style="margin-top:12px;">
                                    Every proposal in <a href="/congress/voting">Congress</a> has a linked discussion thread here.
                                </p>
                            </div>

                        </div>
                    </div>
                </div>

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
    // Category filter: show/hide threads by data-cat attribute
    document.querySelectorAll('.forum-filter').forEach(function(btn) {
        btn.addEventListener('click', function() {
            // Update active filter
            document.querySelectorAll('.forum-filter').forEach(function(b) { b.classList.remove('active'); });
            this.classList.add('active');

            var cat = this.getAttribute('data-cat');
            document.querySelectorAll('.forum-thread').forEach(function(thread) {
                if (cat === 'all') {
                    thread.style.display = 'flex';
                } else {
                    thread.style.display = (thread.getAttribute('data-cat') === cat) ? 'flex' : 'none';
                }
            });
        });
    });

    // Quick compose: AJAX POST to /forum/thread, redirect to new thread
    $(document).ready(function() {
        $('#quick-submit').on('click', function() {
            var title = $('#quick-title').val().trim();
            var categoryId = $('#quick-category').val();

            if (!title) {
                toastr.warning('Please enter a thread title.');
                $('#quick-title').focus();
                return;
            }
            if (!categoryId) {
                toastr.warning('Please select a category.');
                return;
            }

            var $btn = $(this);
            $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Posting...');

            $.ajax({
                url: '/forum/thread',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    title: title,
                    category_id: categoryId
                },
                success: function(response) {
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    } else if (response.thread && response.thread.id) {
                        window.location.href = '/forum/t/' + response.thread.id + '-' + title.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
                    } else {
                        window.location.reload();
                    }
                },
                error: function(xhr) {
                    $btn.prop('disabled', false).html('<i class="fa-solid fa-paper-plane"></i> Post');
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message);
                    } else {
                        toastr.error('Failed to create thread. Please try again.');
                    }
                }
            });
        });

        // Submit on Enter key in title field
        $('#quick-title').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                $('#quick-submit').click();
            }
        });
    });
    </script>
</body>
</html>
