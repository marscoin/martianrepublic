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
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-admin.css">
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

                <div class="row" style="margin-top: 24px;">
                    <div class="col-md-4 col-sm-5 fade-in-1">
                        <div class="section-label">Account Overview</div>
                        <div class="dash-card">
                            @livewire('dashboard-stats')
                        </div>
                    </div>
                    <div class="col-md-8 col-sm-7 fade-in-2">
                        <div class="section-label">Activity</div>
                        <div class="dash-card">

                            @if ($wallet_open)



                                <h4 class="portlet-title">
                                    <u>Transactions By Month</u>
                                </h4>

                                <p>Quick overview over your Marscoin wallet transactions by type over time.</p>
                                <hr>

                                <div class="portlet-body">
                                
                                <div id="chart"></div>
                                </div> 
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
                                    <div class="wallet-is-not-open" style="padding: 30px 20px;">
                                        <h4 class="portlet-title"><u>Welcome to the Martian Republic</u></h4>
                                        <p style="color: var(--mr-text-secondary, #8a8998); margin-bottom: 24px;">Complete these steps to get started as a Martian citizen.</p>

                                        <div style="display: flex; flex-direction: column; gap: 16px; max-width: 480px;">
                                            <div style="display: flex; align-items: center; gap: 14px; padding: 14px 18px; background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.2); border-radius: 8px;">
                                                <i class="fa fa-check-circle" style="color: #34d399; font-size: 22px;"></i>
                                                <div>
                                                    <strong style="color: #34d399;">Account Created</strong>
                                                    <div style="color: var(--mr-text-secondary, #8a8998); font-size: 13px;">Your Martian Republic account is active</div>
                                                </div>
                                            </div>

                                            <div style="display: flex; align-items: center; gap: 14px; padding: 14px 18px; background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.2); border-radius: 8px;">
                                                <i class="fa fa-check-circle" style="color: #34d399; font-size: 22px;"></i>
                                                <div>
                                                    <strong style="color: #34d399;">2FA Secured</strong>
                                                    <div style="color: var(--mr-text-secondary, #8a8998); font-size: 13px;">Two-factor authentication is protecting your account</div>
                                                </div>
                                            </div>

                                            <div style="display: flex; align-items: center; gap: 14px; padding: 14px 18px; background: rgba(0,228,255,0.08); border: 1px solid rgba(0,228,255,0.25); border-radius: 8px;">
                                                <i class="fa fa-arrow-circle-right" style="color: var(--mr-cyan, #00e4ff); font-size: 22px;"></i>
                                                <div style="flex: 1;">
                                                    <strong style="color: var(--mr-cyan, #00e4ff);">Create Your Wallet</strong>
                                                    <div style="color: var(--mr-text-secondary, #8a8998); font-size: 13px;">Set up your Marscoin wallet to participate in the Republic</div>
                                                </div>
                                                <a href="/wallet/dashboard/hd" class="btn btn-primary" style="white-space: nowrap;">Create Wallet</a>
                                            </div>

                                            <div style="display: flex; align-items: center; gap: 14px; padding: 14px 18px; background: rgba(255,255,255,0.03); border: 1px solid var(--mr-border, rgba(255,255,255,0.08)); border-radius: 8px; opacity: 0.5;">
                                                <i class="fa fa-circle-o" style="color: var(--mr-text-secondary, #8a8998); font-size: 22px;"></i>
                                                <div>
                                                    <strong>Register as Citizen</strong>
                                                    <div style="color: var(--mr-text-secondary, #8a8998); font-size: 13px;">Submit your identity to join the Martian citizenry</div>
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
                        <div class="dash-card">
                            @livewire('hodler-stats')
                        </div>
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
                        <div class="dash-card">
                            <h4 class="portlet-title">
                                Price Chart
                            </h4>
                            <div class="portlet-body">
                                <script src="https://widgets.coingecko.com/coingecko-coin-price-chart-widget.js"></script>
                                <coingecko-coin-price-chart-widget coin-id="marscoin" currency="usd" height="300"
                                    locale="en"></coingecko-coin-price-chart-widget>
                            </div> <!-- /.portlet-body -->
                        </div> <!-- /.portlet -->
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

        // Calculate the value received (vout)
        tx.vout.forEach((vout) => {
            if (vout.scriptPubKey.addresses && vout.scriptPubKey.addresses.includes(address)) {
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

                        // Calculate the value received (vout)
                        tx.vout.forEach((vout) => 
                        {
                            if (vout.scriptPubKey.addresses && vout.scriptPubKey.addresses.includes(address)) {
                                valueReceived += parseFloat(vout.value);
                            }else if (vout.scriptPubKey.addresses){
                                recipient = vout.scriptPubKey.addresses[0];
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

                    // Clean and sort the balanceData by time (x)
                    let cleanedBalanceData = balanceData.filter(point => point.x && !isNaN(point.y)).sort((a, b) => a.x - b.x);

                    // Map the sorted and updated balanceData for charting
                    let seriesData = cleanedBalanceData.map(point => {
                        return { x: new Date(point.x), y: point.y };
                    });

                    //console.log(seriesData);

                    let maxYValue = Math.max(...cleanedBalanceData.map(point => point.y));

                    // ApexCharts options
                    var options = {

                    series: [{
                        name: 'Balance',
                        data: seriesData,
                        color: '#c84125',
                    }],
                    chart: {
                        type: 'area',
                        height: 350,
                        zoom: { enabled: false },
                        toolbar: { show: false },
                        foreColor: '#8a8998',
                        background: 'transparent'
                    },
                    dataLabels: { enabled: false },
                    stroke: { curve: 'smooth', width: 2 },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.4,
                            opacityTo: 0.05,
                            stops: [0, 100]
                        }
                    },
                    grid: {
                        borderColor: 'rgba(255,255,255,0.06)',
                        strokeDashArray: 4
                    },
                    xaxis: {
                        type: 'datetime',
                        labels: { style: { colors: '#5a5968' } },
                        axisBorder: { color: 'rgba(255,255,255,0.06)' },
                        axisTicks: { color: 'rgba(255,255,255,0.06)' }
                    },
                    tooltip: {
                        theme: 'dark',
                        x: { format: 'dd MMM yyyy' }
                    },
                    yaxis: {
                        min: 0,
                        max: maxYValue,
                        labels: {
                            style: { colors: '#5a5968' },
                            formatter: function (value) {
                                return value.toFixed(2);
                            }
                        }
                    }
                    };

                    // Initialize ApexCharts
                    var chart = new ApexCharts(document.querySelector("#chart"), options);
                    chart.render();

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
    @livewireScripts
</body>

</html>
