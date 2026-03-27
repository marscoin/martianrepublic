<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <title>The Chronicle - Martian Republic Logbook</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Martian Republic Chronicle - Pioneer research logbook">
    <meta name="author" content="The Marscoin Foundation">
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
    <link rel="stylesheet" href="/assets/wallet/css/simplemde.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/upload.css">
    <link rel="stylesheet" href="/assets/wallet/css/dropify.css">
    <link rel="shortcut icon" href="/assets/favicon.ico">
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
    @livewireStyles

    <style>
    /* ============================================
       THE CHRONICLE — Research Logbook
       Martian Republic Mission Control Aesthetic
       ============================================ */
    html, body { background: #06060c !important; }
    .footer { z-index: 100; position: relative; clear: both; padding: 20px 0 !important; background: var(--mr-void, #06060c) !important; }
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

    .chronicle-page {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }
    .chronicle-page .content { flex: 1; }
    .chronicle-page .footer { margin-top: auto; }

    /* ---- Hero Bar ---- */
    .chronicle-hero {
        position: relative;
        padding: 48px 0 32px;
        overflow: hidden;
    }
    .chronicle-hero::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background:
            radial-gradient(ellipse at 30% 50%, rgba(200,65,37,0.06) 0%, transparent 60%),
            radial-gradient(ellipse at 70% 20%, rgba(0,228,255,0.03) 0%, transparent 50%);
        pointer-events: none;
    }
    .chronicle-hero::after {
        content: '';
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--mr-border-bright) 20%, var(--mr-mars) 50%, var(--mr-border-bright) 80%, transparent);
    }

    .chronicle-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
    }
    .chronicle-subtitle {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: var(--mr-mars);
        margin-bottom: 8px;
    }
    .chronicle-subtitle .dot {
        display: inline-block;
        width: 6px; height: 6px;
        border-radius: 50%;
        background: var(--mr-green);
        margin-right: 6px;
        animation: pulse 2s infinite;
        vertical-align: middle;
    }
    .chronicle-title {
        font-family: 'Orbitron', sans-serif;
        font-weight: 800;
        font-size: 36px;
        letter-spacing: 2px;
        color: #fff;
        text-transform: uppercase;
        margin: 0;
    }

    /* ---- Filter Tabs ---- */
    .chronicle-filters {
        display: flex;
        gap: 2px;
        margin: 32px 0 28px;
        background: var(--mr-border);
        border-radius: 8px;
        overflow: hidden;
    }
    .chronicle-filter {
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
    .chronicle-filter:first-child { border-radius: 8px 0 0 8px; }
    .chronicle-filter:last-child { border-radius: 0 8px 8px 0; }
    .chronicle-filter:hover {
        background: var(--mr-surface-raised);
        color: var(--mr-text);
    }
    .chronicle-filter.active {
        background: var(--mr-surface-raised);
        color: #fff;
    }
    .chronicle-filter.active::after {
        content: '';
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 2px;
        background: var(--mr-mars);
    }
    .chronicle-filter i {
        margin-right: 6px;
        font-size: 12px;
    }

    /* ---- Tab Content ---- */
    .chronicle-tab-content .chronicle-tab-pane {
        display: none;
        animation: fadeIn 0.4s ease-out both;
    }
    .chronicle-tab-content .chronicle-tab-pane.active {
        display: block;
    }

    /* ---- Section Cards ---- */
    .chronicle-section {
        background: var(--mr-surface);
        border: 1px solid var(--mr-border);
        border-radius: 10px;
        padding: 28px;
        margin-bottom: 20px;
    }
    .chronicle-section-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 11px;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--mr-text-dim);
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--mr-border);
    }

    /* ---- Form Inputs ---- */
    .chronicle-label {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: var(--mr-text-dim);
        margin-bottom: 8px;
        display: block;
    }
    .chronicle-label .required { color: var(--mr-mars); }

    .chronicle-input {
        width: 100%;
        background: var(--mr-dark);
        border: 1px solid var(--mr-border);
        border-radius: 8px;
        padding: 14px 16px;
        color: #fff;
        font-size: 16px;
        font-family: 'Open Sans', sans-serif;
        transition: border-color 0.2s;
    }
    .chronicle-input:focus { outline: none; border-color: var(--mr-cyan); }
    .chronicle-input::placeholder { color: var(--mr-text-dim); }

    /* ---- SimpleMDE Dark Override ---- */
    .CodeMirror {
        background: var(--mr-dark) !important;
        color: var(--mr-text) !important;
        border: 1px solid var(--mr-border) !important;
        border-radius: 0 0 8px 8px !important;
        min-height: 350px;
    }
    .CodeMirror-cursor { border-left-color: var(--mr-cyan) !important; }
    .CodeMirror-selected { background: rgba(0,228,255,0.1) !important; }
    .CodeMirror-gutters { background: var(--mr-dark) !important; border-right-color: var(--mr-border) !important; }
    .CodeMirror-linenumber { color: var(--mr-text-dim) !important; }
    .editor-toolbar {
        background: var(--mr-surface-raised) !important;
        border: 1px solid var(--mr-border) !important;
        border-radius: 8px 8px 0 0 !important;
        border-bottom: none !important;
    }
    .editor-toolbar a { color: var(--mr-text-dim) !important; }
    .editor-toolbar a:hover, .editor-toolbar a.active { color: var(--mr-cyan) !important; background: transparent !important; }
    .editor-toolbar i.separator { border-left-color: var(--mr-border) !important; border-right-color: var(--mr-border) !important; }
    .editor-toolbar.fullscreen { z-index: 2470 !important; }
    .CodeMirror-scroll { min-height: 350px; }

    /* ---- FilePond Dark Override ---- */
    .filepond--root { font-family: 'Open Sans', sans-serif !important; }
    .filepond--panel-root {
        background-color: var(--mr-dark) !important;
        border: 1px dashed var(--mr-border-bright) !important;
        border-radius: 8px !important;
    }
    .filepond--drop-label {
        color: var(--mr-text-dim) !important;
        font-size: 13px !important;
    }
    .filepond--drop-label label { cursor: pointer !important; }
    .filepond--label-action {
        color: var(--mr-cyan) !important;
        text-decoration: none !important;
    }
    .filepond--item-panel { background: var(--mr-surface-raised) !important; }
    .filepond--drip-blob { background: var(--mr-mars) !important; }
    .filepond--file-action-button {
        color: var(--mr-text) !important;
        cursor: pointer !important;
    }
    .filepond--file { color: var(--mr-text) !important; }
    .filepond--image-preview-wrapper { background: var(--mr-dark) !important; }

    /* ---- Publish Button ---- */
    .btn-publish {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: var(--mr-mars) !important;
        color: #fff !important;
        padding: 16px 36px;
        border-radius: 8px;
        font-family: 'Orbitron', sans-serif;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        text-decoration: none;
        border: 1px solid transparent;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .btn-publish:hover, .btn-publish:focus {
        background: transparent !important;
        border-color: var(--mr-mars) !important;
        color: var(--mr-mars) !important;
        box-shadow: 0 0 30px rgba(200,65,37,0.2);
        text-decoration: none !important;
    }

    /* ---- DataTables Dark Theme ---- */
    .dataTables_wrapper {
        color: var(--mr-text) !important;
        font-family: 'Open Sans', sans-serif;
    }
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info {
        color: var(--mr-text-dim) !important;
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        letter-spacing: 0.5px;
    }
    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input {
        background: var(--mr-dark) !important;
        border: 1px solid var(--mr-border) !important;
        border-radius: 6px !important;
        color: var(--mr-text) !important;
        padding: 6px 10px !important;
        font-size: 12px;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
        outline: none;
        border-color: var(--mr-cyan) !important;
    }
    table.dataTable {
        border-collapse: collapse !important;
        border-spacing: 0 !important;
    }
    table.dataTable thead th {
        background: var(--mr-dark) !important;
        color: var(--mr-text-dim) !important;
        border-bottom: 1px solid var(--mr-border-bright) !important;
        font-family: 'JetBrains Mono', monospace !important;
        font-size: 10px !important;
        letter-spacing: 1.5px !important;
        text-transform: uppercase !important;
        padding: 14px 16px !important;
    }
    table.dataTable thead .sorting::before,
    table.dataTable thead .sorting::after,
    table.dataTable thead .sorting_asc::before,
    table.dataTable thead .sorting_asc::after,
    table.dataTable thead .sorting_desc::before,
    table.dataTable thead .sorting_desc::after {
        color: var(--mr-text-dim) !important;
        opacity: 0.4 !important;
    }
    table.dataTable thead .sorting_asc::after,
    table.dataTable thead .sorting_desc::before {
        opacity: 1 !important;
        color: var(--mr-cyan) !important;
    }
    table.dataTable tbody td {
        background: var(--mr-surface) !important;
        color: var(--mr-text) !important;
        border-bottom: 1px solid var(--mr-border) !important;
        padding: 14px 16px !important;
        font-size: 13px;
        vertical-align: middle;
    }
    table.dataTable tbody tr:hover td {
        background: var(--mr-surface-raised) !important;
    }
    table.dataTable tbody td a {
        color: var(--mr-cyan) !important;
        text-decoration: none;
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
    }
    table.dataTable tbody td a:hover {
        color: #fff !important;
    }
    .dataTables_wrapper .dataTables_paginate {
        margin-top: 16px !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        background: var(--mr-surface) !important;
        border: 1px solid var(--mr-border) !important;
        border-radius: 6px !important;
        color: var(--mr-text-dim) !important;
        padding: 6px 12px !important;
        margin: 0 2px !important;
        font-size: 12px;
        transition: all 0.2s ease;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: var(--mr-surface-raised) !important;
        border-color: var(--mr-border-bright) !important;
        color: var(--mr-text) !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--mr-mars) !important;
        border-color: var(--mr-mars) !important;
        color: #fff !important;
        font-weight: 700;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        opacity: 0.3 !important;
    }

    /* ---- Status Badges ---- */
    .badge-notarized {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        padding: 4px 12px;
        border-radius: 4px;
        background: rgba(0,228,255,0.08);
        color: var(--mr-cyan);
        border: 1px solid rgba(0,228,255,0.15);
    }
    .badge-pending {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        padding: 4px 12px;
        border-radius: 4px;
        background: rgba(245,158,11,0.1);
        color: var(--mr-amber);
        border: 1px solid rgba(245,158,11,0.2);
    }

    /* ---- Action Buttons ---- */
    .btn-notarize {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--mr-mars);
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 8px 16px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        letter-spacing: 1px;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .btn-notarize:hover {
        background: transparent;
        color: var(--mr-mars);
        box-shadow: inset 0 0 0 1px var(--mr-mars);
        text-decoration: none;
    }
    .btn-delete {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: transparent;
        color: var(--mr-text-dim);
        border: 1px solid var(--mr-border);
        border-radius: 6px;
        padding: 8px 16px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        letter-spacing: 1px;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .btn-delete:hover {
        border-color: var(--mr-red);
        color: var(--mr-red);
        text-decoration: none;
    }

    /* ---- Modal Override ---- */
    .modal-content {
        background: var(--mr-surface) !important;
        border: 1px solid var(--mr-border) !important;
        border-radius: 10px !important;
        color: var(--mr-text) !important;
    }
    .modal-header {
        border-bottom: 1px solid var(--mr-border) !important;
        padding: 20px 24px !important;
    }
    .modal-header .close { color: var(--mr-text-dim) !important; text-shadow: none !important; opacity: 0.8 !important; }
    .modal-header .modal-title, .modal-header h3, .modal-header h5 {
        font-family: 'Orbitron', sans-serif !important;
        font-size: 14px !important;
        letter-spacing: 1.5px !important;
        text-transform: uppercase !important;
        color: #fff !important;
    }
    .modal-body {
        padding: 24px !important;
        color: var(--mr-text) !important;
    }
    .modal-body p {
        color: var(--mr-text-dim);
        font-size: 14px;
        line-height: 1.7;
    }
    .modal-body-box {
        margin-bottom: 16px;
        padding: 12px 16px;
        background: var(--mr-dark);
        border-radius: 6px;
        border: 1px solid var(--mr-border);
    }
    .modal-body-box h5 {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: var(--mr-text-dim);
        margin: 0 0 6px;
    }
    .modal-body-box p { color: var(--mr-text); font-size: 14px; margin: 0; word-break: break-all; }
    .modal-footer {
        border-top: 1px solid var(--mr-border) !important;
        padding: 16px 24px !important;
    }
    .modal-footer .btn-success,
    .modal-footer .btn-primary {
        background: var(--mr-mars) !important;
        border: none !important;
        font-family: 'Orbitron', sans-serif !important;
        font-size: 11px !important;
        letter-spacing: 1.5px !important;
        text-transform: uppercase !important;
        padding: 12px 24px !important;
        border-radius: 6px !important;
        color: #fff !important;
    }
    .modal-footer .btn-success:hover,
    .modal-footer .btn-primary:hover {
        background: transparent !important;
        box-shadow: inset 0 0 0 1px var(--mr-mars) !important;
        color: var(--mr-mars) !important;
    }
    .modal-footer .btn-secondary,
    .modal-footer .btn-default {
        background: var(--mr-dark) !important;
        border: 1px solid var(--mr-border) !important;
        color: var(--mr-text-dim) !important;
        font-family: 'JetBrains Mono', monospace !important;
        font-size: 11px !important;
        letter-spacing: 1px !important;
        text-transform: uppercase !important;
        padding: 12px 24px !important;
        border-radius: 6px !important;
    }
    .modal-footer .btn-danger {
        background: var(--mr-red) !important;
        border: none !important;
        font-family: 'Orbitron', sans-serif !important;
        font-size: 11px !important;
        letter-spacing: 1.5px !important;
        text-transform: uppercase !important;
        padding: 12px 24px !important;
        border-radius: 6px !important;
        color: #fff !important;
    }
    .modal-footer .btn-danger:hover {
        background: transparent !important;
        box-shadow: inset 0 0 0 1px var(--mr-red) !important;
        color: var(--mr-red) !important;
    }
    .transaction-hash {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        color: var(--mr-cyan);
        word-break: break-all;
        padding: 0 24px;
    }
    .transaction-hash-link {
        color: var(--mr-cyan) !important;
        font-family: 'JetBrains Mono', monospace;
        font-size: 12px;
        word-break: break-all;
    }
    #modal-message-success {
        color: var(--mr-green) !important;
        font-family: 'JetBrains Mono', monospace;
        font-size: 12px;
    }
    #modal-message-error {
        color: var(--mr-red) !important;
        font-family: 'JetBrains Mono', monospace;
        font-size: 12px;
    }
    .modal-backdrop { background: rgba(6,6,12,0.85) !important; }

    /* ---- Wallet Required / Empty States ---- */
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
        line-height: 1.7;
        max-width: 480px;
        margin: 0 auto 20px;
    }
    .empty-state a.btn-unlock {
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
    .empty-state a.btn-unlock:hover {
        background: transparent !important;
        border-color: var(--mr-mars) !important;
        color: var(--mr-mars) !important;
        text-decoration: none !important;
    }
    .empty-state .btn-create-first {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--mr-mars);
        color: #fff;
        padding: 12px 24px;
        border-radius: 8px;
        font-family: 'Orbitron', sans-serif;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .empty-state .btn-create-first:hover {
        background: transparent;
        box-shadow: inset 0 0 0 1px var(--mr-mars);
        color: var(--mr-mars);
        text-decoration: none;
    }

    /* ---- Responsive ---- */
    @media (max-width: 991px) {
        .chronicle-header { flex-direction: column; }
        .chronicle-filters { flex-wrap: wrap; }
        .chronicle-filter { flex: 0 0 calc(33.333% - 2px); }
    }
    @media (max-width: 768px) {
        .chronicle-title { font-size: 24px; }
        .chronicle-filter { flex: 0 0 calc(100% - 2px); }
        .chronicle-section { padding: 20px; }
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

<body class="chronicle-page">
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
        @include('wallet.mainnav', array('active'=>'logbook'))

        <div class="content">
            <div class="container">

                <?php if($wallet_open){ ?>

                {{-- HERO BAR --}}
                <div class="chronicle-hero">
                    <div class="chronicle-header">
                        <div>
                            <div class="chronicle-subtitle">
                                <span class="dot"></span> Chronicle &mdash; Active
                            </div>
                            <h1 class="chronicle-title">Logbook</h1>
                        </div>
                    </div>
                </div>

                {{-- FILTER TABS --}}
                <div class="chronicle-filters" id="chronicle-filters">
                    <button class="chronicle-filter active" data-tab="New-Entry">
                        <i class="fa-solid fa-pen-nib"></i> New Entry
                    </button>
                    <button class="chronicle-filter" data-tab="All">
                        <i class="fa-solid fa-globe"></i> All Publications
                    </button>
                    <button class="chronicle-filter" data-tab="My">
                        <i class="fa-solid fa-user-pen"></i> My Publications
                    </button>
                </div>

                {{-- TAB CONTENT --}}
                <div class="chronicle-tab-content">
                    <div class="chronicle-tab-pane active" id="New-Entry">
                        @include('logbook.logbook')
                    </div>
                    <div class="chronicle-tab-pane" id="All">
                        @include('logbook.allentries')
                    </div>
                    <div class="chronicle-tab-pane" id="My">
                        @include('logbook.myentries')
                    </div>
                </div>

                <?php }else{ ?>

                {{-- WALLET REQUIRED STATE --}}
                <div class="chronicle-hero">
                    <div class="chronicle-header">
                        <div>
                            <div class="chronicle-subtitle">Chronicle &mdash; Locked</div>
                            <h1 class="chronicle-title">Logbook</h1>
                        </div>
                    </div>
                </div>

                <div class="empty-state" style="margin-top:32px;">
                    <i class="fa-solid fa-lock"></i>
                    <h3>Wallet Required</h3>
                    <p>Please unlock your civic wallet to access the Research Logbook.</p>
                    <a href="/wallet/dashboard/hd" class="btn-unlock"><i class="fa-solid fa-unlock-alt"></i> Unlock Wallet</a>
                </div>

                <?php } ?>

            </div>
        </div>
    </div>

    <footer class="footer">
        @include('footer')
    </footer>

    <script src="/assets/wallet/js/dist/my_bundle.js"></script>
    <script src="/assets/wallet/js/libs/jquery-1.10.2.min.js"></script>
    <script src="/assets/wallet/js/libs/bootstrap.min.js"></script>
    <script src="/assets/wallet/js/plugins/flot/jquery.flot.js"></script>
    <script src="/assets/wallet/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="/assets/wallet/js/plugins/flot/jquery.flot.pie.js"></script>
    <script src="/assets/wallet/js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="/assets/wallet/js/mvpready-core.js"></script>
    <script src="/assets/wallet/js/mvpready-admin.js"></script>
    <script src="/assets/wallet/js/simplemde.min.js"></script>
    <script src="/assets/wallet/js/md5.min.js"></script>
    <script src="/assets/wallet/js/sha256.js"></script>
    <script src="/assets/wallet/js/dropify.js"></script>
    {{-- FilePond replaced with custom drag-and-drop upload in logbook.blade.php --}}
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>

    <script>
        var simplemde = new SimpleMDE({ element: document.getElementById("description") });
    </script>

    <script>
    $(function(){
        // File upload handled by custom drag-and-drop in logbook.blade.php
    });
    </script>

    {{-- Tab switching logic --}}
    <script>
    $(document).ready(function() {
        // Tab switching
        $('.chronicle-filter').on('click', function() {
            var tab = $(this).data('tab');
            $('.chronicle-filter').removeClass('active');
            $(this).addClass('active');
            $('.chronicle-tab-pane').removeClass('active');
            $('#' + tab).addClass('active');
        });

        // Handle hash-based tab activation (e.g. /logbook/all#My)
        var hash = window.location.hash.replace('#', '');
        if (hash && $('#' + hash).length) {
            $('.chronicle-filter').removeClass('active');
            $('.chronicle-filter[data-tab="' + hash + '"]').addClass('active');
            $('.chronicle-tab-pane').removeClass('active');
            $('#' + hash).addClass('active');
        }
    });
    </script>

    <script>
    $(document).ready(function() {
        if (WalletKey.get() === null) {
            alert("Error: Key is not loaded. Please make sure your key is properly loaded.");
            return;
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        async function doAjax(ajaxurl, args) {
            let result;
            try {
                result = await $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: args
                });
                return result;
            } catch (error) {
                console.error(error);
            }
        }

        $("#saveLogLocalBtn").click(function(event) {
            event.preventDefault();

            // Validate required fields
            if (!$("#title").val().trim()) {
                alert("Title is required");
                return;
            }
            if (!simplemde.value().trim()) {
                alert("Entry content is required");
                return;
            }

            var formData = new FormData()
            var files = window.getUploadedFiles ? window.getUploadedFiles() : [];
            console.log("Publishing with " + files.length + " files");
            files.forEach(function(file) {
                formData.append('filenames[]', file);
            });
            formData.append('address', '<?=$public_address?>')
            formData.append('title', $("#title").val())
            formData.append('entry', simplemde.value())

            // Disable button to prevent double-click
            $("#saveLogLocalBtn").css("opacity", "0.5").css("pointer-events", "none").html('<i class="fa-solid fa-spinner fa-spin"></i> Publishing...');
            $.ajax({
                url:"/api/permapinlog",
                type:"POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    if(data.Hash !== "") {
                        $("#ipfs_path").val(data.Hash);
                        alert("Successfully saved to the planetary file system!");
                        location.href="/logbook/all#My"
                    } else {
                        console.error("No folder hash found in the response.");
                        alert("An error occurred. Please try again.");
                    }
                },
                error: function(errorResponse) {
                    console.error("Publish error:", errorResponse);
                    alert("Failed to save to the planetary file system: " + (errorResponse.responseJSON?.error || "Unknown error"));
                    $("#saveLogLocalBtn").css("opacity", "1").css("pointer-events", "auto").html('<i class="fa-solid fa-satellite-dish"></i> Publish to IPFS');
                }
            });
        });

        $(".notarizemeModalBtn").click(async (e) =>
        {
            const fee = 0.1;
            var publication = $(e.currentTarget).attr('rel');
            $(".modal-document").text(publication);
            $(".transaction-hash-link").text("");

            if ("{{ $balance }}" < fee) {
                $("#submit-notarization").prop("disabled", true)
                $("#modal-message-error").text("Not enough MARS to submit log entry")
                $(".modal-message").show()
                console.log("unable to confirm...")
                return false;
            } else {
                $(".modal-message").hide()
                $(".modal-footer").show();
                console.log("able to confirm..")
                $("#submit-notarization").prop("disabled", false)
                $("#submit-notarization").click(async () => {
                    $("#loading").show()
                    try {
                        cid = publication;
                        message = "LB_"+cid;
                        const io = await sendMARS(1, "<?=$public_address?>");
                        const fee = 0.01
                        const mars_amount = 0.01
                        const total_amount = fee + parseInt(mars_amount)

                        try {
                            const tx = await signMARS(message, mars_amount, io);
                            $(".transaction-hash-link").text("" + tx.tx_hash)
                            $(".transaction-hash-link").attr("href","https://explore.marscoin.org/tx/" + tx.tx_hash)
                            $(".modal-message").show();
                            $(".modal-footer").hide();
                            const data = await doAjax("/api/setfeed", {"type": "LB", "txid": tx.tx_hash, message: $("#modal-document").val(), "embedded_link": "https://ipfs.marscoin.org/ipfs/"+cid, "address": '<?=$public_address?>'});
                        } catch (e) {
                            throw e;
                        }
                    } catch (e) {
                        throw e;
                    }
                })
            }
        })

        var cidToDelete;
        var deleteRow;

        $(".unpinModalBtn").click(async (e) =>
        {
            cidToDelete = $(e.currentTarget).attr('rel');
            deleteRow = $(this).closest('tr');
        })

        $("#confirmDelete").click(async (e) =>
        {
            if (!cidToDelete) {
                console.error('CID missing for deletion.');
                return;
            }

            try {
                const response = await $.ajax({
                    url: '/api/removelog',
                    method: 'POST',
                    data: { cid: cidToDelete },
                    dataType: 'json'
                });

                if (response.error) {
                    $('#confirmDeleteModal').modal('hide');
                    alert('Error deleting publication:' + response.error);
                } else {
                    $('#confirmDeleteModal').modal('hide');
                    console.log('Publication deleted successfully:', response.message);
                    deleteRow.remove();
                    location.reload();
                }
            } catch (error) {
                console.error('AJAX request failed:', error);
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
                const io = await getTxInputsOutputs(sender_address, receiver_address, mars_amount)
                return io
            } catch (e) {
                handleError()
                throw e;
            }
            return null
        }

        const signMARS = async (message, mars_amount, tx_i_o) => {
            const mnemonic = WalletKey.get().trim();
            const sender_address = "<?=$public_address?>".trim()
            const seed = my_bundle.bip39.mnemonicToSeedSync(mnemonic);
            const root = my_bundle.bip32.fromSeed(seed, Marscoin.mainnet)
            const child = root.derivePath("m/44'/2'/0'/0/0");
            const wif = child.toWIF()
            const zubs = zubrinConvert(mars_amount)
            var key = my_bundle.bitcoin.ECPair.fromWIF(wif, Marscoin.mainnet);

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

            for (let i = 0; i < tx_i_o.inputs.length; i++) {
                try{
                    psbt.signInput(i, key);
                } catch (e) {
                    alert("Problem while trying to sign with your key. Please try to reconnect your wallet...");
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

        const handleError = () => {
            console.log("PANIC AN ERROR!!!!!!!!")
        }

        //===============================================================================
        // API CALLS

        const getTxInputsOutputs = async (sender_address, receiver_address, amount) => {
            if (!sender_address || !receiver_address || !amount) {
                throw new Error("Missing inputs for tx hash call...");
            }

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
                const shorthash = response.json()
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

    <script type="text/javascript">
    $(document).ready( function () {
        $('.allentries').DataTable({
            "order": [[4, "desc"]]
        });

        $('.myentries').DataTable({
            "order": [[3, "desc"]]
        });
    });
    </script>

    @livewireScripts
</body>
</html>
