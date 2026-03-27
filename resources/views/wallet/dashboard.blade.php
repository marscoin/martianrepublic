<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard - Martian Republic</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Martian Republic Command Center">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/wallet/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/wallet/js/plugins/dataTables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-admin.css?v=2">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-flat.css">
    <link rel="shortcut icon" href="/assets/favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @livewireStyles
    <style>
    /* ---- THE CHAMBER: Dashboard Command Center ---- */
    .dash-hero {
        padding: 32px 0 20px;
        position: relative;
    }
    .dash-hero::after {
        content: '';
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--mr-border-bright, rgba(255,255,255,0.12)) 30%, var(--mr-mars, #c84125) 50%, var(--mr-border-bright, rgba(255,255,255,0.12)) 70%, transparent);
    }
    .dash-greeting {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: var(--mr-mars, #c84125);
        margin-bottom: 6px;
    }
    .dash-greeting .status-dot {
        display: inline-block;
        width: 6px; height: 6px;
        border-radius: 50%;
        background: var(--mr-green, #34d399);
        margin-right: 8px;
        animation: pulse 2s infinite;
        vertical-align: middle;
    }
    .dash-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 28px;
        font-weight: 800;
        color: #fff;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin: 0;
    }

    /* Stats section heading */
    .section-label {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--mr-text-dim, #8a8998);
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 1px solid var(--mr-border, rgba(255,255,255,0.06));
    }

    /* Override portlet for dashboard */
    .dash-card {
        background: var(--mr-surface, #12121e) !important;
        border: 1px solid var(--mr-border, rgba(255,255,255,0.06)) !important;
        border-radius: 8px !important;
        padding: 24px !important;
        margin-bottom: 20px;
    }
    .dash-card .portlet-title {
        font-family: 'Orbitron', sans-serif !important;
        font-size: 12px !important;
        font-weight: 700 !important;
        letter-spacing: 1.5px !important;
        text-transform: uppercase !important;
        color: #fff !important;
        text-decoration: none !important;
        border: none !important;
    }
    .dash-card .portlet-title u {
        text-decoration: none !important;
    }

    /* Transaction table styling */
    .dataTable {
        font-family: 'JetBrains Mono', monospace !important;
        font-size: 12px !important;
    }
    .dataTable thead th {
        font-family: 'JetBrains Mono', monospace !important;
        font-size: 10px !important;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: var(--mr-text-dim) !important;
        border-bottom: 1px solid var(--mr-border) !important;
        background: transparent !important;
    }
    .dataTable tbody td {
        border-bottom: 1px solid var(--mr-border, rgba(255,255,255,0.04)) !important;
        color: var(--mr-text) !important;
        background: transparent !important;
    }
    .dataTables_wrapper .dataTables_filter input,
    .dataTables_wrapper .dataTables_length select {
        background: var(--mr-surface-raised) !important;
        border: 1px solid var(--mr-border-bright) !important;
        color: var(--mr-text) !important;
        border-radius: 4px !important;
        padding: 4px 8px !important;
    }
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        color: var(--mr-text-dim) !important;
        font-family: 'JetBrains Mono', monospace !important;
        font-size: 11px !important;
    }

    /* Footer fix */
    .dash-page { min-height: 100vh; display: flex; flex-direction: column; }
    .dash-page .content { flex: 1; }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .fade-in-1 { animation: fadeIn 0.5s ease-out 0.2s both; }
    .fade-in-2 { animation: fadeIn 0.5s ease-out 0.4s both; }
    .fade-in-3 { animation: fadeIn 0.5s ease-out 0.6s both; }
    .fade-in-4 { animation: fadeIn 0.5s ease-out 0.8s both; }

    @media (max-width: 767px) {
        .dash-title { font-size: 20px; }
        .table-responsive, .dataTables_wrapper { overflow-x: auto; }
        .dataTable th, .dataTable td { white-space: nowrap; }
        #chart-summary { grid-template-columns: repeat(2, 1fr) !important; }
        #chart-view-toggle { flex-wrap: wrap; }
    }
    </style>
</head>

<body class="dash-page">
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
        @include('wallet.mainnav', ['active' => 'dashboard'])
        <div class="content">
            <div class="container">

                {{-- HERO --}}
                <div class="dash-hero">
                    <div class="dash-greeting"><span class="status-dot"></span>Command Center — Online</div>
                    <h1 class="dash-title">Dashboard</h1>
                </div>

                {{-- Contextual Onboarding Banner --}}
                @if(isset($applied) && $applied && isset($general_public) && !$general_public)
                <a href="/citizen/all" style="display: block; margin-top: 20px; padding: 16px 20px; background: linear-gradient(135deg, rgba(0,228,255,0.06), rgba(0,228,255,0.02)); border: 1px solid rgba(0,228,255,0.2); border-radius: 10px; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.borderColor='rgba(0,228,255,0.4)'" onmouseout="this.style.borderColor='rgba(0,228,255,0.2)'">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(0,228,255,0.12); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fa fa-rocket" style="font-size: 16px; color: var(--mr-cyan, #00e4ff);"></i>
                        </div>
                        <div style="flex: 1;">
                            <div style="font-family: 'Orbitron', sans-serif; font-size: 12px; font-weight: 600; color: #fff; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 2px;">
                                Complete Your Application
                            </div>
                            <div style="font-family: 'JetBrains Mono', monospace; font-size: 10px; color: var(--mr-text-dim, #8a8998);">
                                Your profile is ready — publish it to the Marscoin blockchain to join the General Public
                            </div>
                        </div>
                        <i class="fa fa-arrow-right" style="color: var(--mr-cyan, #00e4ff); font-size: 14px;"></i>
                    </div>
                </a>
                @elseif(isset($general_public) && $general_public && isset($citizen_status) && $citizen_status !== 'CT')
                <a href="/citizen/all" style="display: block; margin-top: 20px; padding: 16px 20px; background: linear-gradient(135deg, rgba(52,211,153,0.06), rgba(52,211,153,0.02)); border: 1px solid rgba(52,211,153,0.15); border-radius: 10px; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.borderColor='rgba(52,211,153,0.3)'" onmouseout="this.style.borderColor='rgba(52,211,153,0.15)'">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(52,211,153,0.12); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fa fa-handshake" style="font-size: 16px; color: var(--mr-green, #34d399);"></i>
                        </div>
                        <div style="flex: 1;">
                            <div style="font-family: 'Orbitron', sans-serif; font-size: 12px; font-weight: 600; color: #fff; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 2px;">
                                Gather Endorsements
                            </div>
                            <div style="font-family: 'JetBrains Mono', monospace; font-size: 10px; color: var(--mr-text-dim, #8a8998);">
                                Connect with citizens to receive endorsements and earn full citizenship
                            </div>
                        </div>
                        <i class="fa fa-arrow-right" style="color: var(--mr-green, #34d399); font-size: 14px;"></i>
                    </div>
                </a>
                @endif

                <div class="row" style="margin-top: 24px;">
                    <div class="col-md-4 col-sm-5 fade-in-1">
                        <div class="section-label">Account Overview</div>
                        <div class="dash-card">
                            @livewire('dashboard-stats')
                        </div>
                        <div style="margin-top: 16px;">
                            @livewire('citizen-id-card')
                        </div>
                    </div>
                    <div class="col-md-8 col-sm-7 fade-in-2">
                        <div class="section-label">Activity</div>
                        <div class="dash-card">

                            @if ($wallet_open)
                                {{-- Financial Overview Header --}}
                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                                    <h4 class="portlet-title" style="margin: 0;">Financial Overview</h4>
                                    <div id="chart-view-toggle" style="display: flex; gap: 2px; background: var(--mr-dark, #0c0c16); border-radius: 6px; padding: 2px; border: 1px solid var(--mr-border, rgba(255,255,255,0.06));">
                                        <button class="chart-toggle-btn active" data-view="combined" style="font-family: 'JetBrains Mono', monospace; font-size: 9px; letter-spacing: 1px; text-transform: uppercase; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; transition: all 0.2s; background: var(--mr-mars, #c84125); color: #fff;">Combined</button>
                                        <button class="chart-toggle-btn" data-view="flow" style="font-family: 'JetBrains Mono', monospace; font-size: 9px; letter-spacing: 1px; text-transform: uppercase; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; transition: all 0.2s; background: transparent; color: var(--mr-text-dim, #8a8998);">Flow</button>
                                        <button class="chart-toggle-btn" data-view="balance" style="font-family: 'JetBrains Mono', monospace; font-size: 9px; letter-spacing: 1px; text-transform: uppercase; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; transition: all 0.2s; background: transparent; color: var(--mr-text-dim, #8a8998);">Balance</button>
                                    </div>
                                </div>

                                {{-- Summary Stats --}}
                                <div id="chart-summary" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1px; background: var(--mr-border, rgba(255,255,255,0.06)); border-radius: 6px; overflow: hidden; margin-bottom: 20px;">
                                    <div style="background: var(--mr-dark, #0c0c16); padding: 12px 14px;">
                                        <div style="font-family: 'JetBrains Mono', monospace; font-size: 8px; letter-spacing: 1.5px; text-transform: uppercase; color: var(--mr-text-faint, #5a5968);">Total In</div>
                                        <div id="stat-total-in" style="font-family: 'Orbitron', sans-serif; font-size: 14px; font-weight: 600; color: var(--mr-green, #34d399); margin-top: 2px;">-</div>
                                    </div>
                                    <div style="background: var(--mr-dark, #0c0c16); padding: 12px 14px;">
                                        <div style="font-family: 'JetBrains Mono', monospace; font-size: 8px; letter-spacing: 1.5px; text-transform: uppercase; color: var(--mr-text-faint, #5a5968);">Total Out</div>
                                        <div id="stat-total-out" style="font-family: 'Orbitron', sans-serif; font-size: 14px; font-weight: 600; color: var(--mr-mars, #c84125); margin-top: 2px;">-</div>
                                    </div>
                                    <div style="background: var(--mr-dark, #0c0c16); padding: 12px 14px;">
                                        <div style="font-family: 'JetBrains Mono', monospace; font-size: 8px; letter-spacing: 1.5px; text-transform: uppercase; color: var(--mr-text-faint, #5a5968);">Net</div>
                                        <div id="stat-net" style="font-family: 'Orbitron', sans-serif; font-size: 14px; font-weight: 600; color: #fff; margin-top: 2px;">-</div>
                                    </div>
                                    <div style="background: var(--mr-dark, #0c0c16); padding: 12px 14px;">
                                        <div style="font-family: 'JetBrains Mono', monospace; font-size: 8px; letter-spacing: 1.5px; text-transform: uppercase; color: var(--mr-text-faint, #5a5968);">Transactions</div>
                                        <div id="stat-tx-count" style="font-family: 'Orbitron', sans-serif; font-size: 14px; font-weight: 600; color: var(--mr-cyan, #00e4ff); margin-top: 2px;">-</div>
                                    </div>
                                </div>

                                {{-- Time range indicator --}}
                                <div id="chart-timerange" style="font-family: 'JetBrains Mono', monospace; font-size: 10px; color: var(--mr-text-faint, #5a5968); margin-bottom: 8px; display: none;">
                                    <i class="fa fa-clock-o" style="margin-right: 4px;"></i>
                                    <span id="chart-timerange-text"></span>
                                </div>

                                <div id="chart" style="min-height: 320px;"></div>

                            @else
                                @if ($has_civic_wallet || $has_wallet)
                                    <div style="padding: 30px 20px; text-align: center;">
                                        <h4 class="portlet-title"><u>Marscoin Wallet</u></h4>
                                        <div style="margin: 24px 0;">
                                            <i class="fa fa-lock" style="font-size: 48px; color: var(--mr-text-secondary, #8a8998); margin-bottom: 16px; display: block;"></i>
                                            <p style="color: var(--mr-text-secondary, #8a8998); margin-bottom: 20px;">Your wallet is locked. Unlock it to view your balance and make transactions.</p>
                                            <a href="/wallet/dashboard/hd" class="btn btn-lg btn-primary">
                                                <i class="fa fa-unlock-alt"></i> Unlock Wallet
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="wallet-is-not-open" style="padding: 32px 24px;">
                                        <div style="text-align: center; margin-bottom: 28px;">
                                            <div style="font-family: 'Orbitron', sans-serif; font-size: 16px; font-weight: 700; color: #fff; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 6px;">Welcome to Mars</div>
                                            <div style="font-family: 'JetBrains Mono', monospace; font-size: 11px; color: var(--mr-text-dim, #8a8998);">Complete these steps to begin your journey as a Martian citizen</div>
                                        </div>

                                        <div style="display: flex; flex-direction: column; gap: 12px; max-width: 440px; margin: 0 auto;">
                                            {{-- Step 1: Account --}}
                                            <div style="display: flex; align-items: center; gap: 14px; padding: 14px 18px; background: rgba(52,211,153,0.06); border: 1px solid rgba(52,211,153,0.2); border-radius: 10px;">
                                                <div style="width: 36px; height: 36px; border-radius: 50%; background: rgba(52,211,153,0.12); border: 2px solid var(--mr-green, #34d399); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                    <i class="fa fa-check" style="color: var(--mr-green); font-size: 14px;"></i>
                                                </div>
                                                <div>
                                                    <div style="font-family: 'JetBrains Mono', monospace; font-size: 11px; font-weight: 500; color: var(--mr-green, #34d399);">Account Created</div>
                                                    <div style="font-family: 'JetBrains Mono', monospace; font-size: 9px; color: var(--mr-text-faint, #5a5968);">Your Martian Republic account is active</div>
                                                </div>
                                            </div>

                                            {{-- Step 2: 2FA --}}
                                            <div style="display: flex; align-items: center; gap: 14px; padding: 14px 18px; background: rgba(52,211,153,0.06); border: 1px solid rgba(52,211,153,0.2); border-radius: 10px;">
                                                <div style="width: 36px; height: 36px; border-radius: 50%; background: rgba(52,211,153,0.12); border: 2px solid var(--mr-green, #34d399); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                    <i class="fa fa-check" style="color: var(--mr-green); font-size: 14px;"></i>
                                                </div>
                                                <div>
                                                    <div style="font-family: 'JetBrains Mono', monospace; font-size: 11px; font-weight: 500; color: var(--mr-green, #34d399);">2FA Secured</div>
                                                    <div style="font-family: 'JetBrains Mono', monospace; font-size: 9px; color: var(--mr-text-faint, #5a5968);">Two-factor authentication is protecting your account</div>
                                                </div>
                                            </div>

                                            {{-- Step 3: Wallet (CURRENT) --}}
                                            <div style="display: flex; align-items: center; gap: 14px; padding: 14px 18px; background: rgba(0,228,255,0.06); border: 1px solid rgba(0,228,255,0.25); border-radius: 10px; position: relative; overflow: hidden;">
                                                <div style="position: absolute; left: 0; top: 0; bottom: 0; width: 3px; background: var(--mr-cyan, #00e4ff); animation: pulse 2s infinite;"></div>
                                                <div style="width: 36px; height: 36px; border-radius: 50%; background: rgba(0,228,255,0.12); border: 2px solid var(--mr-cyan, #00e4ff); display: flex; align-items: center; justify-content: center; flex-shrink: 0; animation: pulse 2s infinite;">
                                                    <i class="fa fa-arrow-right" style="color: var(--mr-cyan); font-size: 14px;"></i>
                                                </div>
                                                <div style="flex: 1;">
                                                    <div style="font-family: 'JetBrains Mono', monospace; font-size: 11px; font-weight: 500; color: var(--mr-cyan, #00e4ff);">Create Your Wallet</div>
                                                    <div style="font-family: 'JetBrains Mono', monospace; font-size: 9px; color: var(--mr-text-faint, #5a5968);">Set up your Marscoin wallet to participate in the Republic</div>
                                                </div>
                                                <a href="/wallet/create" class="onboard-cta" style="padding: 8px 16px; background: var(--mr-mars, #c84125); border-radius: 6px; font-family: 'JetBrains Mono', monospace; font-size: 10px; letter-spacing: 1px; text-transform: uppercase; color: #fff; text-decoration: none; white-space: nowrap;">
                                                    Create Wallet <i class="fa fa-arrow-right" style="margin-left: 4px;"></i>
                                                </a>
                                                <style>
                                                    .onboard-cta {
                                                        animation: ctaPulse 2s ease-in-out infinite;
                                                    }
                                                    @keyframes ctaPulse {
                                                        0%, 100% { box-shadow: 0 0 0 0 rgba(200,65,37,0.4); }
                                                        50% { box-shadow: 0 0 16px 4px rgba(200,65,37,0.2); }
                                                    }
                                                </style>
                                            </div>

                                            {{-- Step 4: Citizen (LOCKED) --}}
                                            <div style="display: flex; align-items: center; gap: 14px; padding: 14px 18px; background: rgba(255,255,255,0.02); border: 1px solid var(--mr-border, rgba(255,255,255,0.06)); border-radius: 10px; opacity: 0.4;">
                                                <div style="width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.04); border: 2px solid var(--mr-border-bright, rgba(255,255,255,0.12)); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                    <span style="font-family: 'JetBrains Mono', monospace; font-size: 13px; font-weight: 700; color: var(--mr-text-faint);">4</span>
                                                </div>
                                                <div>
                                                    <div style="font-family: 'JetBrains Mono', monospace; font-size: 11px; font-weight: 500; color: var(--mr-text-dim, #8a8998);">Register as Citizen</div>
                                                    <div style="font-family: 'JetBrains Mono', monospace; font-size: 9px; color: var(--mr-text-faint, #5a5968);">Submit your identity to join the Martian citizenry</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif


                            @endif


                        </div> <!-- /.dash-card -->
                    </div>
                </div>

                <div class="row fade-in-3">

                    @if ($wallet_open)
                        <div class="col-md-12">
                            <div class="section-label">Transaction Ledger</div>
                            <div class="dash-card">
                                <div class="wallet-is-open">
                                    <h4 class="portlet-title">
                                        Wallet Transactions
                                    </h4>
                                    <div class="table-responsive">
                                    <table class="table table-striped table-bordered dataTable" id="table-2"
                                        aria-describedby="table-2">
                                        <thead>
                                            <tr role="row">
                                                <th style="width: 250px;" class="sorting desc" role="columnheader"
                                                    tabindex="0" aria-controls="table-1" rowspan="1"
                                                    colspan="1" aria-label="" aria-sort="descending">
                                                    Date
                                                </th>

                                                <th style="width: 250px;" class="text-center" role="columnheader"
                                                    tabindex="0" aria-controls="table-1" rowspan="1"
                                                    colspan="1"
                                                    aria-label="CSS grade: activate to sort column ascending">
                                                    Recipient Address / Anchored Data Link</th>
                                                <th style="width: 110px;" class="sorting" role="columnheader"
                                                    tabindex="1" aria-controls="table-1" rowspan="1"
                                                    colspan="1"
                                                    aria-label="Platform(s): activate to sort column ascending">
                                                    MARS
                                                </th>
                                                <th style="width: 110px;" class="text-center sorting"
                                                    role="columnheader" tabindex="2" aria-controls="table-1"
                                                    rowspan="1" colspan="1"
                                                    aria-label="Engine version: activate to sort column ascending">
                                                    USD</th>
                                                <th style="width: 110px;" class="sorting_desc sorting"
                                                    role="columnheader" aria-sort="descending" tabindex="3"
                                                    aria-controls="table-1" rowspan="1" colspan="1"
                                                    aria-label="Rendering engine: activate to sort column descending">
                                                    Transaction Id</th>


                                            </tr>
                                        </thead>


                                        <tbody role="alert" aria-live="polite" aria-relevant="all">

                                        </tbody>
                                    </table>
                                    </div><!-- /.table-responsive -->

                                </div>
                            </div>

                        </div>
                    @endif


                </div>

                <div class="row fade-in-4">
                    <div class="col-md-3">
                        <div class="section-label">Network</div>
                        <div class="dash-card" style="margin-bottom: 16px;">
                            @livewire('block-display')
                        </div>
                        <div class="dash-card" style="margin-bottom: 16px;">
                            @livewire('hodler-stats')
                        </div>
                        {{-- Mars Land CTA --}}
                        <a href="/map/all" class="mars-land-cta" style="display: block; text-decoration: none; position: relative; overflow: hidden; border-radius: 8px; border: 1px solid var(--mr-border, rgba(255,255,255,0.06)); background: var(--mr-surface, #12121e); padding: 16px 18px; transition: border-color 0.3s, transform 0.2s;">
                            <div style="position: absolute; bottom: -8px; right: -20px; opacity: 0.08; pointer-events: none;">
                                <svg width="120" height="60" viewBox="0 0 120 60" fill="none">
                                    <path d="M0 60 Q60 0 120 60" fill="var(--mr-mars, #c84125)"/>
                                </svg>
                            </div>
                            <svg width="100%" height="28" viewBox="0 0 200 28" preserveAspectRatio="none" style="display: block; margin-bottom: 10px;">
                                <defs>
                                    <linearGradient id="marsHorizon" x1="0" y1="0" x2="1" y2="0">
                                        <stop offset="0%" stop-color="transparent"/>
                                        <stop offset="30%" stop-color="var(--mr-mars, #c84125)" stop-opacity="0.7"/>
                                        <stop offset="50%" stop-color="var(--mr-mars, #c84125)"/>
                                        <stop offset="70%" stop-color="var(--mr-mars, #c84125)" stop-opacity="0.7"/>
                                        <stop offset="100%" stop-color="transparent"/>
                                    </linearGradient>
                                    <radialGradient id="marsGlow" cx="50%" cy="100%" r="60%">
                                        <stop offset="0%" stop-color="var(--mr-mars, #c84125)" stop-opacity="0.15"/>
                                        <stop offset="100%" stop-color="transparent"/>
                                    </radialGradient>
                                </defs>
                                <rect x="0" y="0" width="200" height="28" fill="url(#marsGlow)"/>
                                <path d="M0 26 Q50 8 100 6 Q150 8 200 26" stroke="url(#marsHorizon)" stroke-width="2" fill="none"/>
                                <path d="M40 26 Q100 12 160 26" fill="var(--mr-mars, #c84125)" fill-opacity="0.06"/>
                            </svg>
                            <div style="font-family: 'Orbitron', sans-serif; font-size: 11px; font-weight: 700; color: #fff; letter-spacing: 1px; text-transform: uppercase;">
                                Stake Mars Acreage
                            </div>
                            <div style="font-family: 'JetBrains Mono', monospace; font-size: 9px; color: var(--mr-text-dim, #8a8998); margin-top: 3px; letter-spacing: 0.5px;">
                                Claim your plot on the Red Planet <i class="fa fa-arrow-right" style="font-size: 8px; margin-left: 4px; color: var(--mr-mars, #c84125);"></i>
                            </div>
                        </a>
                        <style>
                            .mars-land-cta:hover {
                                border-color: rgba(200,65,37,0.4) !important;
                                transform: translateY(-1px);
                            }
                        </style>
                    </div>
                    <div class="col-md-5">
                        <div class="section-label">Republic Activity</div>
                        <div class="dash-card">
                            <h4 class="portlet-title">Live Feed</h4>
                            @livewire('civic-status-feed')
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="section-label">Market Data</div>
                        <div class="dash-card" style="margin-bottom: 16px;">
                            <h4 class="portlet-title">MARS / USD</h4>
                            <div id="price-display">
                                <div style="text-align: center; padding: 20px; color: var(--mr-text-dim);">
                                    <i class="fa fa-spinner fa-spin"></i> Loading market data...
                                </div>
                            </div>
                        </div>
                        <div class="dash-card">
                            @livewire('block-interval-sparkline')
                        </div>
                        {{-- Mobile Wallet Downloads --}}
                        <div style="margin-top: 16px; border-radius: 8px; border: 1px solid var(--mr-border, rgba(255,255,255,0.06)); background: var(--mr-surface, #12121e); padding: 16px 18px;">
                            <div style="font-family: 'JetBrains Mono', monospace; font-size: 9px; letter-spacing: 1.5px; text-transform: uppercase; color: var(--mr-text-dim, #8a8998); margin-bottom: 10px;">
                                <i class="fa fa-mobile" style="margin-right: 4px;"></i> Mobile Wallets
                            </div>
                            <div style="display: flex; gap: 8px;">
                                <a href="https://apps.apple.com/us/app/martianrepublic/id6480416861" target="_blank" rel="noopener"
                                   class="mobile-dl-btn" style="flex: 1; display: flex; align-items: center; gap: 8px; padding: 10px 12px; background: var(--mr-dark, #0c0c16); border: 1px solid var(--mr-border-bright, rgba(255,255,255,0.1)); border-radius: 6px; text-decoration: none; transition: border-color 0.2s;">
                                    <i class="fa fa-apple" style="font-size: 18px; color: #fff;"></i>
                                    <div>
                                        <div style="font-family: 'JetBrains Mono', monospace; font-size: 8px; color: var(--mr-text-faint, #5a5968); letter-spacing: 0.5px;">Download on</div>
                                        <div style="font-family: 'Orbitron', sans-serif; font-size: 10px; font-weight: 600; color: #fff;">App Store</div>
                                    </div>
                                </a>
                                <a href="https://play.google.com/store/apps/details?id=io.bytewallet.bytewallet" target="_blank" rel="noopener"
                                   class="mobile-dl-btn" style="flex: 1; display: flex; align-items: center; gap: 8px; padding: 10px 12px; background: var(--mr-dark, #0c0c16); border: 1px solid var(--mr-border-bright, rgba(255,255,255,0.1)); border-radius: 6px; text-decoration: none; transition: border-color 0.2s;">
                                    <i class="fa fa-android" style="font-size: 18px; color: var(--mr-green, #34d399);"></i>
                                    <div>
                                        <div style="font-family: 'JetBrains Mono', monospace; font-size: 8px; color: var(--mr-text-faint, #5a5968); letter-spacing: 0.5px;">Get it on</div>
                                        <div style="font-family: 'Orbitron', sans-serif; font-size: 10px; font-weight: 600; color: #fff;">Google Play</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <style>
                            .mobile-dl-btn:hover {
                                border-color: rgba(255,255,255,0.25) !important;
                            }
                        </style>
                    </div> <!-- /.col -->
                </div> <!-- /.row -->
            </div> <!-- /.container -->
        </div> <!-- .content -->
    </div> <!-- /#wrapper -->
    <footer class="footer" style="border-top: 1px solid var(--mr-border, rgba(255,255,255,0.06)); padding: 20px 0; background: var(--mr-void, #06060c);">
        @include('footer')
    </footer>
    <script src="/assets/wallet/js/libs/jquery-1.10.2.min.js"></script>
    <script src="/assets/wallet/js/libs/bootstrap.min.js"></script>
    <script src="/assets/wallet/js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="/assets/wallet/js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script src="/assets/wallet/js/plugins/flot/jquery.flot.js"></script>
    <script src="/assets/wallet/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="/assets/wallet/js/plugins/flot/jquery.flot.pie.js"></script>
    <script src="/assets/wallet/js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="/assets/wallet/js/mvpready-core.js"></script>
    @if (count($transactions) <= 0)
        <script src="/assets/wallet/js/demos/flot/line.js"></script>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js" integrity="sha512-A7AYk1fGKX6S2SsHywmPkrnzTZHrgiVT7GcQkLGDe2ev0aWb8zejytzS8wjo7PGEXKqJOrjQ4oORtnimIRZBtw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        AOS.init();
    </script>
    <script>

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function calculateRunningBalanceWithoutFees(jsonData, address) {
    // Parse the JSON data
    var balanceData = [];
    const transactions = jsonData.txs;
    transactions.sort((a, b) => a.time - b.time);
    
    let runningBalance = 0;
    let processedTransactions = 0;
    let totalTxValue = 0;
    //maxCount = 18;

    for (let tx of transactions) {
        totalTxValue = 0;
        //if (processedTransactions >= maxCount) {
        //    break; // Stop processing if we've reached the maxCount
        //}

        // Track if the address has sent or received in this transaction
        let isSender = false;
        let valueReceived = 0;

        // Calculate the value sent (vin)
        tx.vin.forEach((vin) => {
            if (vin.addr === address) {
                isSender = true;
                runningBalance -= vin.value; // Subtract the value sent from the balance
                totalTxValue -= vin.value;
            }
        });

        // Calculate the value received (vout) - handle both v28 and legacy formats
        tx.vout.forEach((vout) => {
            var ra = vout.scriptPubKey.addresses || (vout.scriptPubKey.address ? [vout.scriptPubKey.address] : []);
            if (ra.includes(address)) {
                valueReceived += parseFloat(vout.value);
            }
        });

        // If the address is the sender, subtract the fees from the balance
        if (isSender) {
            runningBalance -= tx.fees;
            totalTxValue -= tx.fees;
        }

        // Add the value received to the running balance
        runningBalance += valueReceived;
        totalTxValue += valueReceived;
        var time = Math.round(tx.time * 1000);
        balanceData.push({x: time, y: runningBalance, z: totalTxValue, n: processedTransactions});
        processedTransactions++;
    }
    return balanceData;
}



        $(document).ready(function() 
        {
            var mars_price = 0;
            var lastdate = 10000000000;
            var firstdate = 0;
            var d1 = [],
                d2 = [];

            var mars_price = 0;

            $.ajax({
                url: '/api/price',
                type: 'POST',
                data: {
                },
                success: function(data) {
                    mars_price = parseFloat(data.mars_price);
                    console.log("Mars price: ", mars_price);
                    $('.connectivity').hide();


                    // Fetch transactions for primary address (civic wallet)
                    // TODO: aggregate from all addresses for full wallet view
                    $.post("/api/getTransactions", {
                "address": '<?= $public_addr ?>'
            }, function(data) {
                if (data) {

                    

                    var address = '<?= $public_addr ?>';
                    var transactions = data.txs;
                    transactions.sort((a, b) => a.time - b.time);
    
                    var balance = 0; 
                    var balanceData = []; 

                    let runningBalance = 0;
                    let processedTransactions = 0;
                    let totalTxValue = 0;
                    //maxCount = 18;

                    for (let tx of transactions) 
                    {
                        totalTxValue = 0;
                        let dataRowContent = "";
                        // Track if the address has sent or received in this transaction
                        let isSender = false;
                        let valueReceived = 0;
                        let color = "green";
                        let recipient = "";

                        // Calculate the value sent (vin)
                        tx.vin.forEach((vin) => {
                            if (vin.addr === address) {
                                isSender = true;
                                runningBalance -= vin.value; // Subtract the value sent from the balance
                                totalTxValue -= vin.value;
                            }
                        });

                        // Calculate the value received (vout) - handle both v28 and legacy address formats
                        tx.vout.forEach((vout) =>
                        {
                            var vAddrs = vout.scriptPubKey.addresses || (vout.scriptPubKey.address ? [vout.scriptPubKey.address] : []);
                            if (vAddrs.includes(address)) {
                                valueReceived += parseFloat(vout.value);
                            }else if (vAddrs.length > 0){
                                recipient = vAddrs[0];
                            }

                            if (vout.scriptPubKey.type == "nulldata") {
                                dataRowContent = "<tr><td>" + format_time(tx.time) + "</td><td>" +
                                dectag(hexToAscii(vout.scriptPubKey.asm.split("OP_RETURN ")[
                                    1])) + " " + ipfsfy(hexToAscii(vout.scriptPubKey.asm
                                    .split("OP_RETURN ")[1])) +
                                "</td><td><span style='color: red'>" + Number.parseFloat(tx.fees)
                                .toFixed(8) + "</span></td><td>$" + (Number.parseFloat(mars_price)
                                    .toFixed(4) * Number.parseFloat(tx.fees)).toFixed(8) +
                                "</td><td><a href='http://explore.marscoin.org/tx/" + tx.txid +
                                "' target='_blank'>" + tx.txid.substring(1, 18) +
                                "...</a></td></tr>";

                                 $("#table-2 tbody").append(dataRowContent);
                            
                            } 
                        });

                        // If the address is the sender, subtract the fees from the balance
                        if (isSender) {
                            runningBalance -= tx.fees;
                            totalTxValue -= tx.fees;
                            color = "red";
                        }

                        // Add the value received to the running balance
                        runningBalance += valueReceived;
                        totalTxValue += valueReceived;
                        var time = Math.round(tx.time * 1000);
                        balanceData.push({x: time, y: runningBalance, z: totalTxValue, n: processedTransactions});
                        processedTransactions++;
                        if(totalTxValue > 0) 
                            color = "green"; 
                            //even if sender, when receiving amount is positive, we received more than sent...

                        if(dataRowContent == "")
                        {
                            newRowContent = "<tr><td>" + format_time(tx.time) +
                            "</td><td><a target='_blank' href='http://explore.marscoin.org/address/" +
                            recipient + "' >" + recipient +
                            "</a></td><td><b><span style='color: " + color + "'>" + totalTxValue.toFixed(4) +
                            "</span></b></td><td>$" + (Number.parseFloat(mars_price).toFixed(
                                4) * Number.parseFloat(totalTxValue).toFixed(8)).toFixed(
                            2) + "</td><td><a href='http://explore.marscoin.org/tx/" + tx
                            .txid + "' target='_blank'>" + tx.txid.substring(1, 18) +
                            "...</a></td></tr>";

                            $("#table-2 tbody").append(newRowContent);
                        }
                    }
                    
                    firstdate = firstdate - (60 * 60 * 24 * 30);
                    lastdate = lastdate + (60 * 60 * 24 * 30);
                    firstdate = firstdate * 1000;
                    lastdate = lastdate * 1000;

                    $('#table-2').DataTable({
                            "order": [
                                [0, "desc"]
                            ],
                            "columnDefs": [
                                { type: 'date', 'targets': [0] }
                            ],
                            "aoColumns": [{
                                    "orderSequence": ["desc", "asc"]
                                },
                                {
                                    "mData": "recipient"
                                },
                                {
                                    "mData": "usd"
                                },
                                {
                                    "mData": "mars",
                                    "sClass": "text-center"
                                },
                                {
                                    "mData": "time",
                                    "sClass": "text-center"
                                }
                            ],
                            "fnInitComplete": function(oSettings, json) {
                                $(this).parents('.dataTables_wrapper').find(
                                        '.dataTables_filter input').prop('placeholder', 'Search...')
                                    .addClass('form-control input-sm');
                                $("#table-2 thead th").eq(0).click(); 
                            },
                     });

                }

                    // ================================================================
                    // FINANCIAL OVERVIEW CHART ENGINE
                    // ================================================================

                    // Build per-transaction flow data with PROPER inflow/outflow tracking
                    // The existing balanceData[].z is unreliable for HD wallets because
                    // change goes to different addresses, making z = -(full UTXO) instead
                    // of -(actual amount sent). We re-process from raw transactions.
                    let txFlowData = [];
                    let totalIn = 0, totalOut = 0;
                    var rawTxs = data.txs.slice().sort(function(a, b) { return a.time - b.time; });
                    var flowBalance = 0;
                    for (var ti = 0; ti < rawTxs.length; ti++) {
                        var ftx = rawTxs[ti];
                        var fIsSender = false;
                        var fValueReceived = 0;
                        var fValueSentToOthers = 0;
                        var fVinTotal = 0;

                        // Check vins - are we the sender?
                        ftx.vin.forEach(function(vin) {
                            if (vin.addr === address) {
                                fIsSender = true;
                                fVinTotal += vin.value;
                            }
                        });

                        // Check vouts (handle both v28 'address' and legacy 'addresses' formats)
                        ftx.vout.forEach(function(vout) {
                            var sp = vout.scriptPubKey || {};
                            var addrs = sp.addresses || (sp.address ? [sp.address] : []);
                            if (addrs.includes(address)) {
                                fValueReceived += parseFloat(vout.value);
                            } else if (sp.type !== 'nulldata' && addrs.length > 0) {
                                fValueSentToOthers += parseFloat(vout.value);
                            }
                        });

                        var fInflow = 0, fOutflow = 0;
                        if (fIsSender) {
                            // We spent: outflow = amount to others + fees
                            fOutflow = fValueSentToOthers + ftx.fees;
                            // We might also receive in same tx (rare but possible)
                            // Only count received from others (vouts to us minus our own change)
                            // If we're sender, vouts to us IS change, not real income
                        } else {
                            // Pure receive
                            fInflow = fValueReceived;
                        }

                        flowBalance += fInflow - fOutflow;
                        totalIn += fInflow;
                        totalOut += fOutflow;
                        txFlowData.push({
                            time: Math.round(ftx.time * 1000),
                            inflow: fInflow,
                            outflow: fOutflow,
                            balance: flowBalance
                        });
                    }

                    // Update summary stats
                    document.getElementById('stat-total-in').textContent = totalIn.toFixed(4) + ' MARS';
                    document.getElementById('stat-total-out').textContent = totalOut.toFixed(4) + ' MARS';
                    var net = totalIn - totalOut;
                    var netEl = document.getElementById('stat-net');
                    netEl.textContent = (net >= 0 ? '+' : '') + net.toFixed(4) + ' MARS';
                    netEl.style.color = net >= 0 ? 'var(--mr-green, #34d399)' : 'var(--mr-mars, #c84125)';
                    document.getElementById('stat-tx-count').textContent = txFlowData.length;

                    // Smart time bucketing
                    function detectBucketSize(data) {
                        if (data.length < 2) return 'none';
                        var spanMs = data[data.length - 1].time - data[0].time;
                        var spanDays = spanMs / (86400000);
                        if (spanDays <= 3) return 'hour';
                        if (spanDays <= 14) return 'day';
                        if (spanDays <= 90) return 'week';
                        if (spanDays <= 730) return 'month';
                        if (spanDays <= 2555) return 'quarter';
                        return 'year';
                    }

                    function getBucketKey(timestamp, bucketSize) {
                        var d = new Date(timestamp);
                        switch (bucketSize) {
                            case 'hour':
                                return new Date(d.getFullYear(), d.getMonth(), d.getDate(), d.getHours()).getTime();
                            case 'day':
                                return new Date(d.getFullYear(), d.getMonth(), d.getDate()).getTime();
                            case 'week':
                                var day = d.getDay();
                                var diff = d.getDate() - day + (day === 0 ? -6 : 1);
                                return new Date(d.getFullYear(), d.getMonth(), diff).getTime();
                            case 'month':
                                return new Date(d.getFullYear(), d.getMonth(), 1).getTime();
                            case 'quarter':
                                return new Date(d.getFullYear(), Math.floor(d.getMonth() / 3) * 3, 1).getTime();
                            case 'year':
                                return new Date(d.getFullYear(), 0, 1).getTime();
                            default:
                                return timestamp;
                        }
                    }

                    function formatBucketLabel(bucketSize) {
                        var labels = {
                            'hour': 'Hourly', 'day': 'Daily', 'week': 'Weekly',
                            'month': 'Monthly', 'quarter': 'Quarterly', 'year': 'Yearly', 'none': 'Per Transaction'
                        };
                        return labels[bucketSize] || bucketSize;
                    }

                    function bucketData(flowData, bucketSize) {
                        if (bucketSize === 'none' || flowData.length <= 20) {
                            // Few transactions: show each one individually
                            return flowData.map(function(d) {
                                return { time: d.time, inflow: d.inflow, outflow: d.outflow, balance: d.balance };
                            });
                        }
                        var buckets = {};
                        var lastBalance = 0;
                        for (var i = 0; i < flowData.length; i++) {
                            var key = getBucketKey(flowData[i].time, bucketSize);
                            if (!buckets[key]) {
                                buckets[key] = { time: key, inflow: 0, outflow: 0, balance: 0 };
                            }
                            buckets[key].inflow += flowData[i].inflow;
                            buckets[key].outflow += flowData[i].outflow;
                            buckets[key].balance = flowData[i].balance; // last balance in bucket
                            lastBalance = flowData[i].balance;
                        }

                        // Fill gaps between buckets so the chart is continuous
                        var keys = Object.keys(buckets).map(Number).sort(function(a, b) { return a - b; });
                        var filled = [];
                        var prevBal = 0;
                        for (var k = 0; k < keys.length; k++) {
                            // If gap is > 1 bucket, insert empty buckets
                            if (k > 0 && bucketSize !== 'none') {
                                var prevKey = keys[k - 1];
                                var nextExpected = getNextBucket(prevKey, bucketSize);
                                while (nextExpected < keys[k]) {
                                    filled.push({ time: nextExpected, inflow: 0, outflow: 0, balance: prevBal });
                                    nextExpected = getNextBucket(nextExpected, bucketSize);
                                }
                            }
                            filled.push(buckets[keys[k]]);
                            prevBal = buckets[keys[k]].balance;
                        }
                        return filled;
                    }

                    function getNextBucket(timestamp, bucketSize) {
                        var d = new Date(timestamp);
                        switch (bucketSize) {
                            case 'hour': return new Date(d.getFullYear(), d.getMonth(), d.getDate(), d.getHours() + 1).getTime();
                            case 'day': return new Date(d.getFullYear(), d.getMonth(), d.getDate() + 1).getTime();
                            case 'week': return new Date(d.getFullYear(), d.getMonth(), d.getDate() + 7).getTime();
                            case 'month': return new Date(d.getFullYear(), d.getMonth() + 1, 1).getTime();
                            case 'quarter': return new Date(d.getFullYear(), d.getMonth() + 3, 1).getTime();
                            case 'year': return new Date(d.getFullYear() + 1, 0, 1).getTime();
                            default: return timestamp + 1;
                        }
                    }

                    var bucketSize = detectBucketSize(txFlowData);
                    var chartBuckets = bucketData(txFlowData, bucketSize);

                    // Show time range indicator
                    if (txFlowData.length >= 2) {
                        var rangeEl = document.getElementById('chart-timerange');
                        var first = new Date(txFlowData[0].time);
                        var last = new Date(txFlowData[txFlowData.length - 1].time);
                        var rangeText = first.toLocaleDateString('en-US', {month: 'short', year: 'numeric'}) +
                            ' \u2014 ' + last.toLocaleDateString('en-US', {month: 'short', year: 'numeric'}) +
                            ' \u00b7 ' + formatBucketLabel(txFlowData.length <= 20 ? 'none' : bucketSize) + ' view';
                        document.getElementById('chart-timerange-text').textContent = rangeText;
                        rangeEl.style.display = 'block';
                    }

                    // Prepare series data
                    var inflowSeries = chartBuckets.map(function(b) { return { x: b.time, y: parseFloat(b.inflow.toFixed(4)) }; });
                    var outflowSeries = chartBuckets.map(function(b) { return { x: b.time, y: parseFloat((-b.outflow).toFixed(4)) }; });
                    var balanceSeries = chartBuckets.map(function(b) { return { x: b.time, y: parseFloat(b.balance.toFixed(4)) }; });

                    // Determine x-axis format based on bucket size
                    var xaxisFormat = 'MMM yyyy';
                    if (bucketSize === 'hour') xaxisFormat = 'HH:mm';
                    else if (bucketSize === 'day') xaxisFormat = 'dd MMM';
                    else if (bucketSize === 'week') xaxisFormat = 'dd MMM';

                    // Chart render function
                    var currentView = 'combined';
                    var finChart = null;

                    function renderFinChart(view) {
                        if (finChart) { finChart.destroy(); }
                        currentView = view;

                        var series, chartType, yaxisConfig, strokeConfig, fillConfig, plotOptions;
                        var maxBalance = Math.max.apply(null, chartBuckets.map(function(b) { return b.balance; }));
                        var maxInflow = Math.max.apply(null, chartBuckets.map(function(b) { return b.inflow; }));
                        var maxOutflow = Math.max.apply(null, chartBuckets.map(function(b) { return b.outflow; }));
                        var maxFlow = Math.max(maxInflow, maxOutflow);

                        if (view === 'flow') {
                            series = [
                                { name: 'Received', data: inflowSeries, type: 'bar' },
                                { name: 'Sent', data: outflowSeries, type: 'bar' }
                            ];
                            yaxisConfig = [{
                                labels: {
                                    style: { colors: '#5a5968', fontFamily: "'JetBrains Mono', monospace", fontSize: '10px' },
                                    formatter: function(v) { return Math.abs(v).toFixed(2); }
                                },
                                forceNiceScale: true
                            }];
                            strokeConfig = { width: [0, 0], curve: 'smooth' };
                            fillConfig = { opacity: [0.85, 0.85] };
                            plotOptions = {
                                bar: {
                                    columnWidth: chartBuckets.length <= 12 ? '40%' : '65%',
                                    borderRadius: 3,
                                    borderRadiusApplication: 'end'
                                }
                            };
                        } else if (view === 'balance') {
                            series = [
                                { name: 'Balance', data: balanceSeries, type: 'area' }
                            ];
                            yaxisConfig = [{
                                min: 0,
                                max: maxBalance * 1.1,
                                labels: {
                                    style: { colors: '#5a5968', fontFamily: "'JetBrains Mono', monospace", fontSize: '10px' },
                                    formatter: function(v) { return v.toFixed(2); }
                                }
                            }];
                            strokeConfig = { width: [2.5], curve: 'smooth' };
                            fillConfig = {
                                type: ['gradient'],
                                gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.02, stops: [0, 100] }
                            };
                            plotOptions = {};
                        } else {
                            // Combined: bars for flow + area for balance
                            // Scale balance to fit the flow axis range for reliable rendering
                            var maxFlowAbs = Math.max(maxInflow, maxOutflow, 0.01);
                            var balScale = maxBalance > 0 ? (maxFlowAbs * 0.85) / maxBalance : 1;
                            var scaledBalanceSeries = chartBuckets.map(function(b) {
                                return { x: b.time, y: parseFloat((b.balance * balScale).toFixed(4)) };
                            });

                            series = [
                                { name: 'Received', data: inflowSeries, type: 'bar' },
                                { name: 'Sent', data: outflowSeries, type: 'bar' },
                                { name: 'Balance', data: scaledBalanceSeries, type: 'area' }
                            ];
                            yaxisConfig = [
                                {
                                    labels: {
                                        style: { colors: '#5a5968', fontFamily: "'JetBrains Mono', monospace", fontSize: '10px' },
                                        formatter: function(v) { return Math.abs(v).toFixed(2); }
                                    },
                                    forceNiceScale: true,
                                    title: { text: 'Flow (MARS)', style: { color: '#5a5968', fontFamily: "'JetBrains Mono', monospace", fontSize: '9px' } }
                                },
                                { show: false },
                                {
                                    opposite: true,
                                    show: true,
                                    min: 0,
                                    labels: {
                                        style: { colors: '#00e4ff', fontFamily: "'JetBrains Mono', monospace", fontSize: '10px' },
                                        formatter: function(v) {
                                            // Reverse the scaling to show real balance values
                                            var real = balScale > 0 ? v / balScale : 0;
                                            return real.toFixed(1);
                                        }
                                    },
                                    title: { text: 'Balance (MARS)', style: { color: '#00e4ff', fontFamily: "'JetBrains Mono', monospace", fontSize: '9px' } }
                                }
                            ];
                            strokeConfig = { width: [0, 0, 2.5], curve: ['smooth', 'smooth', 'smooth'] };
                            fillConfig = {
                                opacity: [0.85, 0.85, 0.12],
                                type: ['solid', 'solid', 'gradient'],
                                gradient: { shadeIntensity: 0, opacityFrom: 0.2, opacityTo: 0.02, stops: [0, 90] }
                            };
                            plotOptions = {
                                bar: {
                                    columnWidth: chartBuckets.length <= 12 ? '40%' : '60%',
                                    borderRadius: 3,
                                    borderRadiusApplication: 'end'
                                }
                            };
                        }

                        var options = {
                            series: series,
                            chart: {
                                type: view === 'combined' ? 'line' : (view === 'balance' ? 'area' : 'bar'),
                                height: 320,
                                stacked: view === 'flow',
                                toolbar: { show: false },
                                zoom: { enabled: true, type: 'x', autoScaleYaxis: true },
                                foreColor: '#8a8998',
                                background: 'transparent',
                                fontFamily: "'JetBrains Mono', monospace",
                                animations: {
                                    enabled: true,
                                    easing: 'easeinout',
                                    speed: 600,
                                    dynamicAnimation: { enabled: true, speed: 300 }
                                }
                            },
                            colors: view === 'balance'
                                ? ['#00e4ff']
                                : ['#34d399', '#c84125', '#00e4ff'],
                            plotOptions: plotOptions,
                            dataLabels: { enabled: false },
                            stroke: strokeConfig,
                            fill: fillConfig,
                            grid: {
                                borderColor: 'rgba(255,255,255,0.04)',
                                strokeDashArray: 3,
                                xaxis: { lines: { show: false } },
                                yaxis: { lines: { show: true } },
                                padding: { left: 8, right: 8 }
                            },
                            xaxis: {
                                type: 'datetime',
                                labels: {
                                    format: xaxisFormat,
                                    style: { colors: '#5a5968', fontFamily: "'JetBrains Mono', monospace", fontSize: '9px' },
                                    datetimeUTC: false,
                                    rotate: -45,
                                    rotateAlways: chartBuckets.length > 24
                                },
                                axisBorder: { show: true, color: 'rgba(255,255,255,0.06)' },
                                axisTicks: { show: true, color: 'rgba(255,255,255,0.06)' },
                                crosshairs: {
                                    show: true,
                                    stroke: { color: 'rgba(255,255,255,0.15)', width: 1, dashArray: 3 }
                                }
                            },
                            yaxis: yaxisConfig,
                            tooltip: {
                                theme: 'dark',
                                shared: true,
                                intersect: false,
                                style: { fontFamily: "'JetBrains Mono', monospace", fontSize: '11px' },
                                x: { format: 'dd MMM yyyy' },
                                y: {
                                    formatter: function(value, opts) {
                                        if (value === undefined || value === null) return '';
                                        var name = opts.w.config.series[opts.seriesIndex].name;
                                        var absVal = Math.abs(value).toFixed(4);
                                        if (name === 'Sent') return absVal + ' MARS';
                                        return absVal + ' MARS';
                                    }
                                }
                            },
                            markers: view === 'combined' ? {
                                size: [0, 0, 4],
                                colors: ['transparent', 'transparent', '#00e4ff'],
                                strokeColors: '#0c0c16',
                                strokeWidth: 2,
                                hover: { sizeOffset: 2 }
                            } : (view === 'balance' ? {
                                size: [3],
                                colors: ['#00e4ff'],
                                strokeColors: '#0c0c16',
                                strokeWidth: 2,
                                hover: { sizeOffset: 2 }
                            } : { size: 0 }),
                            legend: {
                                show: view === 'combined' || view === 'flow',
                                position: 'top',
                                horizontalAlign: 'left',
                                fontFamily: "'JetBrains Mono', monospace",
                                fontSize: '10px',
                                labels: { colors: '#8a8998' },
                                markers: { radius: 2, width: 10, height: 10 },
                                itemMargin: { horizontal: 12 }
                            }
                        };

                        finChart = new ApexCharts(document.querySelector("#chart"), options);
                        finChart.render();
                    }

                    // Initial render
                    renderFinChart('combined');

                    // Toggle buttons
                    document.querySelectorAll('.chart-toggle-btn').forEach(function(btn) {
                        btn.addEventListener('click', function() {
                            document.querySelectorAll('.chart-toggle-btn').forEach(function(b) {
                                b.style.background = 'transparent';
                                b.style.color = 'var(--mr-text-dim, #8a8998)';
                                b.classList.remove('active');
                            });
                            this.style.background = 'var(--mr-mars, #c84125)';
                            this.style.color = '#fff';
                            this.classList.add('active');
                            renderFinChart(this.getAttribute('data-view'));
                        });
                    });

                });




                },
                error: function() {
                    console.log('Error fetching Mars price');
                    $('.connectivity').show();
                }
            });

            


            function format_time(s) {
                if (s)
                    return new Date(s * 1000).toISOString().slice(0, 19).replace('T', ' ');
                else return "pending...";
            }


            function hexToAscii(hex) {
                var hex = hex.toString();
                var str = '';
                for (var n = 0; n < hex.length; n += 2) {
                    str += String.fromCharCode(parseInt(hex.substr(n, 2), 16));
                }
                return str;
            }

            function dectag(decoded) {
                var new_string = "";
                parts = decoded.split("_");
                if (parts.length > 1) {
                    if (parts[0] == "test") {
                        new_string += "TEST: ";
                    } else if (parts[0] == "GP") {
                        new_string += "Martian Public Application: ";
                    } else if (parts[0] == "CT") {
                        new_string += "Martian Citizen Endorsement: ";
                    } else if (parts[0] == "PR") {
                        new_string += "Proposal: ";
                    } else {
                        new_string += parts[0];
                    }
                }
                return new_string;
            }

            function ipfsfy(decoded) {
                var ipfs_link = "";
                parts = decoded.split("_");
                if (parts.length > 1) {

                    ipfs_link = "<a target='_blank' href='https://ipfs.marscoin.org/ipfs/" + parts[1] +
                        "'>IPFS Link</a>";
                    return ipfs_link;
                }
                return "missing";
            }






        });
    </script>
    <script>
    // Fetch MARS price from our own API
    fetch('/api/mars-price')
        .then(r => r.json())
        .then(d => {
            const mars = d.data['154'];
            const q = mars.quote.USD;
            const price = q.price;
            const change24h = q.percent_change_24h;
            const change7d = q.percent_change_7d;
            const volume = q.volume_24h;
            const maxSupply = mars.max_supply;

            const changeColor24 = change24h >= 0 ? 'var(--mr-green, #34d399)' : 'var(--mr-mars, #c84125)';
            const changeColor7d = change7d >= 0 ? 'var(--mr-green, #34d399)' : 'var(--mr-mars, #c84125)';
            const arrow24 = change24h >= 0 ? '&#9650;' : '&#9660;';
            const arrow7d = change7d >= 0 ? '&#9650;' : '&#9660;';

            document.getElementById('price-display').innerHTML = `
                <div style="margin-bottom: 16px;">
                    <div style="font-family: 'Orbitron', sans-serif; font-size: 28px; font-weight: 700; color: #fff;">
                        $${price.toFixed(6)}
                    </div>
                    <div style="font-family: 'JetBrains Mono', monospace; font-size: 11px; margin-top: 4px;">
                        <span style="color: ${changeColor24};">${arrow24} ${Math.abs(change24h).toFixed(2)}%</span>
                        <span style="color: var(--mr-text-faint); margin: 0 8px;">24h</span>
                        <span style="color: ${changeColor7d};">${arrow7d} ${Math.abs(change7d).toFixed(2)}%</span>
                        <span style="color: var(--mr-text-faint);">7d</span>
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1px; background: var(--mr-border); border-radius: 6px; overflow: hidden;">
                    <div style="background: var(--mr-dark, #0c0c16); padding: 12px;">
                        <div style="font-family: 'JetBrains Mono', monospace; font-size: 9px; letter-spacing: 1.5px; text-transform: uppercase; color: var(--mr-text-dim); margin-bottom: 4px;">24h Volume</div>
                        <div style="font-family: 'Orbitron', sans-serif; font-size: 13px; font-weight: 600; color: #fff;">$${volume.toLocaleString(undefined, {maximumFractionDigits: 0})}</div>
                    </div>
                    <div style="background: var(--mr-dark, #0c0c16); padding: 12px;">
                        <div style="font-family: 'JetBrains Mono', monospace; font-size: 9px; letter-spacing: 1.5px; text-transform: uppercase; color: var(--mr-text-dim); margin-bottom: 4px;">Max Supply</div>
                        <div style="font-family: 'Orbitron', sans-serif; font-size: 13px; font-weight: 600; color: #fff;">${(maxSupply/1e6).toFixed(1)}M</div>
                    </div>
                </div>
            `;
        })
        .catch(() => {
            document.getElementById('price-display').innerHTML = '<div style="text-align:center;padding:20px;color:var(--mr-text-faint);">Price data unavailable</div>';
        });
    </script>
    @livewireScripts
</body>

</html>
