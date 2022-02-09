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
  <link rel="shortcut icon" href="/favicon.ico">

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

        <div class="layout layout-stack-sm layout-main-left">

          <div class="col-sm-4 col-sm-push-8 layout-sidebar">

            <div class="well text-center">
              <p><i class="fa fa-question-circle fa-5x text-muted"></i></p>
              <h4>Have a Question?</h4>
              <p></p>
             <a target="_blank" href="https://marscoin.atlassian.net/servicedesk/customer/portal/1" class="btn btn-secondary">Get it Answered!</a>
            </div> <!-- /.well -->

            <br>

            <div class="portlet">

              <h4>Categories</h4>

              <div class="list-group">

                <a target="_blank" href="https://marscoin.atlassian.net/servicedesk/customer/portal/1/group/1/create/1" class="list-group-item">
                Technical Support
                <span class="badge badge-primary"></span>
                </a>  

                <a target="_blank" href="https://marscoin.atlassian.net/servicedesk/customer/portal/1/group/1/create/6" class="list-group-item">
                Bug Report
                <span class="badge badge-primary"></span>
                </a>  

               
                <a href="https://gitter.im/marscoin-dev/community?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge" class="list-group-item">
                  Join our Dev team
                  <img src="https://badges.gitter.im/marscoin-dev/community.svg"></a>
             

            
            </div> <!-- /.list-group -->

          </div> <!-- /.portlet -->

        </div> <!-- /.layout-sidebar -->



        <div id="faq-questions" class="col-sm-8 col-sm-pull-4 layout-main">

          <h2 class="">Planned Features / Milestones</h2>

          <br><br>

          <h4 class="content-title"><u>Features</u></h4>

          <div id="accordion-help" class="panel-group accordion-simple">

          
            <div class="panel">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-help" href="#faq-general-2"><i class="fa faq-accordion-caret"></i>Improved dashboard charts </a>
                </h4>
              </div><!-- .panel-heading -->

              <div id="faq-general-2" class="panel-collapse collapse">
                <div class="panel-body">
                <p>need to fix / improve the current display of income, expense and balance.</p>
                </div><!-- .panel-body -->
              </div> <!-- ./panel-collapse -->
            </div><!-- .panel -->

            <div class="panel">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-help" href="#faq-general-3"><i class="fa faq-accordion-caret"></i>export to paper wallet </a>
                </h4>
              </div><!-- .panel-heading -->

              <div id="faq-general-3" class="panel-collapse collapse">
                <div class="panel-body">
                <p>allowing users to export paper wallets from within their accounts</p>
                </div><!-- .panel-body -->
              </div> <!-- ./panel-collapse -->
            </div><!-- .panel -->

            <div class="panel">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-help" href="#faq-general-4"><i class="fa faq-accordion-caret"></i>further milestones </a>
                </h4>
              </div><!-- .panel-heading -->

              <div id="faq-general-4" class="panel-collapse collapse">
                <div class="panel-body">
                <p>improved reports for your convenience, added functionality to profile page</p>
                </div><!-- .panel-body -->
              </div> <!-- ./panel-collapse -->
            </div><!-- .panel -->

          </div> <!-- /.accordion -->

     

        </div> <!-- /.col -->

      </div> <!-- /.row -->

    </div> <!-- /.container -->

  </div> <!-- .content -->

</div> <!-- /#wrapper -->

<footer class="footer">
  <div class="container">
    <p class="pull-left">Copyright &copy; 2014-<?=date('Y')?> The Marscoin Foundation, Inc.</p>
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


<script type="text/javascript" src="https://s3.amazonaws.com/assets.freshdesk.com/widget/freshwidget.js"></script>
<script type="text/javascript">
  FreshWidget.init("", {"queryString": "&widgetType=popup", "widgetType": "popup", "buttonType": "text", "buttonText": "Support", "buttonColor": "white", "buttonBg": "#006063", "alignment": "4", "offset": "235px", "formHeight": "500px", "url": "https://marscoinfoundationinc.freshdesk.com"} );
</script>

</body>
</html>