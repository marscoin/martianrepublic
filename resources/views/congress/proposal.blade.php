<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <title>Bill #{{ $proposal->id }} - {{ $proposal->title }} - Martian Republic Congress</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ substr($proposal->description, 0, 160) }}">
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/diff2html@3.4.48/bundles/css/diff2html.min.css">
    <script src="https://cdn.jsdelivr.net/npm/marked@3.0.7/marked.min.js"></script>
    @livewireStyles

    @php
        $createdAt = $proposal->mined ? \Carbon\Carbon::parse($proposal->mined) : null;
        $statusClass = 'active';
        $statusLabel = 'Active';
        $statusIcon = 'fa-circle';
        if ($proposal->status === 'passed') { $statusClass = 'passed'; $statusLabel = 'Passed'; $statusIcon = 'fa-check'; }
        elseif ($proposal->status === 'rejected') { $statusClass = 'rejected'; $statusLabel = 'Rejected'; $statusIcon = 'fa-xmark'; }
        elseif ($proposal->status === 'expired') { $statusClass = 'expired'; $statusLabel = 'Expired'; $statusIcon = 'fa-hourglass-end'; }
        elseif ($proposal->status === 'closed') { $statusClass = 'closed'; $statusLabel = 'Closed'; $statusIcon = 'fa-lock'; }
    @endphp

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

    .docket-page { min-height: 100vh; display: flex; flex-direction: column; }
    .docket-page .content { flex: 1; }
    .docket-page .footer { margin-top: auto; }

    /* ---- Breadcrumb ---- */
    .docket-breadcrumb {
        padding: 20px 0 0;
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        letter-spacing: 1px;
    }
    .docket-breadcrumb a { color: var(--mr-text-dim); text-decoration: none; transition: color 0.2s; }
    .docket-breadcrumb a:hover { color: var(--mr-cyan); }
    .docket-breadcrumb span { color: var(--mr-text-dim); margin: 0 8px; }

    /* ---- Proposal Header ---- */
    .docket-header {
        padding: 32px 0 28px;
        border-bottom: 1px solid var(--mr-border);
        margin-bottom: 32px;
        position: relative;
    }
    .docket-header::after {
        content: '';
        position: absolute;
        bottom: -1px; left: 0;
        width: 120px; height: 2px;
        background: var(--mr-mars);
    }
    .docket-meta-row {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }
    .docket-bill-id {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--mr-text-dim);
        background: var(--mr-dark);
        border: 1px solid var(--mr-border);
        border-radius: 4px;
        padding: 5px 12px;
    }
    .docket-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        padding: 5px 14px;
        border-radius: 4px;
    }
    .docket-status.active { background: rgba(52,211,153,0.1); color: var(--mr-green); border: 1px solid rgba(52,211,153,0.2); }
    .docket-status.passed { background: rgba(0,228,255,0.08); color: var(--mr-cyan); border: 1px solid rgba(0,228,255,0.15); }
    .docket-status.rejected { background: rgba(239,68,68,0.1); color: var(--mr-red); border: 1px solid rgba(239,68,68,0.2); }
    .docket-status.expired { background: rgba(245,158,11,0.1); color: var(--mr-amber); border: 1px solid rgba(245,158,11,0.2); }
    .docket-status.closed { background: rgba(138,137,152,0.1); color: var(--mr-text-dim); border: 1px solid rgba(138,137,152,0.2); }

    .docket-title {
        font-family: 'Open Sans', sans-serif;
        font-size: 28px;
        font-weight: 800;
        color: #fff;
        margin: 0 0 10px;
        line-height: 1.3;
    }
    .docket-sponsor {
        font-size: 14px;
        color: var(--mr-text-dim);
    }
    .docket-sponsor a { color: var(--mr-cyan); text-decoration: none; }
    .docket-sponsor a:hover { color: #fff; }
    .docket-sponsor .category {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: var(--mr-mars);
    }

    /* ---- Content Tabs ---- */
    .docket-tabs {
        display: flex;
        gap: 2px;
        margin-bottom: 28px;
        background: var(--mr-border);
        border-radius: 6px;
        overflow: hidden;
    }
    .docket-tab {
        background: var(--mr-surface);
        border: none;
        padding: 12px 20px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: var(--mr-text-dim);
        cursor: pointer;
        transition: all 0.25s ease;
        position: relative;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .docket-tab:hover { background: var(--mr-surface-raised); color: var(--mr-text); }
    .docket-tab.active { background: var(--mr-surface-raised); color: #fff; }
    .docket-tab.active::after {
        content: '';
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 2px;
        background: var(--mr-mars);
    }

    /* ---- Markdown Content ---- */
    .docket-content {
        background: var(--mr-surface);
        border: 1px solid var(--mr-border);
        border-radius: 10px;
        padding: 32px;
        min-height: 200px;
    }
    #markdown-container {
        font-size: 15px;
        line-height: 1.8;
        color: var(--mr-text);
    }
    #markdown-container h1, #markdown-container h2, #markdown-container h3 { color: #fff; margin: 24px 0 12px; }
    #markdown-container p { margin-bottom: 16px; }
    #markdown-container a { color: var(--mr-cyan); }
    #markdown-container code { background: var(--mr-dark); padding: 2px 6px; border-radius: 3px; font-family: 'JetBrains Mono', monospace; font-size: 13px; }
    #markdown-container blockquote { border-left: 3px solid var(--mr-mars); padding-left: 16px; color: var(--mr-text-dim); margin: 16px 0; }
    #markdown-container ul, #markdown-container ol { padding-left: 24px; margin-bottom: 16px; }

    @if(!$proposal->mined && $proposal->active)
    .docket-awaiting {
        display: flex;
        align-items: center;
        gap: 12px;
        background: var(--mr-surface);
        border: 1px solid rgba(245,158,11,0.2);
        border-radius: 8px;
        padding: 16px 20px;
        margin-top: 20px;
        color: var(--mr-amber);
        font-family: 'JetBrains Mono', monospace;
        font-size: 12px;
    }
    @endif

    /* ---- Timeline ---- */
    .timeline-item {
        display: flex;
        gap: 16px;
        padding: 16px 0;
        border-bottom: 1px solid var(--mr-border);
    }
    .timeline-item:last-child { border-bottom: none; }
    .timeline-icon {
        width: 36px; height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }
    .timeline-icon.notarized { background: rgba(0,228,255,0.08); color: var(--mr-cyan); }
    .timeline-icon.vote { background: rgba(52,211,153,0.08); color: var(--mr-green); }
    .timeline-body { flex: 1; }
    .timeline-body p { margin: 0; font-size: 14px; color: var(--mr-text); }
    .timeline-body .timeline-meta {
        font-size: 12px;
        color: var(--mr-text-dim);
        margin-top: 4px;
    }
    .timeline-body .timeline-meta a { color: var(--mr-cyan); text-decoration: none; font-family: 'JetBrains Mono', monospace; font-size: 11px; }

    /* ---- Comments ---- */
    .comment-item {
        display: flex;
        gap: 14px;
        padding: 20px 0;
        border-bottom: 1px solid var(--mr-border);
    }
    .comment-item:last-child { border-bottom: none; }
    .comment-avatar-img {
        width: 40px; height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--mr-border);
        flex-shrink: 0;
    }
    .comment-content { flex: 1; }
    .comment-author-name {
        font-weight: 700;
        color: #fff;
        font-size: 14px;
    }
    .comment-date {
        font-size: 12px;
        color: var(--mr-text-dim);
        margin-left: 8px;
    }
    .comment-text {
        font-size: 14px;
        color: var(--mr-text);
        line-height: 1.7;
        margin-top: 6px;
    }
    .comment-replies {
        margin-left: 54px;
        border-left: 2px solid var(--mr-border);
        padding-left: 16px;
    }

    .comment-form textarea {
        width: 100%;
        background: var(--mr-dark);
        border: 1px solid var(--mr-border);
        border-radius: 8px;
        padding: 14px;
        color: var(--mr-text);
        font-size: 14px;
        resize: vertical;
        min-height: 100px;
    }
    .comment-form textarea:focus { outline: none; border-color: var(--mr-cyan); }
    .comment-form .btn-submit-comment {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--mr-mars);
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        font-family: 'Orbitron', sans-serif;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        cursor: pointer;
        margin-top: 12px;
        transition: all 0.3s ease;
    }
    .comment-form .btn-submit-comment:hover { background: #a83520; }

    /* ---- Sidebar ---- */
    .docket-sidebar { position: sticky; top: 100px; }

    .sidebar-panel {
        background: var(--mr-surface);
        border: 1px solid var(--mr-border);
        border-radius: 10px;
        padding: 24px;
        margin-bottom: 16px;
    }
    .sidebar-panel-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 11px;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--mr-text-dim);
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--mr-border);
    }

    .stat-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid var(--mr-border);
    }
    .stat-row:last-child { border-bottom: none; }
    .stat-label {
        font-size: 12px;
        color: var(--mr-text-dim);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .stat-label i { width: 16px; text-align: center; }
    .stat-value {
        font-family: 'Orbitron', sans-serif;
        font-size: 14px;
        font-weight: 700;
        color: #fff;
    }

    /* ---- Vote Display ---- */
    .vote-display {
        margin-top: 16px;
    }
    .vote-bar-container {
        display: flex;
        height: 8px;
        border-radius: 4px;
        overflow: hidden;
        background: var(--mr-dark);
        margin: 12px 0;
    }
    .vote-bar-yay { background: var(--mr-green); transition: width 0.6s ease; }
    .vote-bar-nay { background: var(--mr-red); transition: width 0.6s ease; }

    .vote-counts {
        display: flex;
        justify-content: space-between;
        font-family: 'JetBrains Mono', monospace;
        font-size: 12px;
    }
    .vote-yay { color: var(--mr-green); }
    .vote-nay { color: var(--mr-red); }

    /* ---- Ballot CTA ---- */
    .ballot-cta {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        background: var(--mr-mars);
        color: #fff;
        padding: 16px;
        border-radius: 8px;
        font-family: 'Orbitron', sans-serif;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        text-decoration: none;
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }
    .ballot-cta:hover {
        background: transparent;
        border: 1px solid var(--mr-mars);
        color: var(--mr-mars);
        box-shadow: 0 0 30px rgba(200,65,37,0.2);
        text-decoration: none;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: var(--mr-text-dim);
        text-decoration: none;
        font-size: 13px;
        margin-top: 32px;
        transition: color 0.2s;
    }
    .back-link:hover { color: var(--mr-cyan); text-decoration: none; }

    /* ---- Responsive ---- */
    @media (max-width: 991px) {
        .docket-title { font-size: 22px; }
        .docket-content { padding: 20px; }
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .docket-content { animation: fadeIn 0.5s ease-out; }
    </style>
</head>

<body class="docket-page">
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

                {{-- BREADCRUMB --}}
                <div class="docket-breadcrumb">
                    <a href="/congress/all">Congress</a>
                    <span>/</span>
                    <a href="/congress/voting">Legislation</a>
                    <span>/</span>
                    <span style="color:var(--mr-text);">Bill #{{ $proposal->id }}</span>
                </div>

                {{-- PROPOSAL HEADER --}}
                <div class="docket-header">
                    <div class="docket-meta-row">
                        <span class="docket-bill-id">
                            <i class="fa-solid fa-landmark" style="margin-right:4px;"></i>
                            Bill #{{ $proposal->id }}
                        </span>
                        <span class="docket-status {{ $statusClass }}">
                            <i class="fa-solid {{ $statusIcon }}" @if($statusIcon === 'fa-circle') style="font-size:6px;" @endif></i>
                            {{ $statusLabel }}
                        </span>
                        @if($proposal->ipfs_hash)
                            <a href="{{ $proposal->ipfs_hash }}" target="_blank" style="font-family:'JetBrains Mono',monospace; font-size:10px; color:var(--mr-text-dim); text-decoration:none; letter-spacing:1px;">
                                IPFS: {{ strtoupper(substr(str_replace("https://ipfs.marscoin.org/ipfs/", "", $proposal->ipfs_hash), 1, 8)) }}
                                <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:8px; margin-left:2px;"></i>
                            </a>
                        @endif
                    </div>
                    <h1 class="docket-title">{{ $proposal->title }}</h1>
                    <div class="docket-sponsor">
                        Sponsored by <a target="_blank" href="/citizen/id/{{ $proposal->public_address }}">{{ $proposal->author }}</a>
                        &middot; <span class="category">{{ str_replace("poll", "Certified Poll", $proposal->category) }}</span>
                        @if($createdAt)
                            &middot; {{ $createdAt->format('M j, Y') }}
                        @endif
                    </div>
                </div>

                <div class="row">
                    {{-- MAIN CONTENT --}}
                    <div class="col-md-8 col-lg-9">

                        {{-- CONTENT TABS --}}
                        <div class="docket-tabs">
                            <button class="docket-tab active" data-panel="info">
                                <i class="fa-solid fa-file-lines"></i> Proposal
                            </button>
                            <button class="docket-tab" data-panel="timeline">
                                <i class="fa-solid fa-clock-rotate-left"></i> Timeline
                            </button>
                            <button class="docket-tab" data-panel="amendments" id="amendments-tab" style="display:none;">
                                <i class="fa-solid fa-code-compare"></i> Amendments
                            </button>
                            <button class="docket-tab" data-panel="comments">
                                <i class="fa-solid fa-comments"></i> Discussion
                            </button>
                        </div>

                        {{-- INFO PANEL --}}
                        <div class="docket-content docket-panel" id="panel-info">
                            <div id="markdown-container">Loading...</div>

                            @if(!$proposal->mined && $proposal->active)
                                <div class="docket-awaiting">
                                    <i class="fa-solid fa-spinner fa-spin"></i>
                                    Awaiting blockchain notarization...
                                </div>
                            @endif
                        </div>

                        {{-- TIMELINE PANEL --}}
                        <div class="docket-content docket-panel" id="panel-timeline" style="display:none;">
                            @forelse($activities as $activity)
                                <div class="timeline-item">
                                    <div class="timeline-icon {{ $activity->tag === 'PR' ? 'notarized' : 'vote' }}">
                                        @if($activity->tag === 'PR')
                                            <i class="fa-solid fa-link"></i>
                                        @else
                                            <i class="fa-solid fa-check-to-slot"></i>
                                        @endif
                                    </div>
                                    <div class="timeline-body">
                                        @if($activity->tag === 'PR')
                                            <p>{{ $activity->firstname }} {{ $activity->lastname }} <strong>notarized</strong> this proposal on-chain</p>
                                        @else
                                            <p>A ballot was cast</p>
                                        @endif
                                        <div class="timeline-meta">
                                            <a href="https://explore.marscoin.org/tx/{{ $activity->txid }}" target="_blank">
                                                <i class="fa-solid fa-cube"></i> {{ substr($activity->txid, 0, 16) }}...
                                            </a>
                                            &middot;
                                            @if($activity->mined)
                                                {{ \Carbon\Carbon::parse($activity->mined)->diffForHumans() }}
                                            @else
                                                Pending
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p style="color:var(--mr-text-dim); text-align:center; padding:40px 0;">No activity recorded yet.</p>
                            @endforelse
                        </div>

                        {{-- COMMENTS PANEL --}}
                        {{-- AMENDMENTS PANEL --}}
                        <div class="docket-content docket-panel" id="panel-amendments" style="display:none;">
                            <div id="diff-loading" style="text-align:center; padding:40px; color:var(--mr-text-dim);">
                                <i class="fa-solid fa-spinner fa-spin"></i> Loading amendment history...
                            </div>
                            <div id="diff-history" style="display:none; margin-bottom:24px;"></div>
                            <div id="diff-container"></div>
                            <div id="diff-empty" style="display:none; text-align:center; padding:40px; color:var(--mr-text-dim);">
                                <i class="fa-solid fa-check-circle" style="font-size:24px; margin-bottom:8px; display:block; color:var(--mr-green);"></i>
                                No amendments. This proposal has not been modified since submission.
                            </div>
                        </div>

                        {{-- COMMENTS PANEL --}}
                        <div class="docket-content docket-panel" id="panel-comments" style="display:none;">
                            @foreach($posts as $post)
                                @if(is_null($post->post_id))
                                    <div class="comment-item">
                                        <img src="{{ $post->citizen->avatar_link }}"
                                             onerror="this.onerror=null; this.src='https://martianrepublic.org/assets/citizen/generic_profile.jpg'"
                                             class="comment-avatar-img" alt="">
                                        <div class="comment-content">
                                            <span class="comment-author-name">{{ $post->authorName }}</span>
                                            <span class="comment-date">{{ $post->created_at->format('M j, Y \a\t g:i a') }}</span>
                                            <div class="comment-text">{{ $post->content }}</div>
                                        </div>
                                    </div>
                                    @if($post->replies->isNotEmpty())
                                        <div class="comment-replies">
                                            @foreach($post->replies as $reply)
                                                <div class="comment-item">
                                                    <img src="{{ $reply->citizen->avatar_link ?? 'https://martianrepublic.org/assets/citizen/generic_profile.jpg' }}"
                                                         onerror="this.onerror=null; this.src='https://martianrepublic.org/assets/citizen/generic_profile.jpg'"
                                                         class="comment-avatar-img" alt="" style="width:32px;height:32px;">
                                                    <div class="comment-content">
                                                        <span class="comment-author-name">{{ $reply->authorName ?? 'Citizen' }}</span>
                                                        <span class="comment-date">{{ $reply->created_at->format('M j, Y \a\t g:i a') }}</span>
                                                        <div class="comment-text">{{ $reply->content }}</div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                @endif
                            @endforeach

                            @if($proposal->active)
                                <div class="comment-form" style="margin-top:24px; padding-top:24px; border-top:1px solid var(--mr-border);">
                                    <textarea id="comment-text" placeholder="Share your thoughts on this proposal..."></textarea>
                                    <button class="btn-submit-comment" type="button">
                                        <i class="fa-solid fa-paper-plane"></i> Post Comment
                                    </button>
                                </div>
                            @else
                                <p style="color:var(--mr-text-dim); text-align:center; padding:20px 0; font-style:italic;">
                                    Public comment period has ended.
                                </p>
                            @endif
                        </div>

                        <a href="/congress/voting" class="back-link">
                            <i class="fa-solid fa-arrow-left"></i> Back to Legislation
                        </a>
                    </div>

                    {{-- SIDEBAR --}}
                    <div class="col-md-4 col-lg-3">
                        <div class="docket-sidebar">

                            {{-- ========== SCREENING PHASE ========== --}}
                            @if(($lifecyclePhase ?? '') === 'screening')
                                <div class="sidebar-panel" style="border:1px solid rgba(245,158,11,0.3);">
                                    <div class="sidebar-panel-title" style="color:var(--mr-amber);">
                                        <i class="fa-solid fa-hourglass-half" style="margin-right:4px;"></i> Screening Period
                                    </div>
                                    <p style="font-size:13px; color:var(--mr-text-dim); margin-bottom:16px; line-height:1.6;">
                                        This proposal is under community review. Voting opens after the screening period ends.
                                    </p>
                                    @if($proposal->screening_ends_at)
                                        <div style="text-align:center; padding:16px; background:var(--mr-dark); border-radius:8px; margin-bottom:16px;">
                                            <div style="font-family:'JetBrains Mono',monospace; font-size:10px; letter-spacing:2px; text-transform:uppercase; color:var(--mr-text-dim); margin-bottom:6px;">Voting Opens In</div>
                                            <div style="font-family:'Orbitron',sans-serif; font-size:20px; font-weight:700; color:var(--mr-amber);" id="screening-countdown">
                                                {{ now()->diff(\Carbon\Carbon::parse($proposal->screening_ends_at))->format('%dd %hh %im') }}
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Challenge Tier (any citizen except proposer) --}}
                                    @if($isCitizen && !($isProposer ?? false))
                                        <a href="#" id="challenge-tier-btn" style="display:flex; align-items:center; justify-content:center; gap:8px; padding:12px; border:1px solid var(--mr-border); border-radius:8px; color:var(--mr-text-dim); font-family:'JetBrains Mono',monospace; font-size:11px; letter-spacing:1px; text-transform:uppercase; text-decoration:none; transition:all 0.3s ease; margin-bottom:8px;">
                                            <i class="fa-solid fa-scale-balanced"></i> Challenge Tier Classification
                                        </a>
                                    @endif

                                    {{-- Proposer Actions --}}
                                    @if($isProposer ?? false)
                                        <div style="display:flex; gap:8px;">
                                            @if(!$proposal->amended_at)
                                                <a href="#" id="amend-btn" style="flex:1; display:flex; align-items:center; justify-content:center; gap:6px; padding:10px; background:rgba(0,228,255,0.08); border:1px solid rgba(0,228,255,0.2); border-radius:6px; color:var(--mr-cyan); font-family:'JetBrains Mono',monospace; font-size:10px; letter-spacing:1px; text-transform:uppercase; text-decoration:none;">
                                                    <i class="fa-solid fa-pen"></i> Amend
                                                </a>
                                            @else
                                                <span style="flex:1; display:flex; align-items:center; justify-content:center; gap:6px; padding:10px; background:var(--mr-dark); border:1px solid var(--mr-border); border-radius:6px; color:var(--mr-text-dim); font-family:'JetBrains Mono',monospace; font-size:10px; letter-spacing:1px; text-transform:uppercase;">
                                                    <i class="fa-solid fa-check"></i> Amended
                                                </span>
                                            @endif
                                            <a href="#" id="withdraw-btn" style="flex:1; display:flex; align-items:center; justify-content:center; gap:6px; padding:10px; background:rgba(239,68,68,0.08); border:1px solid rgba(239,68,68,0.2); border-radius:6px; color:var(--mr-red); font-family:'JetBrains Mono',monospace; font-size:10px; letter-spacing:1px; text-transform:uppercase; text-decoration:none;">
                                                <i class="fa-solid fa-xmark"></i> Withdraw
                                            </a>
                                        </div>
                                    @endif
                                </div>

                            {{-- ========== VOTING PHASE ========== --}}
                            @elseif(($lifecyclePhase ?? '') === 'voting' || ($proposal->mined && $proposal->active && !$proposal->screening_ends_at))

                                {{-- Ballot CTA --}}
                                @if($isCitizen && $proposal->txid)
                                    <a href="/congress/ballot/{{ $proposal->id }}" class="ballot-cta">
                                        <i class="fa-solid fa-check-to-slot"></i> Request Ballot
                                    </a>
                                @elseif(!$isCitizen)
                                    <div class="sidebar-panel" style="text-align:center;">
                                        <p style="color:var(--mr-text-dim); font-size:13px; margin:0;">
                                            <a href="/citizen/all" style="color:var(--mr-cyan); text-decoration:none;">Become a citizen</a> to cast your vote.
                                        </p>
                                    </div>
                                @endif

                                {{-- Vote Breakdown --}}
                                <div class="sidebar-panel">
                                    <div class="sidebar-panel-title">Vote Breakdown</div>
                                    <div class="vote-display" id="vote-display">
                                        <div class="vote-bar-container">
                                            <div class="vote-bar-yay" id="yay-bar" style="width:0%"></div>
                                            <div class="vote-bar-nay" id="nay-bar" style="width:0%"></div>
                                        </div>
                                        <div class="vote-counts">
                                            <span class="vote-yay"><i class="fa-solid fa-thumbs-up"></i> <span id="yay-count">{{ $proposal->yays ?? 0 }}</span></span>
                                            <span id="yay-pct" style="color:var(--mr-green); font-family:'Orbitron',sans-serif; font-weight:700;"></span>
                                            <span id="nay-pct" style="color:var(--mr-red); font-family:'Orbitron',sans-serif; font-weight:700;"></span>
                                            <span class="vote-nay"><span id="nay-count">{{ $proposal->nays ?? 0 }}</span> <i class="fa-solid fa-thumbs-down"></i></span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Voting Countdown --}}
                                @php
                                    $votingEnd = $proposal->voting_ends_at
                                        ? $proposal->voting_ends_at
                                        : \Carbon\Carbon::parse($proposal->mined)->addDays($proposal->duration)->format('Y-m-d H:i:s');
                                @endphp
                                <div class="sidebar-panel">
                                    <div class="sidebar-panel-title">Time Remaining</div>
                                    <x-countdown-timer :proposal-id="$proposal->id" :end-time="$votingEnd" :start-time="$proposal->mined" />
                                </div>

                            {{-- ========== TIMELOCK PHASE ========== --}}
                            @elseif(($lifecyclePhase ?? '') === 'timelock')
                                <div class="sidebar-panel" style="border:1px solid rgba(0,228,255,0.2);">
                                    <div class="sidebar-panel-title" style="color:var(--mr-cyan);">
                                        <i class="fa-solid fa-lock-open" style="margin-right:4px;"></i> Timelock Period
                                    </div>
                                    <p style="font-size:13px; color:var(--mr-text-dim); margin-bottom:12px; line-height:1.6;">
                                        This proposal passed and is in its timelock period. It will be enacted after the waiting period.
                                    </p>
                                    @if($proposal->timelock_ends_at)
                                        <div style="text-align:center; padding:16px; background:var(--mr-dark); border-radius:8px;">
                                            <div style="font-family:'JetBrains Mono',monospace; font-size:10px; letter-spacing:2px; text-transform:uppercase; color:var(--mr-text-dim); margin-bottom:6px;">Enacted In</div>
                                            <div style="font-family:'Orbitron',sans-serif; font-size:20px; font-weight:700; color:var(--mr-cyan);">
                                                {{ now()->diff(\Carbon\Carbon::parse($proposal->timelock_ends_at))->format('%dd %hh') }}
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- Vote Results --}}
                                <div class="sidebar-panel">
                                    <div class="sidebar-panel-title">Vote Results</div>
                                    <div class="vote-display" id="vote-display">
                                        <div class="vote-bar-container">
                                            <div class="vote-bar-yay" id="yay-bar" style="width:0%"></div>
                                            <div class="vote-bar-nay" id="nay-bar" style="width:0%"></div>
                                        </div>
                                        <div class="vote-counts">
                                            <span class="vote-yay"><i class="fa-solid fa-thumbs-up"></i> <span id="yay-count">{{ $proposal->yays ?? 0 }}</span></span>
                                            <span id="yay-pct" style="color:var(--mr-green); font-family:'Orbitron',sans-serif; font-weight:700;"></span>
                                            <span id="nay-pct" style="color:var(--mr-red); font-family:'Orbitron',sans-serif; font-weight:700;"></span>
                                            <span class="vote-nay"><span id="nay-count">{{ $proposal->nays ?? 0 }}</span> <i class="fa-solid fa-thumbs-down"></i></span>
                                        </div>
                                    </div>
                                </div>

                            {{-- ========== PASSED / ACTIVE / TERMINAL STATES ========== --}}
                            @else
                                {{-- Vote Breakdown for concluded proposals --}}
                                @if(in_array($proposal->status, ['passed', 'rejected', 'active']))
                                    <div class="sidebar-panel">
                                        <div class="sidebar-panel-title">Vote Breakdown</div>
                                        <div class="vote-display" id="vote-display">
                                            <div class="vote-bar-container">
                                                <div class="vote-bar-yay" id="yay-bar" style="width:0%"></div>
                                                <div class="vote-bar-nay" id="nay-bar" style="width:0%"></div>
                                            </div>
                                            <div class="vote-counts">
                                                <span class="vote-yay"><i class="fa-solid fa-thumbs-up"></i> <span id="yay-count">{{ $proposal->yays ?? 0 }}</span></span>
                                                <span id="yay-pct" style="color:var(--mr-green); font-family:'Orbitron',sans-serif; font-weight:700;"></span>
                                                <span id="nay-pct" style="color:var(--mr-red); font-family:'Orbitron',sans-serif; font-weight:700;"></span>
                                                <span class="vote-nay"><span id="nay-count">{{ $proposal->nays ?? 0 }}</span> <i class="fa-solid fa-thumbs-down"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif

                            {{-- COUNTDOWN for legacy proposals without lifecycle timestamps --}}
                            @if($proposal->mined && $proposal->active && !$proposal->voting_ends_at && ($lifecyclePhase ?? '') !== 'screening')
                                @php
                                    $endTime = \Carbon\Carbon::parse($proposal->mined)->addDays($proposal->duration)->format('Y-m-d H:i:s');
                                @endphp
                                <div class="sidebar-panel">
                                    <div class="sidebar-panel-title">Time Remaining</div>
                                    <x-countdown-timer :proposal-id="$proposal->id" :end-time="$endTime" :start-time="$proposal->mined" />
                                </div>
                            @endif

                            {{-- PARAMETERS --}}
                            <div class="sidebar-panel">
                                <div class="sidebar-panel-title">Parameters</div>
                                <div class="stat-row">
                                    <span class="stat-label"><i class="fa-solid fa-gavel" style="color:var(--mr-cyan);"></i> Threshold</span>
                                    <span class="stat-value">{{ $proposal->threshold }}%</span>
                                </div>
                                <div class="stat-row">
                                    <span class="stat-label"><i class="fa-solid fa-users" style="color:var(--mr-cyan);"></i> Participation</span>
                                    <span class="stat-value">{{ $proposal->participation }}%</span>
                                </div>
                                <div class="stat-row">
                                    <span class="stat-label"><i class="fa-solid fa-calendar" style="color:var(--mr-cyan);"></i> Duration</span>
                                    <span class="stat-value">{{ $proposal->duration }}d</span>
                                </div>
                                <div class="stat-row">
                                    <span class="stat-label"><i class="fa-solid fa-hourglass" style="color:var(--mr-cyan);"></i> Expiration</span>
                                    <span class="stat-value">{{ $proposal->expiration > 0 ? $proposal->expiration . 'y' : 'Never' }}</span>
                                </div>
                                @if($proposal->total_votes ?? false)
                                    <div class="stat-row">
                                        <span class="stat-label"><i class="fa-solid fa-check-double" style="color:var(--mr-green);"></i> Total Votes</span>
                                        <span class="stat-value">{{ $proposal->total_votes }}</span>
                                    </div>
                                @endif
                                @if($proposal->yay_percent ?? false)
                                    <div class="stat-row">
                                        <span class="stat-label"><i class="fa-solid fa-chart-pie" style="color:var(--mr-green);"></i> Approval</span>
                                        <span class="stat-value" style="color:var(--mr-green);">{{ round($proposal->yay_percent, 1) }}%</span>
                                    </div>
                                @endif
                                @if($proposal->status === 'closed' && $proposal->closed_reason)
                                    <div class="stat-row">
                                        <span class="stat-label"><i class="fa-solid fa-lock" style="color:var(--mr-text-dim);"></i> Closed Reason</span>
                                        <span class="stat-value" style="font-size:11px; color:var(--mr-amber);">{{ $proposal->closed_reason }}</span>
                                    </div>
                                @endif
                            </div>

                            {{-- ON-CHAIN --}}
                            @if($proposal->txid)
                                <div class="sidebar-panel">
                                    <div class="sidebar-panel-title">On-Chain Record</div>
                                    <div style="font-family:'JetBrains Mono',monospace; font-size:11px; color:var(--mr-text-dim); word-break:break-all;">
                                        <a href="https://explore.marscoin.org/tx/{{ $proposal->txid }}" target="_blank" style="color:var(--mr-cyan); text-decoration:none;">
                                            {{ substr($proposal->txid, 0, 24) }}...
                                            <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:9px;"></i>
                                        </a>
                                    </div>
                                </div>
                            @endif

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
    <script src="https://cdn.jsdelivr.net/npm/diff2html@3.4.48/bundles/js/diff2html-ui.min.js"></script>
    @livewireScripts

    <script>
    // Tab switching
    document.querySelectorAll('.docket-tab').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.docket-tab').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const panel = this.getAttribute('data-panel');
            document.querySelectorAll('.docket-panel').forEach(p => p.style.display = 'none');
            const target = document.getElementById('panel-' + panel);
            if (target) target.style.display = 'block';
        });
    });

    // Render markdown
    $(document).ready(function() {
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        try {
            var htmlContent = marked(JSON.parse({!! json_encode(json_encode($proposal->description)) !!}));
            document.getElementById('markdown-container').innerHTML = htmlContent;
        } catch(e) {
            document.getElementById('markdown-container').textContent = `{{ $proposal->description }}`;
        }

        // Load vote breakdown
        $.ajax({
            url: "/congress/vote/breakdown",
            type: "POST",
            data: { "proposalId": {{ $proposal->id }} },
            dataType: 'json',
            success: function(data) {
                $('#yay-bar').css('width', data.yayPercent + '%');
                $('#nay-bar').css('width', data.nayPercent + '%');
                $('#yay-pct').text(data.yayPercent + '%');
                $('#nay-pct').text(data.nayPercent + '%');
                if (data.totalVotes > 0) {
                    $('#yay-count').text(data.totalVotes - Math.round(data.totalVotes * data.nayPercent / 100));
                    $('#nay-count').text(Math.round(data.totalVotes * data.nayPercent / 100));
                }
            }
        });
    });

    // Load amendment diff via LegislationRepo
    $.ajax({
        url: "/congress/proposal/diff",
        type: "POST",
        data: { proposalId: {{ $proposal->id }} },
        dataType: 'json',
        success: function(data) {
            if (data.success && data.isAmended) {
                // Show the Amendments tab
                document.getElementById('amendments-tab').style.display = '';

                // Render history
                if (data.history && data.history.length > 0) {
                    var historyHtml = '<div style="margin-bottom:20px;">';
                    historyHtml += '<div style="font-family:\'Orbitron\',sans-serif; font-size:11px; letter-spacing:2px; text-transform:uppercase; color:var(--mr-text-dim); margin-bottom:12px;">Git History</div>';
                    data.history.forEach(function(entry, i) {
                        var icon = i === 0 ? 'fa-file-circle-plus' : 'fa-code-commit';
                        var color = i === 0 ? 'var(--mr-green)' : 'var(--mr-cyan)';
                        historyHtml += '<div style="display:flex; gap:12px; align-items:flex-start; padding:10px 0; border-bottom:1px solid var(--mr-border);">';
                        historyHtml += '<i class="fa-solid ' + icon + '" style="color:' + color + '; margin-top:3px;"></i>';
                        historyHtml += '<div style="flex:1;">';
                        historyHtml += '<div style="font-size:14px; color:var(--mr-text);">' + entry.message + '</div>';
                        historyHtml += '<div style="font-family:\'JetBrains Mono\',monospace; font-size:11px; color:var(--mr-text-dim); margin-top:2px;">';
                        historyHtml += '<span style="color:var(--mr-cyan);">' + entry.hash + '</span> &middot; ' + entry.author;
                        historyHtml += '</div></div></div>';
                    });
                    historyHtml += '</div>';
                    $('#diff-history').html(historyHtml).show();
                }

                // Render diff with diff2html
                if (data.diff) {
                    var diff2htmlUi = new Diff2HtmlUI(
                        document.getElementById('diff-container'),
                        data.diff,
                        {
                            drawFileList: false,
                            matching: 'lines',
                            outputFormat: 'side-by-side',
                            renderNothingWhenEmpty: false,
                            colorScheme: 'dark',
                        }
                    );
                    diff2htmlUi.draw();

                    // Dark theme overrides for diff2html
                    var style = document.createElement('style');
                    style.textContent = `
                        .d2h-wrapper { font-family: 'JetBrains Mono', monospace !important; font-size: 12px !important; }
                        .d2h-file-header { background: var(--mr-dark) !important; color: var(--mr-text) !important; border-color: var(--mr-border) !important; }
                        .d2h-file-wrapper { border-color: var(--mr-border) !important; border-radius: 8px !important; overflow: hidden !important; }
                        .d2h-code-linenumber { background: var(--mr-dark) !important; color: var(--mr-text-dim) !important; border-color: var(--mr-border) !important; }
                        .d2h-code-line { background: var(--mr-surface) !important; color: var(--mr-text) !important; }
                        .d2h-del { background: rgba(239,68,68,0.1) !important; }
                        .d2h-ins { background: rgba(52,211,153,0.1) !important; }
                        .d2h-del .d2h-code-line-ctn { background: rgba(239,68,68,0.15) !important; }
                        .d2h-ins .d2h-code-line-ctn { background: rgba(52,211,153,0.15) !important; }
                        .d2h-code-line-ctn { white-space: pre-wrap !important; }
                        .d2h-diff-table { font-size: 12px !important; }
                        .d2h-tag { display: none !important; }
                        .d2h-info { background: var(--mr-dark) !important; color: var(--mr-text-dim) !important; border-color: var(--mr-border) !important; }
                    `;
                    document.head.appendChild(style);
                }

                $('#diff-loading').hide();
            } else {
                // No amendments
                $('#diff-loading').hide();
                $('#diff-empty').show();
            }
        },
        error: function() {
            $('#diff-loading').hide();
            $('#diff-empty').show();
        }
    });
    </script>
</body>
</html>
