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

        <a href="./" class="navbar-brand navbar-brand-img">
          <img src="/assets/wallet/img/logo.png" alt="MVP Ready">
        </a>
      </div> <!-- /.navbar-header -->


      <nav class="collapse navbar-collapse" role="navigation">

        <ul class="nav navbar-nav noticebar navbar-left">

          <li class="dropdown">
            <a href="./page-notifications.html" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell"></i>
              <span class="navbar-visible-collapsed">&nbsp;Notifications&nbsp;</span>
              <span class="badge badge-primary">3</span>
            </a>

            <ul class="dropdown-menu noticebar-menu noticebar-hoverable" role="menu">
              <li class="nav-header">
                <div class="pull-left">
                  Notifications
                </div>

                <div class="pull-right">
                  <a href="javascript:;">Mark as Read</a>
                </div>
              </li>

              <li>
                <a href="./page-notifications.html" class="noticebar-item">
                  <span class="noticebar-item-image">
                    <i class="fa fa-cloud-upload text-success"></i>
                  </span>
                  <span class="noticebar-item-body">
                    <strong class="noticebar-item-title">Templates Synced</strong>
                    <span class="noticebar-item-text">20 Templates have been synced to the Mashon Demo instance.</span>
                    <span class="noticebar-item-time"><i class="fa fa-clock-o"></i> 12 minutes ago</span>
                  </span>
                </a>
              </li>

              <li>
                <a href="./page-notifications.html" class="noticebar-item">
                  <span class="noticebar-item-image">
                    <i class="fa fa-ban text-danger"></i>
                  </span>
                  <span class="noticebar-item-body">
                    <strong class="noticebar-item-title">Sync Error</strong>
                    <span class="noticebar-item-text">5 Designs have been failed to be synced to the Mashon Demo instance.</span>
                    <span class="noticebar-item-time"><i class="fa fa-clock-o"></i> 20 minutes ago</span>
                  </span>
                </a>
              </li>

              <li class="noticebar-menu-view-all">
                <a href="./page-notifications.html">View All Notifications</a>
              </li>
            </ul>
          </li>



          <li class="dropdown">
            <a href="./page-notifications.html" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope"></i>
              <span class="navbar-visible-collapsed">&nbsp;Messages&nbsp;</span>
            </a>

            <ul class="dropdown-menu noticebar-menu noticebar-hoverable" role="menu">
              <li class="nav-header">
                <div class="pull-left">
                  Messages
                </div>

                <div class="pull-right">
                  <a href="javascript:;">New Message</a>
                </div>
              </li>

              <li>
                <a href="./page-notifications.html" class="noticebar-item">
                  <span class="noticebar-item-image">
                    <img src="/assets/wallet/img/avatars/avatar-1-md.jpg" style="width: 50px" alt="">
                  </span>

                  <span class="noticebar-item-body">
                    <strong class="noticebar-item-title">New Message</strong>
                    <span class="noticebar-item-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit...</span>
                    <span class="noticebar-item-time"><i class="fa fa-clock-o"></i> 20 minutes ago</span>
                  </span>
                </a>
              </li>

              <li>
                <a href="./page-notifications.html" class="noticebar-item">
                  <span class="noticebar-item-image">
                    <img src="/assets/wallet/img/avatars/avatar-2-md.jpg" style="width: 50px" alt="">
                  </span>

                  <span class="noticebar-item-body">
                    <strong class="noticebar-item-title">New Message</strong>
                    <span class="noticebar-item-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit...</span>
                    <span class="noticebar-item-time"><i class="fa fa-clock-o"></i> 5 hours ago</span>
                  </span>
                </a>
              </li>

              <li class="noticebar-menu-view-all">
                <a href="./page-notifications.html">View All Messages</a>
              </li>
            </ul>
          </li>

          <li class="dropdown">
            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-exclamation-triangle"></i>
              <span class="navbar-visible-collapsed">&nbsp;Alerts&nbsp;</span>
            </a>

            <ul class="dropdown-menu noticebar-menu noticebar-hoverable" role="menu">
              <li class="nav-header">
                <div class="pull-left">
                  Alerts
                </div>
              </li>

              <li class="noticebar-empty">
                <h4 class="noticebar-empty-title">No alerts here.</h4>
                <p class="noticebar-empty-text">Check out what other makers are doing on Explore!</p>
              </li>
            </ul>
          </li>

        </ul>



        <ul class="nav navbar-nav navbar-right">

          <li>
            <a href="javsacript:;">About</a>
          </li>

          <li>
            <a href="javascript:;">Resources</a>
          </li>

          <li class="dropdown navbar-profile">
            <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;">
              <img src="/assets/wallet/img/avatars/avatar-1-xs.jpg" class="navbar-profile-avatar" alt="">
              <span class="navbar-profile-label">rod@rod.me &nbsp;</span>
              <i class="fa fa-caret-down"></i>
            </a>

            <ul class="dropdown-menu" role="menu">

              <li>
                <a href="./page-profile.html">
                  <i class="fa fa-user"></i>
                  &nbsp;&nbsp;My Profile
                </a>
              </li>

              <li>
                <a href="./page-pricing.html">
                  <i class="fa fa-dollar"></i>
                  &nbsp;&nbsp;Plans & Billing
                </a>
              </li>

              <li>
                <a href="./page-settings.html">
                  <i class="fa fa-cogs"></i>
                  &nbsp;&nbsp;Settings
                </a>
              </li>

              <li class="divider"></li>

              <li>
                <a href="./account-login.html">
                  <i class="fa fa-sign-out"></i>
                  &nbsp;&nbsp;Logout
                </a>
              </li>

            </ul>

          </li>

        </ul>

      </nav>

    </div> <!-- /.container -->

  </header>


  <div class="mainnav">

    <div class="container">

      <a class="mainnav-toggle" data-toggle="collapse" data-target=".mainnav-collapse">
        <span class="sr-only">Toggle navigation</span>
        <i class="fa fa-bars"></i>
      </a>

      <nav class="collapse mainnav-collapse" role="navigation">

        <form class="mainnav-form pull-right" role="search">
          <input type="text" class="form-control input-md mainnav-search-query" placeholder="Search">
          <button class="btn btn-sm mainnav-form-btn"><i class="fa fa-search"></i></button>
        </form>

        <ul class="mainnav-menu">

          <li class="dropdown active">
          	<a href="./index.html" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown">
          	Dashboards
          	<i class="mainnav-caret"></i>
          	</a>

          	<ul class="dropdown-menu" role="menu">
              <li>
                <a href="./index.html">
                <i class="fa fa-dashboard"></i>
                &nbsp;&nbsp;Analytics Dashboard
                </a>
              </li>

              <li>
                <a href="./dashboard-2.html">
                <i class="fa fa-dashboard"></i>
                &nbsp;&nbsp;Sidebar Dashboard
                </a>
              </li>

              <li>
                <a href="./dashboard-3.html">
                <i class="fa fa-dashboard"></i>
                &nbsp;&nbsp;Reports Dashboard
                </a>
              </li>
          	</ul>
          </li>


          <li class="dropdown ">

            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown">
            Send
            <i class="mainnav-caret"></i>
            </a>

            <ul class="dropdown-menu" role="menu">

              <li>
                <a href="./components-tabs.html">
                <i class="fa fa-bars"></i>
                &nbsp;&nbsp;Tabs &amp; Accordions
                </a>
              </li>

              <li>
                <a href="./components-popups.html">
                <i class="fa fa-calendar-o"></i>
                &nbsp;&nbsp;Popups &amp; Alerts
                </a>
              </li>

              <li>
                <a href="./components-validation.html">
                <i class="fa fa-check"></i>
                &nbsp;&nbsp;Validation
                </a>
              </li>

              <li>
                <a href="./components-datatables.html">
                <i class="fa fa-table"></i>
                &nbsp;&nbsp;Data Tables
                </a>
              </li>

              <li>
                <a href="./components-gallery.html">
                <i class="fa fa-picture-o"></i>
                &nbsp;&nbsp;Gallery
                </a>
              </li>

              <li>
                <a href="./components-charts.html">
                <i class="fa fa-bar-chart-o"></i>
                &nbsp;&nbsp;Charts
                </a>
              </li>
            </ul>
          </li>


          <li class="dropdown ">

            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown">
            Receive
            <i class="mainnav-caret"></i>
            </a>

            <ul class="dropdown-menu" role="menu">
              <li>
                <a href="./page-pricing.html">
                <i class="fa fa-money"></i>
                &nbsp;&nbsp;Plans & Billing
                </a>
              </li>

              <li>
                <a href="./page-profile.html">
                <i class="fa fa-user"></i>
                &nbsp;&nbsp;Profile
                </a>
              </li>

              <li>
                <a href="./page-settings.html">
                <i class="fa fa-cogs"></i>
                &nbsp;&nbsp;Settings
                </a>
              </li>

              <li>
                <a href="./page-faq.html">
                <i class="fa fa-question"></i>
                &nbsp;&nbsp;FAQ
                </a>
              </li>
            </ul>
          </li>


          <li class="dropdown ">

            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown">
            Buy
            <i class="mainnav-caret"></i>
            </a>

            <ul class="dropdown-menu" role="menu">
              <li>
                <a href="./page-notifications.html">
                <i class="fa fa-bell"></i>
                &nbsp;&nbsp;Notifications
                </a>
              </li>

              <li>
                <a href="./extras-icons.html">
                <i class="fa fa-smile-o"></i>
                &nbsp;&nbsp;Font Icons
                </a>
              </li>

              <li class="dropdown-submenu">
                <a tabindex="-1" href="#">
                <i class="fa fa-ban"></i>
                &nbsp;&nbsp;Error Pages
                </a>

                <ul class="dropdown-menu">
                  <li>
                    <a href="./page-404.html">
                    <i class="fa fa-ban"></i>
                    &nbsp;&nbsp;404 Error
                    </a>
                  </li>

                  <li>
                    <a href="./page-500.html">
                    <i class="fa fa-ban"></i>
                    &nbsp;&nbsp;500 Error
                    </a>
                  </li>
                </ul>
              </li>

              <li class="dropdown-submenu">

                <a tabindex="-1" href="#">
                <i class="fa fa-lock"></i>
                &nbsp;&nbsp;Login Pages
                </a>

                <ul class="dropdown-menu">
                  <li>
                    <a href="./account-login.html">
                    <i class="fa fa-unlock"></i>
                    &nbsp;&nbsp;Login
                    </a>
                  </li>

                  <li>
                    <a href="./account-login-social.html">
                    <i class="fa fa-unlock"></i>
                    &nbsp;&nbsp;Login Social
                    </a>
                  </li>

                  <li>
                    <a href="./account-signup.html">
                    <i class="fa fa-star"></i>
                    &nbsp;&nbsp;Signup
                    </a>
                    </li>

                  <li>
                    <a href="./account-forgot.html">
                    <i class="fa fa-envelope"></i>
                    &nbsp;&nbsp;Forgot Password
                    </a>
                  </li>
                </ul>
              </li>

            </ul>

          </li>

        </ul>

      </nav>

    </div> <!-- /.container -->

  </div> <!-- /.mainnav -->

  <div class="content">

    <div class="container">

        <div class="row">

          <div class="col-md-4 col-sm-5">

            <div class="portlet">

              <h4 class="portlet-title">
                <u>Daily Stats</u>
              </h4>

              <div class="portlet-body">

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit, fugiat, dolores, laborum sit.</p>

                <hr>

                <table class="table keyvalue-table">
                  <tbody>
                    <tr>
                      <td class="kv-key"><i class="fa fa-male kv-icon kv-icon-primary"></i> Balance</td>
                      <td class="kv-value">♂ 5,367 MRS</td>
                    </tr>
                    <tr>
                      <td class="kv-key"><i class="fa fa-gift kv-icon kv-icon-secondary"></i> Received</td>
                      <td class="kv-value">♂ 473 MRS</td>
                    </tr>
                    <tr>
                      <td class="kv-key"><i class="fa fa-exchange kv-icon kv-icon-tertiary"></i>Sent</td>
                      <td class="kv-value">♂ 78 MRS</td>
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

            <div class="col-md-5">

              <div class="portlet">

                <h4 class="portlet-title">
                  <u>Product Breakdown</u>
                </h4>

                <div class="portlet-body">

                  <div id="pie-chart" class="chart-holder-250"></div>
                </div> <!-- /.portlet-body -->

              </div> <!-- /.portlet -->

            </div> <!-- /.col -->

            <div class="col-md-3">

              <div class="portlet">

                <h4 class="portlet-title">
                  <u>Crypto News</u>
                </h4>

                <div class="portlet-body">

                  <div class="progress-stat">

                    <div class="progress-stat-label">
                      % New Visits
                    </div>

                    <div class="progress-stat-value">
                      77.7%
                    </div>

                    <div class="progress progress-striped progress-sm active">
                      <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="77" aria-valuemin="0" aria-valuemax="100" style="width: 77%">
                        <span class="sr-only">77.74% Visit Rate</span>
                      </div>
                    </div> <!-- /.progress -->

                  </div> <!-- /.progress-stat -->

                  <div class="progress-stat">

                    <div class="progress-stat-label">
                      % Mobile Visitors
                    </div>

                    <div class="progress-stat-value">
                      33.2%
                    </div>

                    <div class="progress progress-striped progress-sm active">
                      <div class="progress-bar progress-bar-tertiary" role="progressbar" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100" style="width: 33%">
                        <span class="sr-only">33% Mobile Visitors</span>
                      </div>
                    </div> <!-- /.progress -->

                  </div> <!-- /.progress-stat -->

                  <div class="progress-stat">

                    <div class="progress-stat-label">
                      Bounce Rate
                    </div>

                    <div class="progress-stat-value">
                      42.7%
                    </div>

                    <div class="progress progress-striped progress-sm active">
                      <div class="progress-bar progress-bar-secondary" role="progressbar" aria-valuenow="42" aria-valuemin="0" aria-valuemax="100" style="width: 42%">
                        <span class="sr-only">42.7% Bounce Rate</span>
                      </div>
                    </div> <!-- /.progress -->

                  </div> <!-- /.progress-stat -->

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


</body>
</html>