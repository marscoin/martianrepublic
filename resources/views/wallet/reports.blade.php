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
  <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
  <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Oswald:400,300,700">
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
 @include('wallet.mainnav', array('active'=>'dashboard'))
 
  <div class="content">
    <div class="container">
      <div class="portlet">
        <h4 class="portlet-title">
          <u>Monthly Stats</u>
        </h4>
        <div class="portlet-body">
          <div class="row">
            <div class="col-sm-6 col-md-3">
              <div class="row-stat">
                <p class="row-stat-label">Revenue Today</p>
                <h3 class="row-stat-value">$890.00</h3>
                <span class="label label-success row-stat-badge">+43%</span>
              </div> <!-- /.row-stat -->
            </div> <!-- /.col -->
            <div class="col-sm-6 col-md-3">
              <div class="row-stat">
                <p class="row-stat-label">Revenue This Month</p>
                <h3 class="row-stat-value">$8290.00</h3>
                <span class="label label-success row-stat-badge">+17%</span>
              </div> <!-- /.row-stat -->
            </div> <!-- /.col -->
            <div class="col-sm-6 col-md-3">
              <div class="row-stat">
                <p class="row-stat-label">Total Users</p>
                <h3 class="row-stat-value">98,290</h3>
                <span class="label label-success row-stat-badge">+26%</span>
              </div> <!-- /.row-stat -->
            </div> <!-- /.col -->
            <div class="col-sm-6 col-md-3">
              <div class="row-stat">
                <p class="row-stat-label">Currently Active Uses</p>
                <h3 class="row-stat-value">19</h3>
                <span class="label label-danger row-stat-badge">+5%</span>
              </div> <!-- /.row-stat -->
            </div> <!-- /.col -->
            
          </div> <!-- /.row -->
        </div> <!-- /.portlet-body -->
      </div> <!-- /.portlet -->
      
          <div class="row">
            <div class="col-md-8">
              <div class="portlet">
                <h4 class="portlet-title">
                  <u>Revenue Per Month</u>
                </h4>
                <div class="portlet-body">
                  <div class="chart-bg chart-bg-secondary">
                    <div id="reports-line-chart" class="chart-holder-250"></div>
                  </div> <!-- /.bg-chart -->
                  <br>
              </div> <!-- /.portlet-body -->
            </div> <!-- /.portlet -->
              
            </div> <!-- /.col -->
            <div class="col-md-4">         
              <div class="portlet">
                <h4 class="portlet-title">
                  <u>Product Sales Breakdown</u>
                </h4>
                <div class="portlet-body">
                  <div id="donut-chart" class="chart-holder" style="width: 70%;"></div>
                  
                </div> <!-- /.portlet-body -->
              </div> <!-- /.portlet -->
              
            </div> <!-- /.col -->
            
          </div> <!-- /.row -->
      <div class="row">
        <div class="col-md-6">
          <div class="portlet">
            <h4 class="portlet-title">
              <u>Product Sales Today</u>
            </h4>
            <div class="portlet-body">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Product</th>
                    <th class="text-right">Purchases</th>
                    <th class="text-right">Revenue</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>CSS Hat</td>
                    <td class="text-right">264</td>
                    <td class="text-right">$3,129.98</td>
                  </tr>
                  <tr>
                    <td>Subtle Patterns</td>
                    <td class="text-right">20</td>
                    <td class="text-right">$129.98</td>
                  </tr>
                  <tr>
                    <td>PNG Hat</td>
                    <td class="text-right">45</td>
                    <td class="text-right">$9,129.98</td>
                  </tr>
                  <tr>
                    <td>Academy</td>
                    <td class="text-right">560</td>
                    <td class="text-right">$12,249.98</td>
                  </tr>
                  <tr>
                    <td>Social Kit</td>
                    <td class="text-right">120</td>
                    <td class="text-right">$0.00</td>
                  </tr>
                  <tr>
                    <td>Pizaa</td>
                    <td class="text-right">340</td>
                    <td class="text-right">$0.00</td>
                  </tr>
                </tbody>
              </table>
            </div> <!-- /.portlet-body -->
          </div> <!-- /.portlet -->
          
        </div> <!-- /.col -->
        <div class="col-md-6">
          <div class="portlet">
            <h4 class="portlet-title">
              <u>Product Sales Breakdown</u>
            </h4>
            <div class="portlet-body">
              <div id="stacked-horizontal-chart" class="chart-holder-250"></div>
              
            </div> <!-- /.portlet-body -->
          </div> <!-- /.portlet -->
          
        </div> <!-- /.col -->
        
      </div> <!-- /.row -->
      <div class="portlet">
        <h4 class="portlet-title">
          <u>Social Media Stats</u>
        </h4>
        
        <div class="portlet-body">
          
          <div class="row">
            <div class="col-sm-3">
              <div class="row-stat">
                <p class="row-stat-label">Facebook Likes</p>
                <h3 class="row-stat-value">1,290</h3>
                <span class="label label-success row-stat-badge">+21</span>
              </div> <!-- /.row-stat -->
            </div> <!-- /.col -->
            <div class="col-sm-3">
              <div class="row-stat">
                <p class="row-stat-label">Twitter Followers</p>
                <h3 class="row-stat-value">3,290</h3>
                <span class="label label-success row-stat-badge">+10</span>
              </div> <!-- /.row-stat -->
            </div> <!-- /.col -->
            <div class="col-sm-3">
              <div class="row-stat">
                <p class="row-stat-label">Github Followers</p>
                <h3 class="row-stat-value">21,361</h3>
                <span class="label label-danger row-stat-badge">-5</span>
              </div> <!-- /.row-stat -->
            </div> <!-- /.col -->
            <div class="col-sm-3">
              <div class="row-stat">
                <p class="row-stat-label">Email Subscribers</p>
                <h3 class="row-stat-value">2,689</h3>
                <span class="label label-warning row-stat-badge">+ 0</span>
              </div> <!-- /.row-stat -->
            </div> <!-- /.col -->
            
          </div> <!-- /.row -->
        </div> <!-- /.portlet-body -->
      </div> <!-- /.portlet -->
      <div class="row">
        <div class="col-md-6">
          <div class="portlet">
            <h4 class="portlet-title">
              <u>Server Load</u>
            </h4>
            
            <div class="porlet-body">
              <div id="auto-chart" class="chart-holder-250"></div>
              
            </div> <!-- /.portlet-body -->
          </div> <!-- /.portlet -->
          
        </div> <!-- /.col -->
        <div class="col-md-6">
          <div class="portlet">
            <h4 class="portlet-title">
              <u>Daily Traffic</u>
            </h4>
            
            <div class="porlet-body">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Source</th>
                    <th class="text-right">Visits</th>
                    <th class="text-right">Conversion</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>socialkit</td>
                    <td class="text-right">25,843</td>
                    <td class="text-right">8.9%</td>
                  </tr>
                  <tr>
                    <td>subtlepatterns</td>
                    <td class="text-right">5,141</td>
                    <td class="text-right">2.4%</td>
                  </tr>
                  <tr>
                    <td>google</td>
                    <td class="text-right">2,562</td>
                    <td class="text-right">1.2%</td>
                  </tr>
                  <tr>
                    <td>facebook</td>
                    <td class="text-right">345</td>
                    <td class="text-right">0.02%</td>
                  </tr>
                  <tr>
                    <td>Social Kit</td>
                    <td class="text-right">12</td>
                    <td class="text-right">0.0%</td>
                  </tr>
                  <tr>
                    <td>webappers</td>
                    <td class="text-right">9</td>
                    <td class="text-right">0.0%</td>
                  </tr>
                </tbody>
              </table>
              
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
<script src="/assets/wallet/js/plugins/flot/jquery.flot.stack.js"></script>
<script src="/assets/wallet/js/plugins/flot/jquery.flot.orderBars.js"></script>
<!-- App JS -->
<script src="/assets/wallet/js/mvpready-core.js"></script>
<script src="/assets/wallet/js/mvpready-admin.js"></script>
<!-- Plugin JS -->
<script src="/assets/wallet/js/demos/reports/line.js"></script>
<script src="/assets/wallet/js/demos/flot/donut.js"></script>
<script src="/assets/wallet/js/demos/flot/stacked-horizontal.js"></script>
<script src="/assets/wallet/js/demos/flot/auto.js"></script>
</body>
</html>