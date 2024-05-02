<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head>
  <title>Martian Republic</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald:400,300,700">
  <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@700&family=Orbitron:wght@500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/landing/css/font-awesome.min.css">
  <link rel="stylesheet" href="/assets/landing/css/bootstrap.min.css">
  <link href="/assets/landing/css/mvpready-landing.css" rel="stylesheet">
  <link href="/assets/landing/css/mvpready-flat.css" rel="stylesheet">
  <link href="/assets/landing/css/animate.css" rel="stylesheet">
  <link rel="shortcut icon" href="/assets/favicon.ico">
  <style>
    .mini-feature-title{
      font-size: 25px;
    }
    .shadow-lg {
        box-shadow: 0 1rem 3rem rgb(0 0 0 / 18%) !important;
    }
    .lead-small{
      font-size: 19px;
    }
    .divider-wider{
      margin-bottom: 110px;
      margin-top: 50px;
    }
  </style>
</head>
<body class=" ">
<div id="wrapper">
  <header class="navbar navbar-inverse" role="banner">

    <div class="container">

      <div class="navbar-header">
        <a href="/" class="navbar-brand navbar-brand-img"  style="font-family: 'Orbitron', sans-serif;">
          <img style="width: 67px;" src="/assets/landing/img/logomarscoinwallet.png" alt="MVP Ready">
        Martian Republic
        </a>

        <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only">Toggle navigation</span>
          <i class="fa fa-bars"></i>
        </button>
      </div> <!-- /.navbar-header -->


      <nav class="collapse navbar-collapse" role="navigation">

        <ul class="nav navbar-nav navbar-right mainnav-menu">

          <li class="">
            <a target="_blank" href="https://marscoin.gitbook.io/marscoin-documentation/">Documentation</a>
          </li>

          <li>
            <a href="/signup">Signup</a>
          </li>

          <li>
            <a href="/login">Login</a>
          </li>

        </ul>

        <ul class="nav navbar-nav navbar-social navbar-left">

          <li>
            <a target="_blank" href="http://facebook.com/marscoin" class="ui-tooltip" title="Facebook" data-placement="bottom">
              <i class="fa fa-facebook"></i>
              <span class="navbar-social-label">Facebook</span>
            </a>
          </li>

          <li>
            <a target="_blank" href="http://twitter.com/marscoinorg" class="ui-tooltip" title="Twitter" data-placement="bottom">
              <i class="fa fa-twitter"></i>
              <span class="navbar-social-label">Twitter</span>
            </a>
          </li>

          <li>
            <a target="_blank" href="https://discord.gg/6vVKH6QdYb" class="ui-tooltip" title="Discord" data-placement="bottom">
              <i class="fa fa-github-alt"></i>
              <span class="navbar-social-label">Discord</span>
            </a>
          </li>

          <li>
            <a target="_blank" href="https://reddit.com/r/marscoin" class="ui-tooltip" title="Reddit" data-placement="bottom">
              <i class="fa fa-reddit"></i>
              <span class="navbar-social-label">Reddit</span>
            </a>
          </li>

        </ul>

      </nav>

    </div> <!-- /.container -->

  </header>



     <div class="masthead">
      
      <div class="container">

        <h1 class="masthead-subtitle">
          Support
        </h1>
    
      </div> <!-- /.container -->
    </div> <!-- /.masthead -->


    <div class="content">

      <div class="container">

        <div class="layout layout-stack-sm layout-main-left">

          <div class="col-sm-8 layout-main">
            <h4 class="content-title">
              <span>Get in Touch</span>
            </h4>

            <p>Feel free to reach out with any questions or suggestions...</p>

            <br>
            <br>

            <h4 class="content-title">
              <span>Send an Email</span>
            </h4>
            <form class="form form-horizontal" action="{{ route('contact.send') }}" method="POST">
            @csrf
              <div class="form-group">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>Name: <span class="required">*</span></label>
                  <input class="form-control" id="name" name="name" type="text" value="" required="">
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <label>Email: <span class="required">*</span></label>
                  <input class="form-control" type="email" id="email" name="email" value="" required="">
                </div>
              </div>

              <div class="form-group">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <label>Subject: <span class="required">*</span></label>
                  <input class="form-control" id="subject" name="subject" type="text" value="" required="">
                </div>
              </div>

              <div class="form-group">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <label>Message: <span class="required">*</span></label>
                  <textarea class="form-control" id="text" name="text" rows="6" cols="40" required=""></textarea>
                </div>
              </div>

              <div class="form-group">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <button class="btn btn-primary" type="submit">Submit Message</button>
                </div>
              </div>

             
            </form>
          </div> <!-- /.col -->

          <div class="col-sm-4 layout-sidebar">
            

          </div> <!-- /.col -->
          
        </div> <!-- /.row -->

      </div> <!-- /.container -->   	

    </div> <!-- /.content -->




</div> <!-- /#wrapper -->

<footer class="footer">

	<div class="container">

      <div class="row">

        <div class="col-sm-3">
          <h4 class="content-title">
            <span>Martian Republic</span>
          </h4>

          Is a project by the Marscoin Foundation, Inc. to further the cause of Marscoin and cryptocurrencies in space exploration.
        </div> <!-- /.col -->


        <div class="col-sm-3">

          <h4 class="content-title">
            <span>Twitter Feed</span>
             <a class="twitter-timeline" height="250" href="https://twitter.com/marscoinorg" data-chrome="nofooter  noscrollbar" data-widget-id="492843006043516928">Tweets by @marscoinorg</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

          </h4>
        </div> <!-- /.col -->


        <div class="col-sm-3">

          <h4 class="content-title">
            <span>Stay Connected</span>
          </h4>

          <p></p>

          <br>

          <ul class="footer-social-link">
            <li>
              <a href="javascript:;" class="ui-tooltip" title="Facebook" data-placement="bottom">
                <i class="fa fa-facebook"></i>
              </a>
            </li>
            <li>
              <a href="javascript:;" class="ui-tooltip" title="Twitter" data-placement="bottom">
                <i class="fa fa-twitter"></i>
              </a>
            </li>
            <li>
              <a href="javascript:;" class="ui-tooltip" title="Google+" data-placement="bottom">
                <i class="fa fa-google-plus"></i>
              </a>
            </li>
          </ul>

        </div> <!-- /.col -->


        <div class="col-sm-3">

        <h4 class="content-title">
          <span>Stay Updated</span>
        </h4>

        <p>Get emails about new theme launches &amp;  future updates.</p>

        <form action="/" class="form">

          <div class="form-group">
            <!-- <label>Email: <span class="required">*</span></label> -->
            <input class="form-control" id="newsletter_email" name="newsletter_email" type="text" value="" required="" placeholder="Email Address">
          </div> <!-- /.form-group -->

          <div class="form-group">
            <button class="btn btn-transparent">Subscribe Me</button>
          </div> <!-- /.form-group -->

        </form>

      </div> <!-- /.col -->

      </div> <!-- /.row -->

	</div> <!-- /.container -->

</footer>

<footer class="copyright">
<div class="container">
    <div class="row">
      <div class="col-sm-6">
        <p>Copyright &copy; 2014-<?=date('Y')?> The Marscoin Foundation, Inc.</p>
    </div>
      <div class="col-sm-6">
        <p class="pull-right"><a style="color: white;" href="/status">Server Status</a> &middot; <a style="color: white;" href="/privacy">Privacy</a></p>
      </div> 
    </div> 
  </div>
</footer>


<script src="/assets/landing/js/libs/jquery-1.10.2.min.js"></script>
<script src="/assets/landing/js/libs/bootstrap.min.js"></script>

<script src="/assets/landing/js/plugins/timeago/jquery.timeago.js"></script>
<script src="/assets/landing/js/plugins/tweetable/tweetable.jquery.min.js"></script>
<script src="/assets/landing/js/plugins/carouFredSel/jquery.carouFredSel-6.2.1-packed.js"></script>

<script src="/assets/landing/js/mvpready-core.js"></script>
<script src="/assets/landing/js/mvpready-landing.js"></script>


</body>
</html>
