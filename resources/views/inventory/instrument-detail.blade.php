<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <title>{{ $instrument->device_type_name }} - Infrastructure Registry - Martian Republic</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Instrument detail and chain of trust - {{ $instrument->device_type_name }}">
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
       INSTRUMENT DETAIL — Chain of Trust View
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
        --mr-purple: #8b5cf6;
        --mr-text: #e4e4e7;
        --mr-text-dim: #8a8998;
        --mr-border: rgba(255,255,255,0.06);
        --mr-border-bright: rgba(255,255,255,0.12);
    }

    .detail-page {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }
    .detail-page .content { flex: 1; }
    .detail-page .footer { margin-top: auto; }

    /* ---- Breadcrumbs ---- */
    .detail-breadcrumbs {
        padding: 20px 0 0;
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        letter-spacing: 1px;
        color: var(--mr-text-dim);
    }
    .detail-breadcrumbs a {
        color: var(--mr-text-dim);
        text-decoration: none;
        transition: color 0.2s;
    }
    .detail-breadcrumbs a:hover { color: var(--mr-cyan); }
    .detail-breadcrumbs .sep { margin: 0 8px; opacity: 0.4; }
    .detail-breadcrumbs .current { color: var(--mr-text); }

    /* ---- Chain of Trust Visualization ---- */
    .chain-of-trust {
        margin: 32px 0;
        padding: 32px;
        background: var(--mr-surface);
        border: 1px solid var(--mr-border);
        border-radius: 10px;
        overflow-x: auto;
    }
    .chain-of-trust-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 11px;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--mr-text-dim);
        margin-bottom: 24px;
    }
    .chain-flow {
        display: flex;
        align-items: stretch;
        gap: 0;
        min-width: 600px;
    }
    .chain-node {
        flex: 1;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        border: 1px solid;
        position: relative;
        text-decoration: none;
        display: block;
        transition: all 0.3s ease;
    }
    .chain-node:hover {
        transform: translateY(-2px);
        text-decoration: none;
    }
    .chain-node-proposal {
        background: rgba(52,211,153,0.05);
        border-color: rgba(52,211,153,0.2);
    }
    .chain-node-proposal:hover { border-color: var(--mr-green); box-shadow: 0 0 20px rgba(52,211,153,0.1); }
    .chain-node-committee {
        background: rgba(0,228,255,0.05);
        border-color: rgba(0,228,255,0.2);
    }
    .chain-node-committee:hover { border-color: var(--mr-cyan); box-shadow: 0 0 20px rgba(0,228,255,0.1); }
    .chain-node-deputy {
        background: rgba(245,158,11,0.05);
        border-color: rgba(245,158,11,0.2);
    }
    .chain-node-deputy:hover { border-color: var(--mr-amber); box-shadow: 0 0 20px rgba(245,158,11,0.1); }
    .chain-node-instrument {
        background: rgba(200,65,37,0.05);
        border-color: rgba(200,65,37,0.2);
    }
    .chain-node-instrument:hover { border-color: var(--mr-mars); box-shadow: 0 0 20px rgba(200,65,37,0.1); }

    .chain-node-icon {
        font-size: 22px;
        margin-bottom: 10px;
    }
    .chain-node-label {
        font-family: 'JetBrains Mono', monospace;
        font-size: 9px;
        letter-spacing: 2px;
        text-transform: uppercase;
        display: block;
        margin-bottom: 6px;
    }
    .chain-node-proposal .chain-node-icon { color: var(--mr-green); }
    .chain-node-proposal .chain-node-label { color: var(--mr-green); }
    .chain-node-committee .chain-node-icon { color: var(--mr-cyan); }
    .chain-node-committee .chain-node-label { color: var(--mr-cyan); }
    .chain-node-deputy .chain-node-icon { color: var(--mr-amber); }
    .chain-node-deputy .chain-node-label { color: var(--mr-amber); }
    .chain-node-instrument .chain-node-icon { color: var(--mr-mars); }
    .chain-node-instrument .chain-node-label { color: var(--mr-mars); }

    .chain-node-name {
        font-family: 'DM Sans', sans-serif;
        font-size: 13px;
        font-weight: 600;
        color: #fff;
        display: block;
        line-height: 1.3;
    }
    .chain-node-detail {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        color: var(--mr-text-dim);
        display: block;
        margin-top: 4px;
    }

    .chain-arrow {
        display: flex;
        align-items: center;
        padding: 0 8px;
        color: var(--mr-text-dim);
        font-size: 18px;
        flex-shrink: 0;
    }

    /* ---- Device Info Card ---- */
    .device-card {
        background: var(--mr-surface);
        border: 1px solid var(--mr-border);
        border-radius: 10px;
        padding: 32px;
        margin-bottom: 24px;
    }
    .device-card-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 11px;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--mr-text-dim);
        margin-bottom: 24px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--mr-border);
    }
    .device-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    .device-field {}
    .device-field-label {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: var(--mr-text-dim);
        margin-bottom: 4px;
    }
    .device-field-value {
        font-family: 'DM Sans', sans-serif;
        font-size: 14px;
        color: var(--mr-text);
        word-break: break-all;
    }
    .device-field-value.mono {
        font-family: 'JetBrains Mono', monospace;
        font-size: 12px;
    }
    .device-field-value .copy-btn {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-left: 8px;
        background: var(--mr-dark);
        border: 1px solid var(--mr-border);
        border-radius: 4px;
        padding: 2px 8px;
        font-size: 10px;
        color: var(--mr-text-dim);
        cursor: pointer;
        transition: all 0.2s;
    }
    .device-field-value .copy-btn:hover { border-color: var(--mr-cyan); color: var(--mr-cyan); }
    .device-field.full-width { grid-column: 1 / -1; }

    .status-badge-lg {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        padding: 6px 16px;
        border-radius: 6px;
    }
    .status-badge-lg.status-active {
        background: rgba(52,211,153,0.1);
        color: var(--mr-green);
        border: 1px solid rgba(52,211,153,0.2);
    }
    .status-badge-lg.status-revoked {
        background: rgba(239,68,68,0.1);
        color: var(--mr-red);
        border: 1px solid rgba(239,68,68,0.2);
    }
    .status-badge-lg.status-calibration_due,
    .status-badge-lg.status-calibration_expired {
        background: rgba(245,158,11,0.1);
        color: var(--mr-amber);
        border: 1px solid rgba(245,158,11,0.2);
    }

    /* ---- Tables ---- */
    .data-table-card {
        background: var(--mr-surface);
        border: 1px solid var(--mr-border);
        border-radius: 10px;
        padding: 32px;
        margin-bottom: 24px;
    }
    .data-table-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 11px;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--mr-text-dim);
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--mr-border);
    }
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    .data-table thead th {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: var(--mr-text-dim);
        padding: 10px 12px;
        text-align: left;
        border-bottom: 1px solid var(--mr-border-bright);
        white-space: nowrap;
    }
    .data-table tbody td {
        font-family: 'JetBrains Mono', monospace;
        font-size: 12px;
        color: var(--mr-text);
        padding: 12px;
        border-bottom: 1px solid var(--mr-border);
        vertical-align: middle;
    }
    .data-table tbody tr:hover { background: var(--mr-surface-raised); }
    .data-table tbody tr:last-child td { border-bottom: none; }

    .data-table .hash-cell {
        max-width: 140px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .data-table a { color: var(--mr-cyan); text-decoration: none; }
    .data-table a:hover { color: #fff; }

    .verified-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 10px;
        padding: 2px 8px;
        border-radius: 4px;
        letter-spacing: 1px;
        text-transform: uppercase;
    }
    .verified-badge.verified-yes {
        background: rgba(52,211,153,0.1);
        color: var(--mr-green);
        border: 1px solid rgba(52,211,153,0.2);
    }
    .verified-badge.verified-no {
        background: rgba(245,158,11,0.1);
        color: var(--mr-amber);
        border: 1px solid rgba(245,158,11,0.2);
    }

    /* ---- Anomaly severity ---- */
    .severity-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: 3px 10px;
        border-radius: 4px;
    }
    .severity-info {
        background: rgba(0,228,255,0.1);
        color: var(--mr-cyan);
        border: 1px solid rgba(0,228,255,0.2);
    }
    .severity-warning {
        background: rgba(245,158,11,0.1);
        color: var(--mr-amber);
        border: 1px solid rgba(245,158,11,0.2);
    }
    .severity-critical {
        background: rgba(239,68,68,0.1);
        color: var(--mr-red);
        border: 1px solid rgba(239,68,68,0.2);
    }

    .anomaly-status {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: var(--mr-text-dim);
    }

    /* ---- Empty state inline ---- */
    .empty-inline {
        text-align: center;
        padding: 32px 16px;
        color: var(--mr-text-dim);
        font-size: 13px;
    }
    .empty-inline i {
        display: block;
        font-size: 28px;
        margin-bottom: 10px;
        opacity: 0.4;
    }

    /* ---- Responsive ---- */
    @media (max-width: 991px) {
        .device-grid { grid-template-columns: 1fr; }
        .chain-flow { flex-direction: column; min-width: auto; }
        .chain-arrow { transform: rotate(90deg); padding: 8px 0; justify-content: center; }
    }
    @media (max-width: 768px) {
        .device-card, .data-table-card, .chain-of-trust { padding: 20px; }
        .data-table { font-size: 11px; }
    }

    /* ---- Animations ---- */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .chain-of-trust { animation: fadeIn 0.4s ease-out both; animation-delay: 0.05s; }
    .device-card { animation: fadeIn 0.4s ease-out both; animation-delay: 0.15s; }
    .data-table-card:nth-of-type(1) { animation: fadeIn 0.4s ease-out both; animation-delay: 0.25s; }
    .data-table-card:nth-of-type(2) { animation: fadeIn 0.4s ease-out both; animation-delay: 0.3s; }
    .data-table-card:nth-of-type(3) { animation: fadeIn 0.4s ease-out both; animation-delay: 0.35s; }
    </style>
</head>

<body class="detail-page">
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

                @if($wallet_open)

                {{-- BREADCRUMBS --}}
                <div class="detail-breadcrumbs">
                    <a href="/inventory/all">Infrastructure</a>
                    <span class="sep">/</span>
                    <a href="{{ route('instruments.index') }}">Instruments</a>
                    <span class="sep">/</span>
                    <span class="current">{{ $instrument->device_type_name }}</span>
                </div>

                {{-- CHAIN OF TRUST VISUALIZATION --}}
                <div class="chain-of-trust">
                    <div class="chain-of-trust-title">
                        <i class="fa-solid fa-link" style="margin-right:6px;"></i> Chain of Trust
                    </div>
                    <div class="chain-flow">
                        @if(isset($chain['proposal']) && $chain['proposal'])
                        <a href="/congress/proposal/{{ $chain['proposal']->id }}" class="chain-node chain-node-proposal">
                            <div class="chain-node-icon"><i class="fa-solid fa-landmark"></i></div>
                            <span class="chain-node-label">Proposal</span>
                            <span class="chain-node-name">Bill #{{ $chain['proposal']->id }}</span>
                            <span class="chain-node-detail">{{ \Illuminate\Support\Str::limit($chain['proposal']->title, 30) }}</span>
                        </a>
                        <div class="chain-arrow"><i class="fa-solid fa-arrow-right"></i></div>
                        @else
                        <div class="chain-node chain-node-proposal" style="opacity:0.4;">
                            <div class="chain-node-icon"><i class="fa-solid fa-landmark"></i></div>
                            <span class="chain-node-label">Proposal</span>
                            <span class="chain-node-name">Pending</span>
                            <span class="chain-node-detail">No linked proposal</span>
                        </div>
                        <div class="chain-arrow"><i class="fa-solid fa-arrow-right"></i></div>
                        @endif

                        @if(isset($chain['committee']) && $chain['committee'])
                        <a href="{{ route('committees.index') }}" class="chain-node chain-node-committee">
                            <div class="chain-node-icon"><i class="fa-solid fa-users-rectangle"></i></div>
                            <span class="chain-node-label">Committee</span>
                            <span class="chain-node-name">{{ $chain['committee']->name }}</span>
                            <span class="chain-node-detail">{{ $chain['committee']->role_tag }}</span>
                        </a>
                        <div class="chain-arrow"><i class="fa-solid fa-arrow-right"></i></div>
                        @else
                        <div class="chain-node chain-node-committee" style="opacity:0.4;">
                            <div class="chain-node-icon"><i class="fa-solid fa-users-rectangle"></i></div>
                            <span class="chain-node-label">Committee</span>
                            <span class="chain-node-name">Unassigned</span>
                            <span class="chain-node-detail">--</span>
                        </div>
                        <div class="chain-arrow"><i class="fa-solid fa-arrow-right"></i></div>
                        @endif

                        @if(isset($chain['deputy']) && $chain['deputy'])
                        <div class="chain-node chain-node-deputy">
                            <div class="chain-node-icon"><i class="fa-solid fa-user-shield"></i></div>
                            <span class="chain-node-label">Deputy</span>
                            <span class="chain-node-name">{{ $chain['deputy']->user->fullname ?? 'Unknown' }}</span>
                            <span class="chain-node-detail">{{ $chain['deputy']->role_tag }}</span>
                        </div>
                        <div class="chain-arrow"><i class="fa-solid fa-arrow-right"></i></div>
                        @else
                        <div class="chain-node chain-node-deputy" style="opacity:0.4;">
                            <div class="chain-node-icon"><i class="fa-solid fa-user-shield"></i></div>
                            <span class="chain-node-label">Deputy</span>
                            <span class="chain-node-name">Uncertified</span>
                            <span class="chain-node-detail">--</span>
                        </div>
                        <div class="chain-arrow"><i class="fa-solid fa-arrow-right"></i></div>
                        @endif

                        @php
                            $catInfo = $categories[$instrument->device_category] ?? ['icon' => 'fa-microchip', 'color' => '#c84125'];
                        @endphp
                        <div class="chain-node chain-node-instrument">
                            <div class="chain-node-icon"><i class="fa-solid {{ $catInfo['icon'] }}"></i></div>
                            <span class="chain-node-label">Instrument</span>
                            <span class="chain-node-name">{{ $instrument->device_type_name }}</span>
                            <span class="chain-node-detail">{{ $instrument->serial }}</span>
                        </div>
                    </div>
                </div>

                {{-- DEVICE INFO CARD --}}
                <div class="device-card">
                    <div class="device-card-title">
                        <i class="fa-solid fa-microchip" style="margin-right:6px;"></i> Device Information
                    </div>
                    <div class="device-grid">
                        <div class="device-field">
                            <div class="device-field-label">Device Name</div>
                            <div class="device-field-value">{{ $instrument->device_type_name }}</div>
                        </div>
                        <div class="device-field">
                            <div class="device-field-label">Device Type</div>
                            <div class="device-field-value mono">0x{{ str_pad(dechex($instrument->device_type), 4, '0', STR_PAD_LEFT) }}</div>
                        </div>
                        <div class="device-field">
                            <div class="device-field-label">Serial Number</div>
                            <div class="device-field-value mono">{{ $instrument->serial }}</div>
                        </div>
                        <div class="device-field">
                            <div class="device-field-label">Make / Model</div>
                            <div class="device-field-value">{{ $instrument->make ?? '--' }} {{ $instrument->model ?? '' }}</div>
                        </div>
                        <div class="device-field">
                            <div class="device-field-label">Status</div>
                            <div class="device-field-value">
                                <span class="status-badge-lg status-{{ $instrument->status }}">
                                    @if($instrument->status === 'active')
                                        <i class="fa-solid fa-circle" style="font-size:6px;"></i> Active
                                    @elseif($instrument->status === 'revoked')
                                        <i class="fa-solid fa-xmark"></i> Revoked
                                    @elseif($instrument->status === 'calibration_due')
                                        <i class="fa-solid fa-clock"></i> Calibration Due
                                    @elseif($instrument->status === 'calibration_expired')
                                        <i class="fa-solid fa-exclamation-triangle"></i> Calibration Expired
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="device-field">
                            <div class="device-field-label">Firmware Version</div>
                            <div class="device-field-value mono">{{ $instrument->firmware_version ?? '--' }}</div>
                        </div>
                        <div class="device-field">
                            <div class="device-field-label">Location</div>
                            <div class="device-field-value">{{ $instrument->location ?? '--' }}</div>
                        </div>
                        <div class="device-field">
                            <div class="device-field-label">MQTT Namespace</div>
                            <div class="device-field-value mono">{{ $instrument->mqtt_namespace ?? '--' }}</div>
                        </div>
                        <div class="device-field full-width">
                            <div class="device-field-label">Marscoin Address</div>
                            <div class="device-field-value mono">
                                {{ $instrument->address }}
                                <button class="copy-btn" onclick="navigator.clipboard.writeText('{{ $instrument->address }}');this.innerHTML='<i class=\'fa-solid fa-check\'></i> Copied';" title="Copy address">
                                    <i class="fa-solid fa-copy"></i> Copy
                                </button>
                            </div>
                        </div>
                        @if($instrument->pubkey_hex)
                        <div class="device-field full-width">
                            <div class="device-field-label">Public Key</div>
                            <div class="device-field-value mono" style="font-size:11px;">
                                {{ substr($instrument->pubkey_hex, 0, 20) }}...{{ substr($instrument->pubkey_hex, -12) }}
                                <button class="copy-btn" onclick="navigator.clipboard.writeText('{{ $instrument->pubkey_hex }}');this.innerHTML='<i class=\'fa-solid fa-check\'></i> Copied';" title="Copy public key">
                                    <i class="fa-solid fa-copy"></i> Copy
                                </button>
                            </div>
                        </div>
                        @endif
                        @if($instrument->cert_txid)
                        <div class="device-field full-width">
                            <div class="device-field-label">Certification TX</div>
                            <div class="device-field-value mono">
                                <a href="https://explore.marscoin.org/tx/{{ $instrument->cert_txid }}" target="_blank" style="color:var(--mr-cyan);text-decoration:none;">
                                    {{ substr($instrument->cert_txid, 0, 24) }}...
                                    <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:9px;margin-left:4px;"></i>
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- ATTESTATION HISTORY --}}
                <div class="data-table-card">
                    <div class="data-table-title">
                        <i class="fa-solid fa-link" style="margin-right:6px;"></i> Attestation History
                        <span style="font-family:'JetBrains Mono',monospace;font-size:10px;color:var(--mr-text-dim);margin-left:8px;">(last 20)</span>
                    </div>
                    @if($attestations->isEmpty())
                        <div class="empty-inline">
                            <i class="fa-solid fa-link-slash"></i>
                            No attestations recorded for this instrument yet.
                        </div>
                    @else
                    <div style="overflow-x:auto;">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Readings</th>
                                    <th>Merkle Root</th>
                                    <th>TX</th>
                                    <th>Verified</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attestations as $att)
                                <tr>
                                    <td>{{ $att->batch_end ? $att->batch_end->format('Y-m-d H:i') : ($att->created_at ? $att->created_at->format('Y-m-d H:i') : '--') }}</td>
                                    <td>{{ $att->reading_count }}</td>
                                    <td class="hash-cell" title="{{ $att->merkle_root }}">{{ substr($att->merkle_root, 0, 16) }}...</td>
                                    <td>
                                        @if($att->txid)
                                        <a href="https://explore.marscoin.org/tx/{{ $att->txid }}" target="_blank" title="{{ $att->txid }}">
                                            {{ substr($att->txid, 0, 12) }}...
                                            <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:8px;"></i>
                                        </a>
                                        @else
                                        <span style="color:var(--mr-text-dim);">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($att->verified)
                                            <span class="verified-badge verified-yes"><i class="fa-solid fa-check"></i> Yes</span>
                                        @else
                                            <span class="verified-badge verified-no"><i class="fa-solid fa-clock"></i> No</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>

                {{-- ANOMALIES --}}
                <div class="data-table-card">
                    <div class="data-table-title">
                        <i class="fa-solid fa-triangle-exclamation" style="margin-right:6px;"></i> Anomalies
                    </div>
                    @if($anomalies->isEmpty())
                        <div class="empty-inline">
                            <i class="fa-solid fa-shield-check" style="color:var(--mr-green);opacity:0.6;"></i>
                            No anomalies flagged for this instrument.
                        </div>
                    @else
                    <div style="overflow-x:auto;">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Type</th>
                                    <th>Severity</th>
                                    <th>Status</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($anomalies as $anomaly)
                                <tr>
                                    <td>{{ $anomaly->created_at ? $anomaly->created_at->format('Y-m-d H:i') : '--' }}</td>
                                    <td>{{ str_replace('_', ' ', $anomaly->anomaly_type) }}</td>
                                    <td>
                                        <span class="severity-badge severity-{{ $anomaly->severity }}">
                                            @if($anomaly->severity === 'critical')
                                                <i class="fa-solid fa-circle-exclamation"></i>
                                            @elseif($anomaly->severity === 'warning')
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                            @else
                                                <i class="fa-solid fa-info-circle"></i>
                                            @endif
                                            {{ ucfirst($anomaly->severity) }}
                                        </span>
                                    </td>
                                    <td><span class="anomaly-status">{{ str_replace('_', ' ', $anomaly->status) }}</span></td>
                                    <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $anomaly->notes ?? '--' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>

                {{-- CALIBRATION HISTORY --}}
                <div class="data-table-card">
                    <div class="data-table-title">
                        <i class="fa-solid fa-wrench" style="margin-right:6px;"></i> Calibration History
                    </div>
                    @if($calibrations->isEmpty())
                        <div class="empty-inline">
                            <i class="fa-solid fa-wrench"></i>
                            No calibration records for this instrument.
                        </div>
                    @else
                    <div style="overflow-x:auto;">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Calibrated At</th>
                                    <th>Due At</th>
                                    <th>Calibrated By</th>
                                    <th>TX</th>
                                    <th>CDI Hash</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($calibrations as $cal)
                                <tr>
                                    <td>{{ $cal->calibrated_at ? $cal->calibrated_at->format('Y-m-d H:i') : '--' }}</td>
                                    <td>
                                        @if($cal->due_at)
                                            @if($cal->due_at->isPast())
                                                <span style="color:var(--mr-red);">{{ $cal->due_at->format('Y-m-d') }} (overdue)</span>
                                            @else
                                                {{ $cal->due_at->format('Y-m-d') }}
                                            @endif
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td>{{ $cal->calibrator && $cal->calibrator->user ? $cal->calibrator->user->fullname : '--' }}</td>
                                    <td>
                                        @if($cal->txid)
                                        <a href="https://explore.marscoin.org/tx/{{ $cal->txid }}" target="_blank" title="{{ $cal->txid }}">
                                            {{ substr($cal->txid, 0, 12) }}...
                                            <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:8px;"></i>
                                        </a>
                                        @else
                                        <span style="color:var(--mr-text-dim);">--</span>
                                        @endif
                                    </td>
                                    <td class="hash-cell" title="{{ $cal->new_dice_cdi_hash }}">
                                        {{ $cal->new_dice_cdi_hash ? substr($cal->new_dice_cdi_hash, 0, 16) . '...' : '--' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>

                @else
                {{-- Wallet not open --}}
                <div style="text-align:center;padding:80px 20px;background:var(--mr-surface);border:1px solid var(--mr-border);border-radius:10px;margin-top:40px;">
                    <i class="fa-solid fa-lock" style="font-size:48px;color:var(--mr-text-dim);margin-bottom:16px;display:block;"></i>
                    <h3 style="font-family:'Orbitron',sans-serif;font-size:16px;color:var(--mr-text);margin-bottom:8px;letter-spacing:1px;">Wallet Required</h3>
                    <p style="color:var(--mr-text-dim);font-size:14px;margin-bottom:20px;">Please unlock your civic wallet to view instrument details.</p>
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
</body>
</html>
