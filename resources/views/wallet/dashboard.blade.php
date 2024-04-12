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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" integrity="sha512-1cK78a1o+ht2JcaW6g8OXYwqpev9+6GqOkz9xmBN9iUUhIndKtxwILGWYOSibOKjLsEdjyjZvYDq/cZwNeak0w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @livewireStyles
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
                            @livewire('dashboard-stats')
                        </div> 
                    </div> 
                    <div class="col-md-8 col-sm-7">
                        <div class="portlet">

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
                                    <div>
                                        <h4 class="portlet-title">
                                            <u>Open Marscoin Wallet</u>
                                        </h4>

                                        <div
                                            style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                            <h3>Connect your wallet 🚀</h3>
                                            <a data-aos="fade-up" data-aos-duration="1000" id="" type="button" class="btn-lg btn-primary "
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

                            @livewire('hodler-stats')

                            
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
                    var mars_price = parseFloat(data.mars_price);
                    console.log("Mars price: ", mars_price);
                    $('.connectivity').hide();
                },
                error: function() {
                    console.log('Error fetching Mars price');
                    $('.connectivity').show();
                }
            });

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
                                4) * Number.parseFloat(totalTxValue).toFixed(2)).toFixed(
                                8) + "</td><td><a href='http://explore.marscoin.org/tx/" + tx
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
                        color: '#d74b4b',
                    }],
                    chart: {
                        type: 'area',
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
    @livewireScripts
</body>

</html>
