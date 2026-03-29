<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <title>{{ $thread->title ?? 'Thread' }} - The Forum - Martian Republic</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Forum discussion - Martian Republic">
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
       THE FORUM — Thread View
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

    /* ---- Breadcrumbs ---- */
    .forum-breadcrumbs {
        padding: 20px 0 0;
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        letter-spacing: 1px;
        color: var(--mr-text-dim);
    }
    .forum-breadcrumbs a {
        color: var(--mr-text-dim);
        text-decoration: none;
        transition: color 0.2s;
    }
    .forum-breadcrumbs a:hover {
        color: var(--mr-cyan);
    }
    .forum-breadcrumbs .sep {
        margin: 0 8px;
        color: var(--mr-border-bright);
    }
    .forum-breadcrumbs .current {
        color: var(--mr-text);
    }

    /* ---- Proposal Bar ---- */
    .forum-proposal-bar {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
        background: var(--mr-surface);
        border: 1px solid var(--mr-border);
        border-left: 3px solid var(--mr-green);
        border-radius: 0 10px 10px 0;
        padding: 16px 24px;
        margin: 20px 0 0;
    }
    .forum-proposal-bar .bill-id {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        letter-spacing: 1px;
        color: var(--mr-text);
        background: var(--mr-dark);
        border: 1px solid var(--mr-border);
        border-radius: 4px;
        padding: 4px 10px;
    }
    .tier-badge {
        font-family: 'JetBrains Mono', monospace;
        font-size: 9px;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        padding: 3px 10px;
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
    .proposal-progress-wrap {
        flex: 1;
        min-width: 120px;
        max-width: 200px;
    }
    .proposal-progress-bar {
        display: flex;
        height: 4px;
        border-radius: 2px;
        overflow: hidden;
        background: var(--mr-dark);
    }
    .proposal-progress-bar .yay-bar {
        background: var(--mr-green);
        transition: width 0.5s ease;
    }
    .proposal-progress-bar .nay-bar {
        background: var(--mr-red);
        transition: width 0.5s ease;
    }
    .proposal-time-remaining {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        color: var(--mr-text-dim);
    }
    .proposal-bar-actions {
        display: flex;
        gap: 8px;
        margin-left: auto;
    }
    .proposal-bar-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 6px;
        font-family: 'Orbitron', sans-serif;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        text-decoration: none;
        transition: all 0.3s ease;
        cursor: pointer;
        border: 1px solid;
    }
    .proposal-bar-btn.view-btn {
        background: transparent;
        border-color: var(--mr-border-bright);
        color: var(--mr-text-dim);
    }
    .proposal-bar-btn.view-btn:hover {
        border-color: var(--mr-cyan);
        color: var(--mr-cyan);
        text-decoration: none;
    }
    .proposal-bar-btn.vote-btn {
        background: var(--mr-mars);
        border-color: var(--mr-mars);
        color: #fff;
    }
    .proposal-bar-btn.vote-btn:hover {
        background: transparent;
        color: var(--mr-mars);
        text-decoration: none;
    }

    /* ---- Thread Header ---- */
    .forum-thread-header-full {
        padding: 32px 0 24px;
        border-bottom: 1px solid var(--mr-border);
        margin-bottom: 0;
    }
    .forum-thread-header-full h1 {
        font-family: 'DM Sans', sans-serif;
        font-size: 28px;
        font-weight: 700;
        color: #fff;
        margin: 0 0 10px;
        line-height: 1.3;
    }
    .forum-thread-info {
        font-family: 'JetBrains Mono', monospace;
        font-size: 12px;
        color: var(--mr-text-dim);
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }
    .forum-thread-info .locked-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        color: var(--mr-amber);
        background: rgba(245,158,11,0.1);
        border: 1px solid rgba(245,158,11,0.2);
        border-radius: 3px;
        padding: 2px 8px;
        font-size: 10px;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    /* ---- Posts ---- */
    .forum-posts {
        padding-top: 2px;
    }
    .forum-post {
        display: flex;
        background: var(--mr-surface);
        border: 1px solid var(--mr-border);
        border-radius: 10px;
        margin-top: 2px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        animation: fadeIn 0.4s ease-out both;
    }
    .forum-post:first-child {
        margin-top: 0;
    }
    .forum-post:hover {
        border-color: var(--mr-border-bright);
    }
    .forum-post:hover .forum-post-accent {
        background: var(--mr-cyan);
        opacity: 1;
    }

    .forum-post-accent {
        width: 3px;
        min-height: 100%;
        flex-shrink: 0;
        background: var(--mr-border);
        border-radius: 10px 0 0 10px;
        opacity: 0.6;
        transition: all 0.3s ease;
    }

    .forum-post-content {
        flex: 1;
        padding: 20px 24px;
        min-width: 0;
    }

    .forum-post-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 14px;
    }

    .forum-post-author {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .author-name {
        font-family: 'Orbitron', sans-serif;
        font-size: 12px;
        font-weight: 600;
        color: var(--mr-text);
        letter-spacing: 0.5px;
    }
    .citizen-badge {
        font-family: 'JetBrains Mono', monospace;
        font-size: 9px;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        padding: 2px 8px;
        border-radius: 3px;
        font-weight: 500;
    }
    .citizen-badge.citizen {
        background: rgba(0,228,255,0.08);
        color: var(--mr-cyan);
        border: 1px solid rgba(0,228,255,0.2);
    }
    .citizen-badge.general {
        background: rgba(138,137,152,0.1);
        color: var(--mr-text-dim);
        border: 1px solid rgba(138,137,152,0.15);
    }

    .forum-post-time {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        color: var(--mr-text-dim);
        white-space: nowrap;
    }

    /* ---- Quoted Content ---- */
    .forum-quote {
        border-left: 2px solid var(--mr-cyan);
        background: rgba(0,228,255,0.03);
        border-radius: 0 6px 6px 0;
        padding: 10px 16px;
        margin-bottom: 14px;
    }
    .forum-quote .quote-author {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: var(--mr-cyan);
        display: block;
        margin-bottom: 4px;
    }
    .forum-quote p {
        font-family: 'DM Sans', sans-serif;
        font-size: 13px;
        font-style: italic;
        color: var(--mr-text-dim);
        margin: 0;
        line-height: 1.6;
    }

    /* ---- Post Body ---- */
    .forum-post-body {
        font-family: 'DM Sans', sans-serif;
        font-size: 14px;
        line-height: 1.8;
        color: var(--mr-text);
    }
    .forum-post-body p {
        margin-bottom: 12px;
    }
    .forum-post-body p:last-child {
        margin-bottom: 0;
    }
    .forum-post-body a {
        color: var(--mr-cyan);
        text-decoration: none;
    }
    .forum-post-body a:hover {
        color: #fff;
        text-decoration: underline;
    }
    .forum-post-body strong {
        color: #fff;
        font-weight: 600;
    }
    .forum-post-body em {
        font-style: italic;
        color: var(--mr-text);
    }
    .forum-post-body code {
        font-family: 'JetBrains Mono', monospace;
        font-size: 13px;
        background: var(--mr-dark);
        border: 1px solid var(--mr-border);
        border-radius: 4px;
        padding: 2px 6px;
        color: var(--mr-cyan);
    }
    .forum-post-body pre {
        background: var(--mr-dark);
        border: 1px solid var(--mr-border);
        border-radius: 6px;
        padding: 16px;
        overflow-x: auto;
        margin: 12px 0;
    }
    .forum-post-body pre code {
        background: none;
        border: none;
        padding: 0;
        color: var(--mr-text);
        font-size: 12px;
        line-height: 1.6;
    }
    .forum-post-body blockquote {
        border-left: 2px solid var(--mr-cyan);
        background: rgba(0,228,255,0.03);
        padding: 10px 16px;
        margin: 12px 0;
        border-radius: 0 6px 6px 0;
        color: var(--mr-text-dim);
        font-style: italic;
    }
    .forum-post-body ul, .forum-post-body ol {
        margin: 8px 0;
        padding-left: 24px;
    }
    .forum-post-body li {
        margin-bottom: 4px;
    }

    /* ---- Post Actions ---- */
    .forum-post-actions {
        display: flex;
        gap: 4px;
        margin-top: 14px;
        padding-top: 12px;
        border-top: 1px solid var(--mr-border);
    }
    .post-action {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: transparent;
        border: 1px solid transparent;
        border-radius: 4px;
        padding: 5px 12px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        letter-spacing: 0.5px;
        color: var(--mr-text-dim);
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .post-action:hover {
        color: var(--mr-cyan);
        border-color: rgba(0,228,255,0.15);
        background: rgba(0,228,255,0.03);
    }
    .post-action i {
        font-size: 11px;
    }

    /* ---- Composer ---- */
    .forum-composer {
        position: sticky;
        bottom: 16px;
        background: var(--mr-dark);
        border: 1px solid var(--mr-border);
        border-radius: 10px;
        margin-top: 24px;
        overflow: hidden;
        z-index: 50;
        box-shadow: 0 -8px 32px rgba(0,0,0,0.4);
    }
    .composer-toolbar {
        display: flex;
        align-items: center;
        gap: 2px;
        padding: 8px 12px;
        background: var(--mr-surface);
        border-bottom: 1px solid var(--mr-border);
    }
    .composer-toolbar button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        background: transparent;
        border: 1px solid transparent;
        border-radius: 4px;
        color: var(--mr-text-dim);
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 13px;
    }
    .composer-toolbar button:hover {
        color: var(--mr-cyan);
        background: rgba(0,228,255,0.05);
        border-color: rgba(0,228,255,0.1);
    }
    .composer-spacer {
        flex: 1;
    }
    .preview-toggle {
        display: flex;
        align-items: center;
        gap: 6px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        letter-spacing: 0.5px;
        color: var(--mr-text-dim);
        cursor: pointer;
        margin: 0;
    }
    .preview-toggle input[type="checkbox"] {
        accent-color: var(--mr-cyan);
    }

    #reply-indicator {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: rgba(0,228,255,0.03);
        border-bottom: 1px solid rgba(0,228,255,0.1);
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        color: var(--mr-text-dim);
    }
    #reply-indicator #reply-to-name {
        color: var(--mr-cyan);
        font-weight: 500;
    }
    #reply-indicator button {
        background: transparent;
        border: none;
        color: var(--mr-text-dim);
        cursor: pointer;
        font-size: 16px;
        padding: 0 4px;
        margin-left: 4px;
        transition: color 0.2s;
    }
    #reply-indicator button:hover {
        color: var(--mr-red);
    }

    #post-content {
        width: 100%;
        min-height: 120px;
        max-height: 300px;
        background: transparent;
        border: none;
        padding: 16px 20px;
        color: var(--mr-text);
        font-family: 'DM Sans', sans-serif;
        font-size: 14px;
        line-height: 1.7;
        resize: vertical;
        outline: none;
    }
    #post-content::placeholder {
        color: var(--mr-text-dim);
    }

    #post-preview {
        padding: 16px 20px;
        min-height: 80px;
        border-top: 1px solid var(--mr-border);
    }

    .composer-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px;
        border-top: 1px solid var(--mr-border);
    }
    .composer-hint {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        letter-spacing: 0.5px;
        color: var(--mr-text-dim);
    }
    .composer-submit {
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
    }
    .composer-submit:hover {
        background: transparent;
        border-color: var(--mr-mars);
        color: var(--mr-mars);
        box-shadow: 0 0 20px rgba(200,65,37,0.15);
    }
    .composer-submit:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    .composer-submit:disabled:hover {
        background: var(--mr-mars);
        color: #fff;
        box-shadow: none;
    }

    /* ---- Login Prompt ---- */
    .forum-login-prompt {
        text-align: center;
        padding: 40px 20px;
        margin-top: 24px;
        background: var(--mr-surface);
        border: 1px solid var(--mr-border);
        border-radius: 10px;
        font-family: 'DM Sans', sans-serif;
        font-size: 14px;
        color: var(--mr-text-dim);
    }
    .forum-login-prompt a {
        color: var(--mr-cyan);
        text-decoration: none;
        font-weight: 600;
    }
    .forum-login-prompt a:hover {
        color: #fff;
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

    /* ---- Responsive ---- */
    @media (max-width: 991px) {
        .forum-proposal-bar { flex-direction: column; align-items: flex-start; }
        .proposal-bar-actions { margin-left: 0; width: 100%; }
        .proposal-bar-btn { flex: 1; justify-content: center; }
    }
    @media (max-width: 768px) {
        .forum-thread-header-full h1 { font-size: 22px; }
        .forum-post-content { padding: 14px 16px; }
        .forum-post-header { flex-direction: column; align-items: flex-start; gap: 6px; }
        .forum-composer { position: relative; bottom: auto; margin-bottom: 24px; }
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
    .forum-post:nth-child(1) { animation-delay: 0.03s; }
    .forum-post:nth-child(2) { animation-delay: 0.06s; }
    .forum-post:nth-child(3) { animation-delay: 0.09s; }
    .forum-post:nth-child(4) { animation-delay: 0.12s; }
    .forum-post:nth-child(5) { animation-delay: 0.15s; }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .new-post-highlight {
        animation: slideUp 0.4s ease-out;
        border-color: rgba(0,228,255,0.2) !important;
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

                {{-- BREADCRUMBS --}}
                <div class="forum-breadcrumbs">
                    <a href="/forum">The Forum</a>
                    <span class="sep">&rsaquo;</span>
                    @if(isset($thread->category))
                        <a href="/forum/c/{{ $thread->category_id }}-{{ Str::slug($thread->category_title ?? '') }}">{{ $thread->category_title ?? 'General' }}</a>
                        <span class="sep">&rsaquo;</span>
                    @endif
                    <span class="current">{{ Str::limit($thread->title, 60) }}</span>
                </div>

                {{-- PROPOSAL LINK BAR --}}
                @if(isset($proposal) && $proposal)
                    @php
                        $tierClass = 'tier-signal';
                        $tierName = $proposal->category ?? 'Signal';
                        if (stripos($tierName, 'operational') !== false) $tierClass = 'tier-operational';
                        elseif (stripos($tierName, 'legislative') !== false) $tierClass = 'tier-legislative';
                        elseif (stripos($tierName, 'constitutional') !== false) $tierClass = 'tier-constitutional';

                        $proposalActive = $proposal->active ?? false;
                        $timeRemaining = '';
                        if ($proposalActive && isset($proposal->mined) && isset($proposal->duration)) {
                            $endTime = \Carbon\Carbon::parse($proposal->mined)->addDays($proposal->duration);
                            $remaining = now()->diff($endTime);
                            $isExpired = now()->gt($endTime);
                            $timeRemaining = $isExpired ? 'Voting ended' : $remaining->days . 'd ' . $remaining->h . 'h remaining';
                        }

                        $yayPct = $proposal->yay_percent ?? 0;
                        $nayPct = $proposal->nay_percent ?? 0;
                    @endphp
                    <div class="forum-proposal-bar">
                        <span class="bill-id">Bill #{{ $proposal->id }}</span>
                        <span class="tier-badge {{ $tierClass }}">{{ strtoupper($tierName) }}</span>
                        @if($proposalActive)
                            <div class="proposal-progress-wrap">
                                <div class="proposal-progress-bar">
                                    <div class="yay-bar" style="width: {{ $yayPct }}%"></div>
                                    <div class="nay-bar" style="width: {{ $nayPct }}%"></div>
                                </div>
                            </div>
                            <span class="proposal-time-remaining">
                                <i class="fa-solid fa-clock" style="margin-right:4px;"></i>{{ $timeRemaining }}
                            </span>
                        @else
                            <span class="proposal-time-remaining">
                                {{ strtoupper($proposal->status ?? 'CLOSED') }}
                            </span>
                        @endif
                        <div class="proposal-bar-actions">
                            <a href="/congress/proposal/{{ $proposal->id }}" class="proposal-bar-btn view-btn">
                                <i class="fa-solid fa-landmark"></i> View Proposal
                            </a>
                            @if($proposalActive)
                                <a href="/congress/proposal/{{ $proposal->id }}" class="proposal-bar-btn vote-btn">
                                    <i class="fa-solid fa-check-to-slot"></i> Vote
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- THREAD HEADER --}}
                <div class="forum-thread-header-full">
                    <h1>{{ $thread->title }}</h1>
                    <div class="forum-thread-info">
                        <span>Started by {{ $thread->author_name ?? 'Anonymous' }}</span>
                        <span>&middot;</span>
                        <span>{{ $thread->created_at->format('M j, Y') }}</span>
                        <span>&middot;</span>
                        <span>{{ isset($posts) ? $posts->count() : 0 }} {{ (isset($posts) && $posts->count() === 1) ? 'post' : 'posts' }}</span>
                        @if($thread->locked)
                            <span>&middot;</span>
                            <span class="locked-badge"><i class="fa-solid fa-lock"></i> Locked</span>
                        @endif
                    </div>
                </div>

                {{-- POSTS --}}
                <div class="forum-posts" id="forum-posts">
                    @if(isset($posts) && $posts->count() > 0)
                        @foreach($posts as $post)
                            <div class="forum-post" id="post-{{ $post->id }}">
                                <div class="forum-post-accent"></div>
                                <div class="forum-post-content">
                                    <div class="forum-post-header">
                                        <div class="forum-post-author">
                                            <span class="author-name">{{ $post->author_name ?? 'Anonymous' }}</span>
                                            @php
                                                $isCitizen = false;
                                                if (isset($post->user) && isset($post->user->citizen)) {
                                                    $isCitizen = !empty($post->user->citizen);
                                                } elseif (isset($post->is_citizen)) {
                                                    $isCitizen = $post->is_citizen;
                                                }
                                            @endphp
                                            <span class="citizen-badge {{ $isCitizen ? 'citizen' : 'general' }}">
                                                {{ $isCitizen ? 'CITIZEN' : 'VISITOR' }}
                                            </span>
                                        </div>
                                        <span class="forum-post-time">{{ \Carbon\Carbon::parse($post->created_at)->diffForHumans() }}</span>
                                    </div>

                                    {{-- Quoted parent post if reply --}}
                                    @if(isset($post->parent) && $post->parent)
                                        <div class="forum-quote">
                                            <span class="quote-author">{{ $post->parent_author ?? 'Anonymous' }}</span>
                                            <p>{{ Str::limit(strip_tags($post->parent->content), 200) }}</p>
                                        </div>
                                    @endif

                                    <div class="forum-post-body">
                                        {!! nl2br(e($post->content)) !!}
                                    </div>

                                    @if(!$thread->locked)
                                    <div class="forum-post-actions">
                                        @auth
                                        <button class="post-action" onclick="quotePost({{ $post->id }}, '{{ addslashes($post->author_name ?? 'Anonymous') }}', {{ json_encode(Str::limit($post->content, 200)) }})">
                                            <i class="fa-solid fa-quote-left"></i> Quote
                                        </button>
                                        <button class="post-action" onclick="replyTo({{ $post->id }}, '{{ addslashes($post->author_name ?? 'Anonymous') }}')">
                                            <i class="fa-solid fa-reply"></i> Reply
                                        </button>
                                        @endauth
                                    </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state" style="margin-top:2px;">
                            <i class="fa-solid fa-comment-slash"></i>
                            <h3>No Posts Yet</h3>
                            <p>Be the first to contribute to this discussion.</p>
                        </div>
                    @endif
                </div>

                {{-- COMPOSER --}}
                @auth
                    @if(!$thread->locked)
                    <div class="forum-composer" id="composer">
                        <div class="composer-toolbar">
                            <button type="button" onclick="insertMd('**','**')" title="Bold"><i class="fa-solid fa-bold"></i></button>
                            <button type="button" onclick="insertMd('*','*')" title="Italic"><i class="fa-solid fa-italic"></i></button>
                            <button type="button" onclick="insertMd('`','`')" title="Inline code"><i class="fa-solid fa-code"></i></button>
                            <button type="button" onclick="insertMd('[','](url)')" title="Link"><i class="fa-solid fa-link"></i></button>
                            <button type="button" onclick="insertMd('> ','')" title="Quote"><i class="fa-solid fa-quote-right"></i></button>
                            <div class="composer-spacer"></div>
                            <label class="preview-toggle">
                                <input type="checkbox" id="preview-toggle"> Preview
                            </label>
                        </div>
                        <div id="reply-indicator" style="display:none;">
                            Replying to <span id="reply-to-name"></span>
                            <button type="button" onclick="cancelReply()">&times;</button>
                        </div>
                        <textarea id="post-content" placeholder="Write your response..."></textarea>
                        <div id="post-preview" style="display:none;" class="forum-post-body"></div>
                        <div class="composer-footer">
                            <span class="composer-hint"><i class="fa-solid fa-markdown" style="margin-right:4px;"></i> Markdown supported</span>
                            <button id="submit-post" class="composer-submit">
                                Submit Post <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                        <input type="hidden" id="parent-post-id" value="">
                    </div>
                    @else
                    <div class="forum-login-prompt">
                        <i class="fa-solid fa-lock" style="margin-right:8px; color:var(--mr-amber);"></i>
                        This thread has been locked. No new replies can be posted.
                    </div>
                    @endif
                @else
                <div class="forum-login-prompt">
                    <a href="/login">Sign in</a> to join the discussion
                </div>
                @endauth

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
    var threadId = {{ $thread->id }};

    // Markdown insertion helper
    function insertMd(before, after) {
        var textarea = document.getElementById('post-content');
        var start = textarea.selectionStart;
        var end = textarea.selectionEnd;
        var text = textarea.value;
        var selected = text.substring(start, end);
        var replacement = before + (selected || 'text') + after;
        textarea.value = text.substring(0, start) + replacement + text.substring(end);
        textarea.focus();
        // Position cursor after inserted text
        var cursorPos = start + before.length + (selected ? selected.length : 4);
        textarea.setSelectionRange(cursorPos, cursorPos);
    }

    // Quote a post
    function quotePost(postId, authorName, content) {
        var textarea = document.getElementById('post-content');
        var quotedLines = content.split('\n').map(function(line) { return '> ' + line; }).join('\n');
        var quoteBlock = '> **' + authorName + '** wrote:\n' + quotedLines + '\n\n';

        if (textarea.value.length > 0) {
            textarea.value += '\n' + quoteBlock;
        } else {
            textarea.value = quoteBlock;
        }

        // Set parent post id
        document.getElementById('parent-post-id').value = postId;
        document.getElementById('reply-indicator').style.display = 'flex';
        document.getElementById('reply-to-name').textContent = authorName;

        textarea.focus();
        textarea.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // Reply to a post
    function replyTo(postId, authorName) {
        document.getElementById('parent-post-id').value = postId;
        document.getElementById('reply-indicator').style.display = 'flex';
        document.getElementById('reply-to-name').textContent = authorName;

        var textarea = document.getElementById('post-content');
        textarea.focus();
        textarea.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // Cancel reply
    function cancelReply() {
        document.getElementById('parent-post-id').value = '';
        document.getElementById('reply-indicator').style.display = 'none';
        document.getElementById('reply-to-name').textContent = '';
    }

    // Simple markdown preview (bold, italic, code, links, blockquotes)
    function renderPreview(text) {
        var html = text;
        // Escape HTML first
        html = html.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        // Code blocks (triple backtick)
        html = html.replace(/```([\s\S]*?)```/g, '<pre><code>$1</code></pre>');
        // Inline code
        html = html.replace(/`([^`]+)`/g, '<code>$1</code>');
        // Bold
        html = html.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
        // Italic
        html = html.replace(/\*(.+?)\*/g, '<em>$1</em>');
        // Links
        html = html.replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" target="_blank">$1</a>');
        // Blockquotes
        html = html.replace(/^&gt; (.+)$/gm, '<blockquote>$1</blockquote>');
        // Line breaks
        html = html.replace(/\n/g, '<br>');
        return html;
    }

    $(document).ready(function() {
        // Preview toggle
        $('#preview-toggle').on('change', function() {
            if (this.checked) {
                var content = $('#post-content').val();
                $('#post-preview').html(renderPreview(content)).show();
                $('#post-content').hide();
            } else {
                $('#post-preview').hide();
                $('#post-content').show();
            }
        });

        // Submit post via AJAX
        $('#submit-post').on('click', function() {
            var content = $('#post-content').val().trim();
            var parentId = $('#parent-post-id').val();

            if (!content) {
                toastr.warning('Please write something before posting.');
                $('#post-content').focus();
                return;
            }

            var $btn = $(this);
            $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Posting...');

            $.ajax({
                url: '/forum/t/' + threadId + '/post',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    content: content,
                    parent_id: parentId || null
                },
                success: function(response) {
                    // Build new post HTML
                    var post = response.post || response;
                    var authorName = post.author_name || '{{ Auth::check() ? addslashes(Auth::user()->name ?? Auth::user()->email) : "You" }}';
                    var isCitizen = post.is_citizen || false;
                    var badgeClass = isCitizen ? 'citizen' : 'general';
                    var badgeText = isCitizen ? 'CITIZEN' : 'VISITOR';

                    var postHtml = '<div class="forum-post new-post-highlight" id="post-' + (post.id || 'new') + '">' +
                        '<div class="forum-post-accent" style="background:var(--mr-cyan);opacity:1;"></div>' +
                        '<div class="forum-post-content">' +
                            '<div class="forum-post-header">' +
                                '<div class="forum-post-author">' +
                                    '<span class="author-name">' + authorName + '</span>' +
                                    '<span class="citizen-badge ' + badgeClass + '">' + badgeText + '</span>' +
                                '</div>' +
                                '<span class="forum-post-time">just now</span>' +
                            '</div>' +
                            '<div class="forum-post-body">' + content.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/\n/g,'<br>') + '</div>' +
                        '</div>' +
                    '</div>';

                    // Remove empty state if present
                    $('#forum-posts .empty-state').remove();

                    // Append new post
                    $('#forum-posts').append(postHtml);

                    // Clear composer
                    $('#post-content').val('');
                    cancelReply();
                    if ($('#preview-toggle').is(':checked')) {
                        $('#preview-toggle').prop('checked', false).trigger('change');
                    }

                    // Scroll to new post
                    var newPost = document.getElementById('post-' + (post.id || 'new'));
                    if (newPost) {
                        newPost.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }

                    $btn.prop('disabled', false).html('Submit Post <i class="fa-solid fa-arrow-right"></i>');
                    toastr.success('Post submitted successfully.');
                },
                error: function(xhr) {
                    $btn.prop('disabled', false).html('Submit Post <i class="fa-solid fa-arrow-right"></i>');
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message);
                    } else {
                        toastr.error('Failed to submit post. Please try again.');
                    }
                }
            });
        });

        // Ctrl+Enter to submit
        $('#post-content').on('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.which === 13) {
                e.preventDefault();
                $('#submit-post').click();
            }
        });
    });
    </script>
</body>
</html>
