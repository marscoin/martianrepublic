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

  <!-- Google Font: Open Sans -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald:400,300,700">

  <!-- Font Awesome CSS -->
  <link rel="stylesheet" href="/assets/wallet/css/font-awesome.min.css">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="/assets/wallet/css/bootstrap.min.css">

  <!-- App CSS -->
  <link rel="stylesheet" href="/assets/wallet/css/mvpready-admin.css">
  <link rel="stylesheet" href="/assets/wallet/css/mvpready-flat.css">
  <!-- <link href="/assets/wallet/css/custom.css" rel="stylesheet">-->

  <!-- Favicon -->
  <link rel="shortcut icon" href="favicon.ico">

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

 @include('wallet.mainnav', array('active'=>'fund'))

  <div class="content">
   <div class="container">
      <h3 class="content-title"><u>Buy Marscoins</u></h3>
      <br>
      <div class="row">
        <div class="col-sm-12 col-md-10 col-md-offset-1">
          <div class="row">
            <div class="col-sm-4">
              <div class="pricing-plan">
                <div class="pricing-plan-ribbon-current ui-tooltip" title="Current Plan!">
                  <i class="fa fa-check"></i>
                </div>
                <div class="pricing-header">
                  <h3 class="pricing-plan-title">Starter</h3>
                  <p class="pricing-plan-label">Try Marscoins</p>
                </div>

                <div class="pricing-plan-price">
                  <span class="pricing-plan-amount">$10</span> / 1000 MRS
                </div>

                <ul class="pricing-plan-details">
                  <li><strong>Immediately</strong> transfered to your wallet</li>
                   <li><strong>In 30 seconds</strong> using any credit card</li>
                  <li><strong>Once</strong> a day</li>
                  <li><strong>Global</strong> now, on Mars in 2025</li>
                </ul>

                <!--<form action="" method="POST">
                <script
                  src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                  data-key="pk_live_4TI6JTAjOWqt9LZszbCJGHTr"
                  data-amount="2000"
                  data-name="Marscoin"
                  data-description="1000 MARS ($10.00)"
                  data-email="{{$email}}"
                  data-image="/assets/landing/img/logomarscoinwallet.png">
                </script>
                <input type="hidden" name="marsa" value="1000">
              </form>-->

              </div> <!-- /.pricing-plan -->

            </div> <!-- /.col -->


            <div class="col-sm-4">

              <div class="pricing-plan">

                <div class="pricing-header">
                  <h3 class="pricing-plan-title">Basic</h3>
                  <p class="pricing-plan-label">Support Space Colonization</p>
                </div>

                <div class="pricing-plan-price">
                  <span class="pricing-plan-amount">$50</span> / 5000 MRS
                </div>

                <ul class="pricing-plan-details">
                  <li><strong>Immediately</strong> transfered to your wallet</li>
                  <li><strong>In 30 seconds</strong> using any credit card</li>
                  <li><strong>twice</strong> a week</li>
                  <li><strong>Global</strong> now, on Mars in 2025</li>
                </ul>

                <!--<form action="" method="POST">
                <script
                  src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                  data-key="pk_live_4TI6JTAjOWqt9LZszbCJGHTr"
                  data-amount="2000"
                  data-name="Marscoin"
                  data-description="5000 MARS ($50.00)"
                  data-email="{{$email}}"
                  data-image="/assets/landing/img/logomarscoinwallet.png">
                </script>
                <input type="hidden" name="marsa" value="5000">
              </form>-->

              </div> <!-- /.pricing-plan -->

            </div> <!-- /.col -->


            <div class="col-sm-4">
              <div class="pricing-plan">

                <div class="pricing-plan-ribbon-secondary ui-tooltip" title="Best Value!">
                  <i class="fa fa-star"></i>
                </div>

                <div class="pricing-header">
                  <h3 class="pricing-plan-title">Pro</h3>
                  <p class="pricing-plan-label">Help Colonize Mars</p>
                </div>

                <div class="pricing-plan-price">
                  <span class="pricing-plan-amount">$100</span> / 10,000 MRS
                </div>

                <ul class="pricing-plan-details">
                  <li><strong>Immediately</strong> transfered to your wallet</li>
                   <li><strong>In 30 seconds</strong> using any credit card</li>
                  <li><strong>Once</strong> a week</li>
                  <li><strong>Global</strong> now, on Mars in 2025</li>
                </ul>

               <!--<form action="" method="POST">
                <script
                  src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                  data-key="pk_live_4TI6JTAjOWqt9LZszbCJGHTr"
                  data-amount="2000"
                  data-name="Marscoin"
                  data-description="10,000 MARS ($100.00)"
                  data-email="{{$email}}"
                  data-image="/assets/landing/img/logomarscoinwallet.png">
                </script>
                <input type="hidden" name="marsa" value="10000">
              </form>-->

              </div> <!-- /.pricing-plan -->

            </div> <!-- /.col -->

          </div> <!-- /.row -->

        </div> <!-- /.col -->

      </div> <!-- /.row -->


      <h4 class="text-center">
      Need More?
      <span>Connect to a cryptocurrency exchange that will allow you to Buy and Sell as many Marscoin as you need.</span>
      <a href="https://www.cryptopia.co.nz/Exchange/?market=MARS_BTC">Learn More <i class="fa fa-external-link"></i></a>
      </h4>


      <br>
      <br>
      <br>


      <h3 class="content-title"><u>Import Private Key</u></h3>
      <p>Use the form below to import a Marscoin private key.</p>
              <br><br>
              <form action="#" class="form-horizontal">
                <div class="form-group">
                  <label class="col-md-2">Private Key:</label>
                  <div class="col-md-8">
                     <span class="qrcodeicon">
                    <input type="text" id="quick-import-key" name="quick-import-key" class="form-control"  />
                    <span></span>
                    </span>
                  </div> <!-- /.col -->
                </div> <!-- /.form-group -->

                <div class="form-group">
                  <div class="col-md-8 col-md-push-2">
                    <a href="javascript:void(0);" id="quick-import" class="btn btn-success">Import</a>
                  </div> <!-- /.col -->
                </div> <!-- /.form-group -->
              </form>
              <div class="alert alert-success" id="quick-import-response" style="display: none;">
                <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
                Note:<br>
                <strong><span id="quick-import-address"></span></strong>
              </div>





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
<script src="/assets/wallet/js/plugins/flot/jquery.flot.js"></script>
<script src="/assets/wallet/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
<script src="/assets/wallet/js/plugins/flot/jquery.flot.pie.js"></script>
<script src="/assets/wallet/js/plugins/flot/jquery.flot.resize.js"></script>

<!-- App JS -->
<script src="/assets/wallet/js/mvpready-core.js"></script>
<script src="/assets/wallet/js/mvpready-admin.js"></script>

<!-- Plugin JS -->
<script src="/assets/wallet/js/demos/flot/line.js"></script>
<script src="/assets/wallet/js/demos/flot/pie.js"></script>
<script src="/assets/wallet/js/demos/flot/auto.js"></script>


<script>
$( "#quick-import" ).click(function() {

  $.post( "/api/importPK/{{ Auth::user()->email }}", { pk_key: $("#quick-import-key").val()},  function( data ) {
    $( "#quick-import-address" ).html( data );
    $( "#quick-import-response" ).show();
  });

});

</script>

</body>
</html>
