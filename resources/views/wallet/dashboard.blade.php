<html>
<head>
    <title>Martian Republic</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald:400,300,700">
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@700&family=Orbitron:wght@500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/assets/wallet/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/wallet/js/plugins/dataTables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-admin.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-flat.css">
    <link rel="shortcut icon" href="/assets/favicon.ico">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>

<body class=" ">
    <div id="wrapper">
        <header class="navbar navbar-inverse" role="banner">
            <div class="container">
                <div class="navbar-header">
                    @include('wallet.header')
                </div> <!-- /.navbar-header -->
                <nav class="collapse navbar-collapse" role="navigation">
                    @include('wallet.navbarleft')
                    @include('wallet.navbarright')
                </nav>
            </div> <!-- /.container -->
        </header>
        @include('wallet.mainnav', ['active' => 'dashboard'])
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-sm-5">
                        <div class="portlet">
                            <h4 class="portlet-title">
                                <u>Daily Stats</u>
                            </h4>
                            <!--   <div class="alert alert-danger" role="alert">
                Blockchain resync in process ... your balance might not reflect actual balance
              </div>-->
                            @if ($balance >= 5000)
                                <div class="alert alert-danger" role="alert">
                                    Your online wallet balance exceeds 5000 MARS. Please store them safely offline.
                                </div>
                            @endif
                            <div class="portlet-body">
                                <p>Quick overview over your Marscoin account.</p>
                                <hr>
                                <table class="table keyvalue-table">
                                    <tbody>
                                        <tr>
                                            <td class="kv-key"><i title="{{ $public_addr }}"
                                                    class="fa fa-money kv-icon kv-icon-primary"></i> Balance</td>
                                            <td class="kv-value">♂ <span
                                                    id="balance">{{ number_format($balance ?? '', 4) }}</span> MARS
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="kv-key"><i
                                                    class="fa fa-angle-double-right kv-icon kv-icon-secondary"></i>
                                                Received</td>
                                            <td class="kv-value">♂ <span
                                                    id="received">{{ number_format($received, 4) }}</span> MARS</td>
                                        </tr>
                                        <tr>
                                            <td class="kv-key"><i
                                                    class="fa fa-angle-double-left kv-icon kv-icon-tertiary"></i>Sent
                                            </td>
                                            <td class="kv-value">♂ <span
                                                    id="sent">{{ number_format($sent, 4) }}</span> MARS</td>
                                        </tr>
                                        <tr>
                                            <td class="kv-key"><a href="/forum"><i class="fa  fa-wechat kv-icon kv-icon-default"></i> Forum Recently</a></td>
                                            <td class="kv-value">{{$forum_count}}</td>
                                        </tr>
                                        <tr>
                                            <td class="kv-key"><a href="/congress/voting"><i class="fa fa-bank kv-icon kv-icon-default"></i> Open Proposals</a></td>
                                            <td class="kv-value">{{$proposal_count}}</td>
                                        </tr>
                                        <tr>
                                            <td class="kv-key"><a href="/citizen/all"><i class="fa  fa-universal-access kv-icon kv-icon-default"></i> Citizen Status</a></td>
                                            <td class="kv-value">{{$citizen_status}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div> <!-- /.portlet-body -->
                        </div> <!-- /.portlet -->
                    </div> <!-- /.col -->
                    <div class="col-md-8 col-sm-7">
                        <div class="portlet">

                            @if ($wallet_open)



                                <h4 class="portlet-title">
                                    <u>Transactions By Month</u>
                                </h4>

                                <p>Quick overview over your Marscoin wallet transactions by type over time.</p>
                                <hr>

                                <div class="portlet-body">
                                <!-- <canvas id="balanceChart" style="width: 100%; height: 260px;"></canvas> -->

                                <div id="chart"></div>
                                </div> <!-- /.portlet-body -->
                            @else
                                @if ($has_civic_wallet || $has_wallet)
                                    <div>
                                        <h4 class="portlet-title">
                                            <u>Open Marscoin Wallet</u>
                                        </h4>

                                        <div
                                            style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                            <h3>Connect to wallet 🚀</h3>
                                            <a id="" type="button" class="btn-lg btn-primary "
                                                href="/wallet/dashboard/hd">Connect
                                                Wallet</a>
                                        </div>
                                    </div>
                                @else
                                    <div class="wallet-is-not-open">
                                        <h4 class="portlet-title">
                                            <u>Open Marscoin Wallet</u>
                                        </h4>

                                        <div
                                            style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                            <h3>Open your first Marscoin wallet today! 🚀</h3>
                                            <a id="" type="button" class="btn-lg btn-primary "
                                                href="/wallet/dashboard/hd">Open
                                                Wallet</a>
                                        </div>
                                    </div>
                                @endif


                            @endif


                        </div> <!-- /.portlet -->
                    </div> <!-- /.col -->
                </div> <!-- /.row -->

                <div class="row">

                    @if ($wallet_open)
                        <div class="col-md-12">
                            <div class="portlet">
                                <div class="wallet-is-open">
                                    <h4 class="portlet-title">
                                        <u>Wallet Transactions</u>
                                    </h4>
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

                                </div>
                            </div>

                        </div>
                    @endif


                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="portlet">
                            <h4 class="portlet-title">
                                <u>Your balance / MARS Circulation</u>
                            </h4>
                            <div class="portlet-body">
                                <div id="pie-chart" class="chart-holder-250"></div>
                            </div> <!-- /.portlet-body -->
                        </div> <!-- /.portlet -->
                    </div> <!-- /.col -->
                    <div class="col-md-5">
                        <div class="portlet">
                            <h4 class="portlet-title">
                                <u>Crypto News</u>
                            </h4>
                            <div class="portlet-body">

                                <a class="twitter-timeline" height="250" href="https://twitter.com/marscoinorg"
                                    data-chrome="nofooter transparent noscrollbar"
                                    data-widget-id="492843006043516928">Tweets by @marscoinorg</a>
                                <script>
                                    ! function(d, s, id) {
                                        var js, fjs = d.getElementsByTagName(s)[0],
                                            p = /^http:/.test(d.location) ? 'http' : 'https';
                                        if (!d.getElementById(id)) {
                                            js = d.createElement(s);
                                            js.id = id;
                                            js.src = p + "://platform.twitter.com/widgets.js";
                                            fjs.parentNode.insertBefore(js, fjs);
                                        }
                                    }(document, "script", "twitter-wjs");
                                </script>



                            </div> <!-- /.portlet-body -->
                        </div> <!-- /.portlet -->
                    </div> <!-- /.col -->
                    <div class="col-md-4">
                        <div class="portlet">
                            <h4 class="portlet-title">
                                <u>Price Chart</u>
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
    <footer class="footer">
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
    <script>

        $(document).ready(function() {
            var mars_price = '{{ $mars_price }}';
            var lastdate = 10000000000;
            var firstdate = 0;
            var d1 = [],
                d2 = [];

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var data, chartOptions

            data = [{
                    label: "Global Marscoin ({{ number_format($coincount) }}) MARS",
                    data: Math.floor({{ $coincount }})
                },
                {
                    label: "Your holdings ({{ number_format($balance) }}) MARS",
                    data: Math.floor({{ $balance }})
                }
            ]

            chartOptions = {
                series: {
                    pie: {
                        show: true,
                        innerRadius: 0,
                        stroke: {
                            width: 4
                        }
                    }
                },
                legend: {
                    show: false,
                    position: 'ne'
                },
                tooltip: true,
                tooltipOpts: {
                    content: '%s: %y'
                },
                grid: {
                    hoverable: true
                },
                colors: mvpready_core.layoutColors
            }

            var holder = $('#pie-chart')

            if (holder.length) {
                $.plot(holder, data, chartOptions)
            }



            $.post("/api/getTransactions", {
                "address": '<?= $public_addr ?>'
            }, function(data) {
                if (data) {
                    var txs = data.txs;
                    var balance = 0; // Initialize running balance
                    var balanceData = []; // This will hold our balance over time

                    $.each(txs, function(i, item) {
                        if (firstdate < item.time)
                            firstdate = item.time;
                        if (lastdate > item.time)
                            lastdate = item.time;



                        if (item.vout[0].scriptPubKey.type == "nulldata") {
                            var dataRowContent = "<tr><td>" + format_time(item.time) + "</td><td>" +
                                dectag(hexToAscii(item.vout[0].scriptPubKey.asm.split("OP_RETURN ")[
                                    1])) + " " + ipfsfy(hexToAscii(item.vout[0].scriptPubKey.asm
                                    .split("OP_RETURN ")[1])) +
                                "</td><td><span style='color: red'>" + Number.parseFloat(item.fees)
                                .toFixed(8) + "</span></td><td>$" + (Number.parseFloat(mars_price)
                                    .toFixed(4) * Number.parseFloat(item.fees)).toFixed(8) +
                                "</td><td><a href='http://explore.marscoin.org/tx/" + item.txid +
                                "' target='_blank'>" + item.txid.substring(1, 18) +
                                "...</a></td></tr>";

                            $("#table-2 tbody").append(dataRowContent);
                        } else {
                            c = "";
                            if (item.vout[0].scriptPubKey.addresses[0] == '{{ $public_addr }}') {
                                c = "green";
                                var value = Number.parseFloat(item.vout[0].value);
                                balance += value; 
                                var time = Math.round(item.time * 1000);
                                balanceData.push({x: time, y: balance});
                            } else {
                                c = "red";
                                var value = Number.parseFloat(item.vout[0].value);
                                balance -= value; 
                                balanceData.push({x: time, y: balance});
                            }
                            var newRowContent = "<tr><td>" + format_time(item.time) +
                                "</td><td><a target='_blank' href='http://explore.marscoin.org/address/" +
                                item.vout[0].scriptPubKey.addresses[0] + "' >" + item.vout[0]
                                .scriptPubKey.addresses[0] +
                                "</a></td><td><b><span style='color: " + c + "'>" + Number
                                .parseFloat(item.vout[0].value).toFixed(4) +
                                "</span></b></td><td>$" + (Number.parseFloat(mars_price).toFixed(
                                    4) * Number.parseFloat(item.vout[0].value).toFixed(2)).toFixed(
                                    8) + "</td><td><a href='http://explore.marscoin.org/tx/" + item
                                .txid + "' target='_blank'>" + item.txid.substring(1, 18) +
                                "...</a></td></tr>";

                            $("#table-2 tbody").append(newRowContent);

                            
                        }
                    });
                    firstdate = firstdate - (60 * 60 * 24 * 30);
                    lastdate = lastdate + (60 * 60 * 24 * 30);
                    firstdate = firstdate * 1000;
                    lastdate = lastdate * 1000;
                    console.log("Time:")
                    console.log(firstdate)
                    console.log(lastdate)

                    console.log(d1);
                    console.log(d2);

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

                        // Your cleaned and sorted balanceData array
                    let cleanedBalanceData = balanceData.filter(point => point.x && point.y);
                    cleanedBalanceData.sort((a, b) => a.x - b.x);

                    // Transform data for ApexCharts
                    let seriesData = cleanedBalanceData.map(point => {
                    return { x: new Date(point.x), y: point.y };
                    });

                    let maxYValue = Math.max(...cleanedBalanceData.map(point => point.y));

                    // ApexCharts options
                    var options = {
                    series: [{
                        name: 'Balance',
                        data: seriesData
                    }],
                    chart: {
                        type: 'line',
                        height: 350,
                        zoom: {
                        enabled: false
                        },
                        toolbar: {
                        show: false
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'smooth'
                    },
                    xaxis: {
                        type: 'datetime'
                    },
                    tooltip: {
                        x: {
                        format: 'dd MMM yyyy'
                        }
                    },
                    yaxis: {
                            min: 0,
                            max: maxYValue ,
                            labels: {
                            formatter: function (value) {
                                return value.toFixed(2); // Rounds the label to two decimal places
                            }
                            },
                        }
                    };

                    // Initialize ApexCharts
                    var chart = new ApexCharts(document.querySelector("#chart"), options);
                    chart.render();



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
</body>

</html>
