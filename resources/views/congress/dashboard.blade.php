<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <title>Congress - Martian Republic</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Martian Republic Congress - Direct democracy on Mars">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/wallet/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-admin.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-flat.css">
    <link rel="shortcut icon" href="/assets/favicon.ico">
    @livewireStyles

    <style>
    /* ============================================
       THE CHAMBER — Congress Dashboard
       Martian Republic Mission Control Aesthetic
       ============================================ */

    .chamber-page {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }
    .chamber-page .content {
        flex: 1;
    }
    .chamber-page .footer {
        margin-top: auto;
    }

    /* ---- Hero Section ---- */
    .congress-hero {
        position: relative;
        padding: 60px 0 40px;
        overflow: hidden;
    }
    .congress-hero::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background:
            radial-gradient(ellipse at 20% 50%, rgba(200,65,37,0.08) 0%, transparent 60%),
            radial-gradient(ellipse at 80% 20%, rgba(0,228,255,0.04) 0%, transparent 50%);
        pointer-events: none;
    }
    .congress-hero::after {
        content: '';
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--mr-border-bright, rgba(255,255,255,0.12)) 20%, var(--mr-mars, #c84125) 50%, var(--mr-border-bright, rgba(255,255,255,0.12)) 80%, transparent);
    }

    /* ---- Title Block ---- */
    .congress-title {
        font-family: 'Orbitron', sans-serif;
        font-weight: 800;
        font-size: 42px;
        letter-spacing: 3px;
        color: #fff;
        margin: 0 0 8px;
        text-transform: uppercase;
        animation: fadeSlideDown 0.8s ease-out;
    }
    .congress-subtitle {
        font-family: 'JetBrains Mono', monospace;
        font-size: 12px;
        letter-spacing: 4px;
        text-transform: uppercase;
        color: var(--mr-mars, #c84125);
        margin-bottom: 28px;
        animation: fadeSlideDown 0.8s ease-out 0.15s both;
    }
    .congress-desc {
        font-size: 16px;
        line-height: 1.8;
        color: var(--mr-text-dim, #8a8998);
        max-width: 640px;
        animation: fadeSlideDown 0.8s ease-out 0.3s both;
    }

    /* ---- Status Bar (mission control feel) ---- */
    .status-bar {
        display: flex;
        gap: 1px;
        margin: 40px 0 48px;
        background: var(--mr-border, rgba(255,255,255,0.06));
        border-radius: 8px;
        overflow: hidden;
        animation: fadeSlideUp 0.7s ease-out 0.5s both;
    }
    .status-cell {
        flex: 1;
        background: var(--mr-surface, #12121e);
        padding: 20px 24px;
        position: relative;
    }
    .status-cell:first-child { border-radius: 8px 0 0 8px; }
    .status-cell:last-child { border-radius: 0 8px 8px 0; }
    .status-label {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--mr-text-dim, #8a8998);
        margin-bottom: 6px;
    }
    .status-value {
        font-family: 'Orbitron', sans-serif;
        font-size: 22px;
        font-weight: 700;
        color: #fff;
    }
    .status-value .unit {
        font-size: 11px;
        font-weight: 400;
        color: var(--mr-text-dim, #8a8998);
        margin-left: 4px;
    }
    .status-indicator {
        display: inline-block;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--mr-green, #34d399);
        margin-right: 6px;
        animation: pulse 2s infinite;
        vertical-align: middle;
    }

    /* ---- Pillar Cards ---- */
    .pillars {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2px;
        margin-bottom: 48px;
        background: var(--mr-border, rgba(255,255,255,0.06));
        border-radius: 10px;
        overflow: hidden;
    }
    .pillar {
        background: var(--mr-surface, #12121e);
        padding: 40px 32px;
        position: relative;
        transition: background 0.3s ease, transform 0.3s ease;
        cursor: default;
    }
    .pillar:hover {
        background: var(--mr-surface-raised, #1a1a2a);
    }
    .pillar:first-child { border-radius: 10px 0 0 10px; }
    .pillar:last-child { border-radius: 0 10px 10px 0; }
    .pillar::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 2px;
        background: transparent;
        transition: background 0.3s ease;
    }
    .pillar:nth-child(1):hover::before { background: var(--mr-mars, #c84125); }
    .pillar:nth-child(2):hover::before { background: var(--mr-cyan, #00e4ff); }
    .pillar:nth-child(3):hover::before { background: var(--mr-green, #34d399); }

    .pillar-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        font-size: 20px;
    }
    .pillar:nth-child(1) .pillar-icon {
        background: rgba(200,65,37,0.12);
        color: var(--mr-mars, #c84125);
    }
    .pillar:nth-child(2) .pillar-icon {
        background: rgba(0,228,255,0.08);
        color: var(--mr-cyan, #00e4ff);
    }
    .pillar:nth-child(3) .pillar-icon {
        background: rgba(52,211,153,0.1);
        color: var(--mr-green, #34d399);
    }
    .pillar-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 14px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #fff;
        margin-bottom: 10px;
    }
    .pillar-desc {
        font-size: 14px;
        line-height: 1.7;
        color: var(--mr-text-dim, #8a8998);
    }
    .pillar-stat {
        margin-top: 20px;
        padding-top: 16px;
        border-top: 1px solid var(--mr-border, rgba(255,255,255,0.06));
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        color: var(--mr-text-dim, #8a8998);
    }
    .pillar-stat strong {
        color: #fff;
        font-size: 16px;
        display: block;
        margin-top: 4px;
    }

    /* Staggered reveal */
    .pillar:nth-child(1) { animation: fadeSlideUp 0.6s ease-out 0.6s both; }
    .pillar:nth-child(2) { animation: fadeSlideUp 0.6s ease-out 0.75s both; }
    .pillar:nth-child(3) { animation: fadeSlideUp 0.6s ease-out 0.9s both; }

    /* ---- CTA Section ---- */
    .congress-cta {
        animation: fadeSlideUp 0.6s ease-out 1.1s both;
        margin-bottom: 60px;
    }
    .cta-enter {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        background: var(--mr-mars, #c84125);
        color: #fff;
        padding: 16px 36px;
        border-radius: 8px;
        font-family: 'Orbitron', sans-serif;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        text-decoration: none;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }
    .cta-enter:hover {
        background: transparent;
        border-color: var(--mr-mars, #c84125);
        color: var(--mr-mars, #c84125);
        box-shadow: 0 0 30px rgba(200,65,37,0.2);
        text-decoration: none;
    }
    .cta-enter i {
        font-size: 16px;
    }
    .cta-guest {
        background: var(--mr-surface, #12121e);
        border: 1px solid var(--mr-border, rgba(255,255,255,0.06));
        border-radius: 8px;
        padding: 20px 28px;
        display: inline-flex;
        align-items: center;
        gap: 12px;
    }
    .cta-guest p {
        margin: 0;
        font-size: 14px;
        color: var(--mr-text-dim, #8a8998);
    }
    .cta-guest a {
        color: var(--mr-cyan, #00e4ff);
        text-decoration: none;
        font-weight: 600;
    }
    .cta-guest a:hover { color: #fff; }

    /* ---- Scan Line Effect ---- */
    .scanline {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        pointer-events: none;
        z-index: 9999;
        background: repeating-linear-gradient(
            0deg,
            transparent,
            transparent 2px,
            rgba(0,0,0,0.015) 2px,
            rgba(0,0,0,0.015) 4px
        );
    }

    /* ---- Responsive ---- */
    @media (max-width: 768px) {
        .congress-title { font-size: 26px; letter-spacing: 1px; }
        .status-bar { flex-direction: column; }
        .status-cell:first-child { border-radius: 8px 8px 0 0; }
        .status-cell:last-child { border-radius: 0 0 8px 8px; }
        .pillars { grid-template-columns: 1fr; }
        .pillar:first-child { border-radius: 10px 10px 0 0; }
        .pillar:last-child { border-radius: 0 0 10px 10px; }
        .pillar { padding: 28px 24px; }
    }

    /* ---- Animations ---- */
    @keyframes fadeSlideDown {
        from { opacity: 0; transform: translateY(-16px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
    }
    </style>
</head>

<body class="chamber-page">
    <div class="scanline"></div>
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

                {{-- HERO --}}
                <div class="congress-hero">
                    <div class="congress-subtitle">
                        <span class="status-indicator"></span> Legislative Chamber &mdash; Sol 1,247
                    </div>
                    <h1 class="congress-title">Martian Congress</h1>
                    <p class="congress-desc">
                        The legislative body of the Martian Republic. Citizens propose and vote on measures that shape the future of Mars governance. Every proposal, every vote &mdash; permanently recorded on the Marscoin blockchain.
                    </p>
                </div>

                {{-- STATUS BAR --}}
                <div class="status-bar">
                    <div class="status-cell">
                        <div class="status-label">Network Status</div>
                        <div class="status-value">
                            <span class="status-indicator"></span>Active
                        </div>
                    </div>
                    <div class="status-cell">
                        <div class="status-label">Block Height</div>
                        <div class="status-value" id="block-height">{{ $blockHeight ?? '---' }}<span class="unit">blocks</span></div>
                    </div>
                    <div class="status-cell">
                        <div class="status-label">Registered Citizens</div>
                        <div class="status-value" id="citizen-count">{{ ($citizenCount ?? 0) + ($publicCount ?? 0) }}<span class="unit">citizens</span></div>
                    </div>
                    <div class="status-cell">
                        <div class="status-label">Governance Model</div>
                        <div class="status-value">Direct<span class="unit">democracy</span></div>
                    </div>
                </div>

                {{-- THREE PILLARS --}}
                <div class="pillars">
                    <div class="pillar">
                        <div class="pillar-icon"><i class="fa fa-file-text-o"></i></div>
                        <div class="pillar-title">Proposals</div>
                        <div class="pillar-desc">
                            Any citizen can draft legislation. Proposals are published to IPFS and anchored on-chain for permanent, tamper-proof transparency.
                        </div>
                        <div class="pillar-stat">
                            Total proposals submitted
                            <strong id="proposal-count">{{ $proposalCount ?? '--' }}</strong>
                        </div>
                    </div>
                    <div class="pillar">
                        <div class="pillar-icon"><i class="fa fa-check-square-o"></i></div>
                        <div class="pillar-title">Voting</div>
                        <div class="pillar-desc">
                            One citizen, one vote. Each ballot is cryptographically signed with the voter's private key and broadcast to the network.
                        </div>
                        <div class="pillar-stat">
                            Verification method
                            <strong>BIP-44 Signature</strong>
                        </div>
                    </div>
                    <div class="pillar">
                        <div class="pillar-icon"><i class="fa fa-link"></i></div>
                        <div class="pillar-title">On-Chain</div>
                        <div class="pillar-desc">
                            Every vote is immutably recorded on the Marscoin blockchain. No central authority can alter, censor, or reverse the outcome.
                        </div>
                        <div class="pillar-stat">
                            Blockchain
                            <strong>Marscoin (MARS)</strong>
                        </div>
                    </div>
                </div>

                {{-- CTA --}}
                <div class="congress-cta">
                    @auth
                        <a href="/congress/voting" class="cta-enter">
                            <i class="fa fa-gavel"></i>
                            Enter Congress Hall
                        </a>
                    @else
                        <div class="cta-guest">
                            <i class="fa fa-lock" style="color: var(--mr-text-dim); font-size: 18px;"></i>
                            <p>
                                <a href="/login">Sign in</a> to participate in proposals and voting, or
                                <a href="/signup">become a citizen</a> to join the Republic.
                            </p>
                        </div>
                    @endauth
                </div>

            </div>
        </div>
    </div>

    <footer class="footer" style="border-top: 1px solid var(--mr-border, rgba(255,255,255,0.06)); padding: 20px 0; background: var(--mr-void, #06060c);">
        @include('footer')
    </footer>

    <script src="/assets/wallet/js/libs/jquery-1.10.2.min.js"></script>
    <script src="/assets/wallet/js/libs/bootstrap.min.js"></script>
    @livewireScripts

    <script>
    // Populate live stats
    (function() {
        // Block height from pebas
        fetch('/api/price', { method: 'POST', headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content} })
            .then(r => r.json()).then(d => {}).catch(() => {});

        // Fetch block count
        fetch('https://pebas.marscoin.org/api/mars/balance?address=MDCURC61G7A5jNRjnDq42XB1RvU51y4Ftx')
            .catch(() => {});

        // Use Livewire or inline data for now
        @if(isset($proposalCount))
            document.getElementById('proposal-count').textContent = '{{ $proposalCount }}';
        @endif
    })();
    </script>
</body>
</html>
