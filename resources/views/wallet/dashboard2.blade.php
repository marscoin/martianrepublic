<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head>
  <title>Marscoin Wallet</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald:400,300,700">
  <link rel="stylesheet" href="/assets/wallet/css/font-awesome.min.css">
  <link rel="stylesheet" href="/assets/wallet/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/wallet/css/mvpready-admin.css">
  <link rel="stylesheet" href="/assets/wallet/css/mvpready-flat.css">
  <link rel="shortcut icon" href="favicon.ico">
</head>
<body class=" ">
<div id="wrapper">
  <header class="navbar navbar-inverse" role="banner">
    <div class="container">
      <div class="navbar-header">
        <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only">Toggle navigation</span>
          <i class="fa fa-cog"></i>
        </button>
        <a href="/wallet/dashboard" class="navbar-brand navbar-brand-img">
          <img style="width: 67px;" src="/assets/landing/img/logomarscoinwallet.png" alt="MVP Ready">
        Marscoin Wallet
        </a>
      </div> <!-- /.navbar-header -->
      <nav class="collapse navbar-collapse" role="navigation">
         @include('wallet.navbarleft')
         @include('wallet.navbarright')
      </nav>
    </div> <!-- /.container -->
  </header>
 @include('wallet.mainnav', array('active'=>'dashboard'))
  <div class="content">
    <div class="container">
        <div class="row">
          <div class="col-md-4 col-sm-5">
            <div class="portlet">
              <h4 class="portlet-title">
                <u>Daily Stats</u>
              </h4>
              <div class="portlet-body">
                <p>Quick overview over your Marscoin account. For detailed statistics <a>click here</a></p>
                <hr>
                <table class="table keyvalue-table">
                  <tbody>
                    <tr>
                      <td class="kv-key"><i title="{{$address}}" class="fa fa-money kv-icon kv-icon-primary"></i> Balance</td>
                      <td class="kv-value">♂ <span id="balance">{{$balance}}</span> MARS</td>
                    </tr>
                    <tr>
                      <td class="kv-key"><i class="fa fa-angle-double-right kv-icon kv-icon-secondary"></i> Received</td>
                      <td class="kv-value">♂ <span id="received">{{$received}}</span> MARS</td>
                    </tr>
                    <tr>
                      <td class="kv-key"><i class="fa fa-angle-double-left kv-icon kv-icon-tertiary"></i>Sent</td>
                      <td class="kv-value">♂ <span id="sent">{{$sent}}</span> MARS</td>
                    </tr>
                    <tr>
                      <td class="kv-key"><i class="fa fa-envelope-o kv-icon kv-icon-default"></i> Messages</td>
                      <td class="kv-value">39 </td>
                    </tr>
                  </tbody>
                </table>
              </div> <!-- /.portlet-body -->
            </div> <!-- /.portlet -->
          </div> <!-- /.col -->
          <div class="col-md-8 col-sm-7">
            <div class="portlet">
              <h4 class="portlet-title">
                <u>Income vs Expenses</u>
              </h4>
              <div class="portlet-body">
                <div id="line-chart" class="chart-holder-300"></div>
              </div> <!-- /.portlet-body -->
            </div> <!-- /.portlet -->
          </div> <!-- /.col -->
        </div> <!-- /.row -->
        <div class="row">
            <div class="col-md-3">
              <div class="portlet">
                <h4 class="portlet-title">
                  <u>Marscoin economy</u>
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
                  
                <a class="twitter-timeline" height="250" href="https://twitter.com/marscoinorg" data-chrome="nofooter transparent noscrollbar" data-widget-id="492843006043516928">Tweets by @marscoinorg</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>



                </div> <!-- /.portlet-body -->
              </div> <!-- /.portlet -->
          </div> <!-- /.col -->
          <div class="col-md-4">
            <div class="portlet">
              <h4 class="portlet-title">
                <u>Price Chart</u>
              </h4>
              <div class="portlet-body">
                <div id="auto-chart" class="chart-holder-200"></div>
              </div> <!-- /.portlet-body -->
            </div> <!-- /.portlet -->
          </div> <!-- /.col -->
        </div> <!-- /.row -->
    </div> <!-- /.container -->
  </div> <!-- .content -->
</div> <!-- /#wrapper -->
<footer class="footer">
  <div class="container">
    <p class="pull-left">Copyright &copy; 2014-<?=date('Y')?> The Marscoin Foundation, Inc.</p>
    <p class="pull-right">Marscoin Wallet v.1.5.120</p>
  </div>
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
<script src="/assets/wallet/js/demos/flot/auto.js"></script>
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
  error: function (XMLHttpRequest, textStatus, errorThrown) {
  window.setTimeout(updateBalance, 100000);
  }
  })};

  $(function () {

  var data, chartOptions

  data = [
    { label: "Global Marscoin ({{$coincount}})", data: Math.floor ({{$coincount}}) }, 
    { label: "Your holdings ({{$balance}})", data: Math.floor ({{$balance}}) }
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
    $.plot(holder, data, chartOptions )
  }


})

@if (count($transactions) > 0)
$(function () {

  var d1, d2, data, chartOptions

  d1 = [
  @foreach ($incomes as $transaction)
        [ {{$transaction['time']}}, {{$transaction['amount']}}],
  @endforeach

  ]

  d2 = [
 @foreach ($expenses as $transaction)
        [ {{$transaction['time']}}, {{$transaction['amount']}}], 
  @endforeach

  ]

  data = [{
    label: "Total Income",
    data: d1
  }, {
    label: "Total Expenses",
    data: d2
  }]

  chartOptions = {
    xaxis: {
      min: {{$firstdate}},
      max: {{$lastdate}},
      mode: "time",
      minTickSize: [2, "day"],
      tickLength: 0
    },
    yaxis: {

    },
    series: {
      lines: {
        show: true,
        fill: false,
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
    colors: mvpready_core.layoutColors
  }

  var holder = $('#line-chart')

  if (holder.length) {
    $.plot(holder, data, chartOptions )
  }

})
@endif
</script>
</body>
</html>