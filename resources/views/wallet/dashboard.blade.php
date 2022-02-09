<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->

<head>
    <title>Marscoin Wallet</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Google Font: Open Sans -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald:400,300,700">
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@700&family=Orbitron:wght@500&display=swap" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="/assets/wallet/css/font-awesome.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/assets/wallet/css/bootstrap.min.css">
    <!-- App CSS -->
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-admin.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-flat.css">
    <!-- <link href="/assets/wallet/css/custom.css" rel="stylesheet">-->
    <!-- Favicon -->
    <link rel="shortcut icon" href="/assets/favicon.ico">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
  <![endif]-->
</head>

<body class=" ">
<div id="wrapper">
  <header class="navbar navbar-inverse" role="banner">
    <div class="container">
      <div class="navbar-header">
        @include('wallet.header')
      </div> <!-- /.navbar-header -->
      <nav class="collapse navbar-collapse" role="navigation">
         @include('wallet.navbarleft', array('info' => $network ))
         @include('wallet.navbarright')
      </nav>
    </div> <!-- /.container -->
  </header>
 @include('wallet.mainnav', array('active'=>'dashboard', 'info'=>$network))
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
                                            <td class="kv-key"><i title="{{ $address }}"
                                                    class="fa fa-money kv-icon kv-icon-primary"></i> Balance</td>
                                            <td class="kv-value">♂ <span
                                                    id="balance">{{ number_format($balance, 2) }}</span> MARS</td>
                                        </tr>
                                        <tr>
                                            <td class="kv-key"><i
                                                    class="fa fa-angle-double-right kv-icon kv-icon-secondary"></i>
                                                Received</td>
                                            <td class="kv-value">♂ <span
                                                    id="received">{{ number_format($received, 2) }}</span> MARS</td>
                                        </tr>
                                        <tr>
                                            <td class="kv-key"><i
                                                    class="fa fa-angle-double-left kv-icon kv-icon-tertiary"></i>Sent
                                            </td>
                                            <td class="kv-value">♂ <span
                                                    id="sent">{{ number_format($sent, 2) }}</span> MARS</td>
                                        </tr>
                                        <tr>
                                            <td class="kv-key"><i
                                                    class="fa fa-envelope-o kv-icon kv-icon-default"></i> Messages</td>
                                            <td class="kv-value">0</td>
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
                                    <u>Account Interactions</u>
                                </h4>



                                <table class="table table-striped table-bordered dataTable" id="table-1"
                                    aria-describedby="table-1_info">
                                    <thead>
                                        <tr role="row">
                                            <th style="width: 250px;" class="sorting_asc" role="columnheader"
                                                tabindex="0" aria-controls="table-1" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Browser: activate to sort column descending">
                                                Transaction
                                            </th>

                                            <th style="width: 250px;" class="text-center sorting" role="columnheader"
                                                tabindex="0" aria-controls="table-1" rowspan="1" colspan="1"
                                                aria-label="CSS grade: activate to sort column ascending">
                                                Recipient
                                                Address</th>
                                            <th style="width: 110px;" class="sorting" role="columnheader"
                                                tabindex="0" aria-controls="table-1" rowspan="1" colspan="1"
                                                aria-label="Platform(s): activate to sort column ascending">
                                                MARS
                                            </th>
                                            <th style="width: 110px;" class="text-center sorting" role="columnheader"
                                                tabindex="0" aria-controls="table-1" rowspan="1" colspan="1"
                                                aria-label="Engine version: activate to sort column ascending">
                                                USD</th>
                                            <th style="width: 110px;" class="sorting" role="columnheader"
                                                tabindex="0" aria-controls="table-1" rowspan="1" colspan="1"
                                                aria-label="Rendering engine: activate to sort column ascending">
                                                Date</th>


                                        </tr>
                                    </thead>


                                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                                        <tr class="odd">
                                            <td valign="top" colspan="5" class="dataTables_empty">
                                                No transaction data...</td>
                                        </tr>
                                    </tbody>
                                </table>




                            @else


                                <h4 class="portlet-title">
                                    <u>Open Marscoin Wallet</u>
                                </h4>

                                <div
                                    style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                    <h3>Open your first Marscoin wallet today! 🚀</h3>
                                    <a id="" type="button" class="btn-lg btn-primary " href="/wallet/dashboard/hd">Open
                                        Wallet</a>
                                </div>
                            @endif

                            <div class="portlet-body">
                                <div id="area-chart" class="chart-holder-300" style="min-width: 300px"></div>
                            </div> <!-- /.portlet-body -->
                        </div> <!-- /.portlet -->
                    </div> <!-- /.col -->
                </div> <!-- /.row -->
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
    <!-- Bootstrap core JavaScript
================================================== -->
    <!-- Core JS -->
    <script src="/assets/wallet/js/libs/jquery-1.10.2.min.js"></script>
    <script src="/assets/wallet/js/libs/bootstrap.min.js"></script>
    <!--[if lt IE 9]>
<script src="/assets/wallet/js/libs/excanvas.compiled.js"></script>
<![endif]-->
    <!-- Plugin JS -->
    <script src="/assets/wallet/js/plugins/flot/jquery.flot.js"></script>
    <script src="/assets/wallet/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="/assets/wallet/js/plugins/flot/jquery.flot.pie.js"></script>
    <script src="/assets/wallet/js/plugins/flot/jquery.flot.resize.js"></script>
    <!-- App JS -->
    <script src="/assets/wallet/js/mvpready-core.js"></script>
    <script src="/assets/wallet/js/mvpready-admin.js"></script>
    <!-- Plugin JS -->
    @if (count($transactions) <= 0)
        <script src="/assets/wallet/js/demos/flot/line.js"></script>
    @endif
    <script>
        updateBalance();

        function updateBalance() {
            jQuery.ajax({
                type: 'GET',
                url: '/api/balance/{{ Auth::user()->email }}',
                timeout: 100000,
                success: function(data) {
                    jQuery("#balance").fadeIn('slow', function() {
                        jQuery('#balance').text(data);
                    });

                    window.setTimeout(updateBalance, 100000);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    window.setTimeout(updateBalance, 100000);
                }
            })
        };

        $(function() {

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


        })

        @if (count($transactions) > 0)
            $(function () {
        
            var data, chartOptions
        
            d2 = [
            @foreach ($balances as $transaction)
                [ {{ $transaction['time'] }}, {{ $transaction['amount'] }}],
            @endforeach
        
            ]
        
            data = [{
            label: "Your balance",
            data: d2
            }]
        
            chartOptions = {
            xaxis: {
            min: {{ $firstdate }},
            max: {{ $lastdate }},
            mode: "time",
            minTickSize: [2, "day"],
            tickLength: 0
            },
            yaxis: {
        
            },
            series: {
            lines: {
            show: true,
            fill: true,
            lineWidth: 3
            },
            points: {
            show: true,
            radius: 3,
            fill: true,
            fillColor: "#ffffff",
            lineWidth: 2
            }
            },
            grid: {
            hoverable: true,
            clickable: false,
            borderWidth: 0
            },
            legend: {
            show: true
            },
            tooltip: true,
            tooltipOpts: {
            content: '%s: %y'
            },
            colors: ['#5cb85c', '#D74B4B', '#475F77', '#BCBCBC', '#777777', '#6685a4', '#E68E8E']
            }
        
            var holder = $('#area-chart')
        
            if (holder.length) {
            $.plot(holder, data, chartOptions)
            }
        
            })
        @endif

        @if ($balance == 0 && $voucher == false)
            $("#redeem").click(function(event){
            event.preventDefault();
            $.post("/api/redeem",function(data){
            alert(data);
            });
            });
        @endif
    </script>
</body>

</html>
