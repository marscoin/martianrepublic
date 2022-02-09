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
 
      </nav>
    </div> <!-- /.container -->
  </header>
 @include('wallet.recoverynav', array('active'=>'dashboard'))
  <div class="content">
    <div class="container">
        <div class="row">
          <div class="col-md-6 col-sm-6">
            <div class="portlet">
              <h4 class="portlet-title">
                <u>Account recovery</u>
              </h4>
              <div class="portlet-body">
                <p>Please enter your offline Marscoin wallet address</p>
                <hr>
                <form class="form account-form" method="POST" action="/wallet/dashboard">
                  <div class="form-group" style="text-align: center;">
                    <input name="maddress" id="maddress" style="height: 48px; font-size: 22px; font-weight: 700;  text-align: center;" type="text" class="form-control" id="maddress" placeholder="Enter Marscoin address" tabindex="1" autofocus >
                    <button type="submit" class="btn btn-success btn-block btn-lg" tabindex="2">Submit &nbsp; <i class="fa fa-check"></i>
                    </button>
                  </div> 
                </form>
              </div> <!-- /.portlet-body -->
            </div> <!-- /.portlet -->
          </div> <!-- /.col -->
          <div class="col-md-6 col-sm-6">
            <div class="portlet">
              <h4 class="portlet-title">
                <u>Pardon the dust!</u>
              </h4>
              <div class="portlet-body">
                <p>While we work on upgrading our online wallet to include new functionality, please make sure to safely retrieve your funds. Enter your Marscoin Address from an offline / desktop Marscoin wallet. We'll notifiy you when the transfer completed.</p>
                <p>
                  
                </p>
                
              </div> <!-- /.portlet-body -->
            </div> <!-- /.portlet -->
          </div>
          
        </div> <!-- /.row -->
      
    </div> <!-- /.container -->
  </div> <!-- .content -->
</div> <!-- /#wrapper -->
<footer class="footer">
  <div class="container">
    <p class="pull-left">Copyright &copy; 2014-<?=date('Y')?> The Marscoin Foundation, Inc.</p>
    <p class="pull-right">Marscoin Wallet v.1.6</p>
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

</body>
</html>