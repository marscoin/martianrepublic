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
          Status
        </h1>
        

      </div> <!-- /.container -->

    </div> <!-- /.masthead -->




    <div class="content">

      <div class="content">
      
      <div class="container">

        <div class="text-center">
          <h2>Martian Republic Server Status</h2>
          <h4>The MartianRepublic Node communicates with the following subsystems</h4>

         
        </div>

        <div class="row">

            <div class="col-sm-12 col-md-10 col-md-offset-1">

              <div class="row">


                <div class="col-sm-4">
                  <div class="pricing-plan">
                    <div class="pricing-header">
                      <h3 class="pricing-plan-title">Web Server</h3>               
                      <p class="pricing-plan-label">Base Reference Implementation</p>   
                    </div>
                    <?php if($web_status == "success"){ ?>
                    <a href="#" class="btn btn-success">Online</a>
                    <?php }else { ?>
                    <a href="#" class="btn btn-danger">Offline</a>
                    <?php } ?>
                  </div> <!-- /.pricing-plan -->
                </div> <!-- /.col -->


                 <div class="col-sm-4">
                  <div class="pricing-plan">
                    <div class="pricing-header">
                      <h3 class="pricing-plan-title">Database Server</h3>               
                      <p class="pricing-plan-label">Local Database Cache</p>   
                    </div>
                    <?php if($mysql_status == "success"){ ?>
                    <a href="#" class="btn btn-success">Online</a>
                    <?php }else { ?>
                    <a href="#" class="btn btn-danger">Offline</a>
                    <?php } ?>
                  </div> <!-- /.pricing-plan -->
                </div> <!-- /.col -->


                <div class="col-sm-4">
                  <div class="pricing-plan">
                    <div class="pricing-header">
                      <h3 class="pricing-plan-title">Marscoin Node</h3>               
                      <p class="pricing-plan-label">Local Marscoin node</p>   
                    </div>
                    <?php if($marscoind_status == "success"){ ?>
                    <a href="#" class="btn btn-success">Online</a>
                    <?php }else { ?>
                    <a href="#" class="btn btn-danger">Offline</a>
                    <?php } ?>
                  </div> <!-- /.pricing-plan -->
                </div> <!-- /.col -->              


              </div> <!-- /.row -->


              <div class="row">



              <div class="col-sm-4">
                  <div class="pricing-plan">
                    <div class="pricing-header">
                      <h3 class="pricing-plan-title">Blockexplorer</h3>               
                      <p class="pricing-plan-label">Blockchain explorer node</p>   
                    </div>
                    <?php if($blockexplorer == "success"){ ?>
                    <a href="#" class="btn btn-success">Online</a>
                    <?php }else { ?>
                    <a href="#" class="btn btn-danger">Offline</a>
                    <?php } ?>
                  </div> <!-- /.pricing-plan -->
                </div> <!-- /.col -->      

                


                <div class="col-sm-4">
                  <div class="pricing-plan">
                    <div class="pricing-header">
                      <h3 class="pricing-plan-title">Pebas Node</h3>               
                      <p class="pricing-plan-label">Blockexplorer API Bridge </p>   
                    </div>
                    <?php if($pebas_status == "success"){ ?>
                    <a href="#" class="btn btn-success">Online</a>
                    <?php }else { ?>
                    <a href="#" class="btn btn-danger">Offline</a>
                    <?php } ?>
                  </div> <!-- /.pricing-plan -->
                </div> <!-- /.col --> 


                <div class="col-sm-4">
                  <div class="pricing-plan">
                    <div class="pricing-header">
                      <h3 class="pricing-plan-title">IPFS Node</h3>               
                      <p class="pricing-plan-label">IPFS Pinning Service</p>   
                    </div>
                    <?php if($ipfs_status == "success"){ ?>
                    <a href="#" class="btn btn-success">Online</a>
                    <?php }else { ?>
                    <a href="#" class="btn btn-danger">Offline</a>
                    <?php } ?>
                  </div> <!-- /.pricing-plan -->
                </div> <!-- /.col --> 







              </div>

            </div> <!-- /.col -->

          </div> <!-- /.row -->


          <br>
          <br>

          <h4 class="text-center">
            Running into bugs?
            <span>Send us a bug report.</span>
            <a href="mailto:info@marscoin.org">Get in touch &nbsp;<i class="fa fa-external-link"></i></a>
          </h4>

          <br><br><br><br>


        <div class="row">
                
            <div class="col-md-12">
              
          <?php if($network && count($network) > 0){ ?>
           <h3 class="content-title"><span>Marscoin Network</span></h3>
                <p class="noticebar-empty-text">Block Height: #<?=$network['blocks']?></p>
                <p class="noticebar-empty-text">Server Connections: <?=$network['connections']?></p>
                <p class="noticebar-empty-text">Network Difficulty: <?=$network['difficulty']?></p>
                <p class="noticebar-empty-text">Node Version: <?=$network['version']?></p>
          <?php } ?>

            </div> <!-- /.row -->
        </div>

   <br><br><br><br>

          <div class="row">
                
            <div class="col-md-12">
              <h3 class="content-title">
                <span>The Martian Republic includes</span>
              </h3>
            </div> <!-- /.row -->
        
          <div class="col-md-3 col-sm-6">
            <ul class="icons-list">
              <li>
                <i class="icon-li fa fa-check-square"></i> 

                Free Online HD Wallet
              </li>

              <li>
                <i class="icon-li fa fa-check-square"></i> 

                SSL Protection
              </li>

              <li>
                <i class="icon-li fa fa-check-square"></i> 

                Blockchain based Identity service
              </li>

              <li>
                <i class="icon-li fa fa-check-square"></i> 

                Blockchain based Voting
              </li>
            </ul>         
          </div><!-- /.col -->

          <div class="col-md-3 col-sm-6">
            <ul class="icons-list">
              
              <li>
              <i class="icon-li fa fa-check-square"></i> 

                Open Source Community Project
              </li>
              
              <li>
              <i class="icon-li fa fa-check-square"></i> 

                Realtime Dashboard
              </li>
              
              <li>
              <i class="icon-li fa fa-check-square"></i> 

                Inventory Registry
              </li>
              
              <li>
              <i class="icon-li fa fa-check-square"></i> 

                Research Logbook
              </li>
           </ul>    
          </div><!-- /.col -->

          <div class="col-md-3 col-sm-6">
            <ul class="icons-list">
              
              <li>
              <i class="icon-li fa fa-check-square"></i> 

               Proposal Creation
              </li>
              
              <li>
              <i class="icon-li fa fa-check-square"></i> 

               Blockchain Verified Bill Discussions
              </li>
              
              <li>
              <i class="icon-li fa fa-check-square"></i> 

                Coinshuffle vote anonymization server
              </li>
              
              <li>
              <i class="icon-li fa fa-check-square"></i> 

                Send and Receive Marscoins
              </li>
            </ul>     
          </div><!-- /.col -->

          <div class="col-md-3 col-sm-6">
            <ul class="icons-list">
              
              <li>
              <i class="icon-li fa fa-check-square"></i> 

               Seed phrase backup service
              </li>
              
              <li>
              <i class="icon-li fa fa-check-square"></i> 

               Public Endorsements
              </li>
              
              <li>
              <i class="icon-li fa fa-check-square"></i> 

                2FA Authentication for added privacy
              </li>
              
              <li>
              <i class="icon-li fa fa-check-square"></i> 

                Easy to Use Interface
              </li>
            </ul>   
          </div><!-- /.col -->
        
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

      <div class="col-sm-12">
        <p>Copyright &copy; 2014-<?=date('Y')?> The Marscoin Foundation, Inc.</p>
      </div> <!-- /.col -->

    </div> <!-- /.row -->

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
