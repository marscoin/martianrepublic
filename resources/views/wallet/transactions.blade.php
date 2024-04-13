<html lang="en" class="no-js">
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
  <!-- <link href="/assets/wallet/css/custom.css" rel="stylesheet">-->
  <link rel="shortcut icon" href="favicon.ico">
 <style>
 span.qrcodeicon span {
position: absolute;
display: block;
top: 7px;
right: 21px;
width: 18px;
height: 18px;
background: url('/assets/wallet/img/qrcode.png');
cursor: pointer;
z-index: 1;
}
 </style>
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
        <a href="./" class="navbar-brand navbar-brand-img">
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
   @include('wallet.mainnav', array('active'=>'transactions'))

  <div class="content">

    <div class="container">

      <div class="portlet">

        <h3 class="portlet-title">
          <u>My Transactions</u>
        </h3>

        <div class="portlet-body">

          <table class="table table-striped table-bordered" id="table-1">
            <thead>
              <tr>
                <th style="width: 30%">To/From Address</th>
                <th style="width: 20%">Amount</th>
                <th style="width: 18%">Confirmations</th>
                <th style="width: 20%">Transaction Id</th>
                <th style="width: 12%">Timestamp</th>
              </tr>
            </thead>
@foreach ($transactions as $transaction)
    <tr>
      @if ($transaction['amount'] < 0)
      <td style="color: red;"><i class="fa fa-angle-double-left"></i> {{$transaction['address']}}</td>
      @else
       <td style="color: green;"><i class="fa fa-angle-double-right"></i> {{$transaction['address']}}</td>
      @endif
      @if ($transaction['amount'] < 0)
      <td style="color: red;">{{$transaction['amount']}} MARS</td>
      @else
      <td style="color: green;">{{$transaction['amount']}} MARS</td>
      @endif
      <td>{{$transaction['confirmations']}}</td>
      <td><a target="_blank" href="http://explore.marscoin.org/tx/{{$transaction['txid']}}">{{Str::limit($transaction['txid'],15)}}</a></td>
      <td>{{AppHelper::ago($transaction['time'])}}</td>
    </tr>
@endforeach
            <tfoot>
              <tr>
                <th>Address</th>
                <th>Amount</th>
                <th>Confirmations</th>
                <th>Transaction Id</th>
                <th>Timestamp</th>
              </tr>
            </tfoot>
          </table>

        </div> <!-- /.portlet-body -->

      </div> <!-- /.portlet -->

    </div> <!-- /.container -->

  </div> <!-- .content -->

</div> <!-- /#wrapper -->

<footer class="footer">
  <div class="container">
    <p class="pull-left">Copyright &copy; 2014 The Marscoin Foundation, Inc.</p>
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

<script src="/assets/wallet/js/plugins/dataTables/jquery.dataTables.js"></script>
<script src="/assets/wallet/js/plugins/dataTables/dataTables.bootstrap.js"></script>

<!-- App JS -->
<script src="/assets/wallet/js/mvpready-core.js"></script>
<script src="/assets/wallet/js/mvpready-admin.js"></script>


</body>
</html>