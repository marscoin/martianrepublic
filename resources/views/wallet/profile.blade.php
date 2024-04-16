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
  <!-- <link href="/assets/wallet/css/custom.css" rel="stylesheet">-->
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
        <div class="col-md-3 col-sm-5">

          <div class="profile-avatar">
            <img src="/assets/wallet/img/avatars/avatar-2-lg.jpg" class="profile-avatar-img thumbnail" alt="Profile Image">
          </div> <!-- /.profile-avatar -->

          <div class="list-group">  

            <a href="javascript:;" class="list-group-item">
              <i class="fa fa-asterisk text-primary"></i> &nbsp;&nbsp;Activity Feed

              <i class="fa fa-chevron-right list-group-chevron"></i>
            </a> 

            <a href="javascript:;" class="list-group-item">
              <i class="fa fa-book text-primary"></i> &nbsp;&nbsp;Projects

              <i class="fa fa-chevron-right list-group-chevron"></i>
              <span class="badge">3</span>
            </a> 

            <a href="javascript:;" class="list-group-item">
              <i class="fa fa-envelope text-primary"></i> &nbsp;&nbsp;Messages

              <i class="fa fa-chevron-right list-group-chevron"></i>
            </a> 

            <a href="javascript:;" class="list-group-item">
              <i class="fa fa-group text-primary"></i> &nbsp;&nbsp;Friends

              <i class="fa fa-chevron-right list-group-chevron"></i>
            </a> 

            <a href="javascript:;" class="list-group-item">
              <i class="fa fa-cog text-primary"></i> &nbsp;&nbsp;Settings

              <i class="fa fa-chevron-right list-group-chevron"></i>
            </a> 
          </div> <!-- /.list-group -->



        </div> <!-- /.col -->



        <div class="col-md-6 col-sm-7">

          <h3>Nikita Williams</h3>

          <h5 class="text-muted">Visual, UI, UX Designer</h5>

          <hr>

          <p>
            <a href="javascript:;" class="btn btn-primary">Follow Nikita</a>
            &nbsp;&nbsp;
            <a href="javascript:;" class="btn btn-secondary">Send Message</a>
          </p>

          <hr>
          
          <ul class="icons-list">
            <li><i class="icon-li fa fa-envelope"></i> support@jumpstartthemes.com</li>
            <li><i class="icon-li fa fa-globe"></i> jumstartthemes.com</li>
            <li><i class="icon-li fa fa-map-marker"></i> Las Vegas, NV</li>
          </ul>    

          <br>

          <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec.</p>

          <hr>


          <br><br>

          <h4 class="content-title"><u>Activity Feed</u></h4>

            <div class="share-widget clearfix">

              <textarea class="form-control share-widget-textarea" rows="3" placeholder="Share what you've been up to..." tabindex="1"></textarea>

              <div class="share-widget-actions">
                <div class="share-widget-types pull-left">
                  <a href="javascript:;" class="fa fa-picture-o ui-tooltip" title="Post an Image"><i></i></a>
                  <a href="javascript:;" class="fa fa-video-camera ui-tooltip" title="Upload a Video"><i></i></a>
                  <a href="javascript:;" class="fa fa-lightbulb-o ui-tooltip" title="Post an Idea"><i></i></a>
                  <a href="javascript:;" class="fa fa-question-circle ui-tooltip" title="Ask a Question"><i></i></a>
                </div>	

              <div class="pull-right">
                <a class="btn btn-primary btn-sm" tabindex="2">Post</a>
              </div>

              </div> <!-- /.share-widget-actions -->

            </div> <!-- /.share-widget -->

            <br><br>

            <div class="feed-item feed-item-idea">

              <div class="feed-icon">
                <i class="fa fa-lightbulb-o"></i>
              </div> <!-- /.feed-icon -->

              <div class="feed-subject">
                <p><a href="javascript:;">Nikita Williams</a> shared an idea: <a href="javascript:;">Create an Awesome Idea</a></p>
              </div> <!-- /.feed-subject -->

              <div class="feed-content">
                <ul class="icons-list">
                  <li>
                    <i class="icon-li fa fa-quote-left"></i>
                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes.
                  </li>
                </ul>
              </div> <!-- /.feed-content -->

              <div class="feed-actions">
                <a href="javascript:;" class="pull-left"><i class="fa fa-thumbs-up"></i> 123</a> 
                <a href="javascript:;" class="pull-left"><i class="fa fa-comment-o"></i> 29</a>

                <a href="javascript:;" class="pull-right"><i class="fa fa-clock-o"></i> 2 days ago</a>
              </div> <!-- /.feed-actions -->

            </div> <!-- /.feed-item -->



            <div class="feed-item feed-item-image">

              <div class="feed-icon">
                <i class="fa fa-picture-o"></i>
              </div> <!-- /.feed-icon -->

              <div class="feed-subject">
                <p><a href="javascript:;">Nikita Williams</a> posted the <strong>4 files</strong>: <a href="javascript:;">Annual Reports</a></p>
              </div> <!-- /.feed-subject -->

              <div class="feed-content">
                <div class="thumbnail" style="width: 375px">
                  <img src="/assets/wallet/img/mockup.png" style="width: 100%;" alt="Gallery Image">
                </div> <!-- /.thumbnail -->
              </div> <!-- /.feed-content -->

              <div class="feed-actions">
                <a href="javascript:;" class="pull-left"><i class="fa fa-thumbs-up"></i> 123</a> 
                <a href="javascript:;" class="pull-left"><i class="fa fa-comment-o"></i> 29</a>

                <a href="javascript:;" class="pull-right"><i class="fa fa-clock-o"></i> 2 days ago</a>
              </div> <!-- /.feed-actions -->

            </div> <!-- /.feed-item -->



            <div class="feed-item feed-item-file">

              <div class="feed-icon">
                <i class="fa fa-cloud-upload"></i>
              </div> <!-- /.feed-icon -->

              <div class="feed-subject">
                <p><a href="javascript:;">Nikita Williams</a> posted the <strong>4 files</strong>: <a href="javascript:;">Annual Reports</a></p>
              </div> <!-- /.feed-subject -->

              <div class="feed-content">
                <ul class="icons-list">
                  <li>
                    <i class="icon-li fa fa-file-text-o"></i>
                    <a href="javascript:;">Annual Report 2007</a> - annual_report_2007.pdf
                  </li>

                  <li>
                    <i class="icon-li fa fa-file-text-o"></i>
                    <a href="javascript:;">Annual Report 2008</a> - annual_report_2007.pdf
                  </li>

                  <li>
                    <i class="icon-li fa fa-file-text-o"></i>
                    <a href="javascript:;">Annual Report 2009</a> - annual_report_2007.pdf
                  </li>

                  <li>
                    <i class="icon-li fa fa-file-text-o"></i>
                    <a href="javascript:;">Annual Report 2010</a> - annual_report_2007.pdf
                  </li>
                </ul>
              </div> <!-- /.feed-content -->

              <div class="feed-actions">
                <a href="javascript:;" class="pull-left"><i class="fa fa-thumbs-up"></i> 123</a> 
                <a href="javascript:;" class="pull-left"><i class="fa fa-comment-o"></i> 29</a>

                <a href="javascript:;" class="pull-right"><i class="fa fa-clock-o"></i> 2 days ago</a>
              </div> <!-- /.feed-actions -->

            </div> <!-- /.feed-item -->



            <div class="feed-item feed-item-bookmark">

              <div class="feed-icon">
                <i class="fa fa-bookmark"></i>
              </div> <!-- /.feed-icon -->

              <div class="feed-subject">
                <p><a href="javascript:;">Nikita Williams</a> bookmarked a page on Delicious: <a href="javascript:;">Jumpstart Themes</a></p>
              </div> <!-- /.feed-subject -->

              <div class="feed-content">
              </div> <!-- /.feed-content -->

              <div class="feed-actions">
                <a href="javascript:;" class="pull-left"><i class="fa fa-thumbs-up"></i> 123</a> 
                <a href="javascript:;" class="pull-left"><i class="fa fa-comment-o"></i> 29</a>

                <a href="javascript:;" class="pull-right"><i class="fa fa-clock-o"></i> 2 days ago</a>
              </div> <!-- /.feed-actions -->

            </div> <!-- /.feed-item -->



            <div class="feed-item feed-item-question">

              <div class="feed-icon">
                <i class="fa fa-question"></i>
              </div> <!-- /.feed-icon -->

              <div class="feed-subject">
                <p><a href="javascript:;">Nikita Williams</a> posted the question: <a href="javascript:;">Who can I call to get a new parking pass for the Rowan Building?</a></p>
              </div> <!-- /.feed-subject -->

              <div class="feed-content">
                <ul class="icons-list">
                  <li>
                    <i class="icon-li fa fa-quote-left"></i>
                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes.
                  </li>
                </ul>
              </div> <!-- /.feed-content -->

              <div class="feed-actions">
                <a href="javascript:;" class="pull-left"><i class="fa fa-thumbs-up"></i> 123</a> 
                <a href="javascript:;" class="pull-left"><i class="fa fa-comment-o"></i> 29</a>

                <a href="javascript:;" class="pull-right"><i class="fa fa-clock-o"></i> 2 days ago</a>
              </div> <!-- /.feed-actions -->

            </div> <!-- /.feed-item -->

            <br class="visible-xs">            
            <br class="visible-xs">
            
          </div> <!-- /.col -->


          <div class="col-md-3">
            <h5 class="content-title"><u>Social Stats</u></h5>

            <div class="list-group">  

              <a href="javascript:;" class="list-group-item">
                  <h3 class="pull-right"><i class="fa fa-eye text-primary"></i></h3>
                  <h4 class="list-group-item-heading">38,847</h4>
                  <p class="list-group-item-text">Profile Views</p>                  
                </a>

              <a href="javascript:;" class="list-group-item">
                <h3 class="pull-right"><i class="fa fa-facebook-square  text-primary"></i></h3>
                <h4 class="list-group-item-heading">3,482</h4>
                <p class="list-group-item-text">Facebook Likes</p>
              </a>

              <a href="javascript:;" class="list-group-item">
                <h3 class="pull-right"><i class="fa fa-twitter-square  text-primary"></i></h3>
                <h4 class="list-group-item-heading">5,845</h4>
                <p class="list-group-item-text">Twitter Followers</p>
              </a>
            </div> <!-- /.list-group -->

            <br>

            <h5 class="content-title"><u>Project Activity</u></h5>

            <div class="well">


              <ul class="icons-list text-md">

                <li>
                  <i class="icon-li fa fa-location-arrow"></i>

                  <strong>Rod</strong> uploaded 6 files. 
                  <br>
                  <small>about 4 hours ago</small>
                </li>

                <li>
                  <i class="icon-li fa fa-location-arrow"></i>

                  <strong>Rod</strong> followed the research interest: <a href="javascript:;">Open Access Books in Linguistics</a>. 
                  <br>
                  <small>about 23 hours ago</small>
                </li>

                <li>
                  <i class="icon-li fa fa-location-arrow"></i>

                  <strong>Rod</strong> added 51 papers. 
                  <br>
                  <small>2 days ago</small>
                </li>
              </ul>

            </div> <!-- /.well -->

          </div> <!-- /.col -->

        </div> <!-- /.row -->

        <br><br>

    </div> <!-- /.container -->

  </div> <!-- .content -->

</div> <!-- /#wrapper -->


<footer class="footer">
  <div class="container">
    <p class="pull-left">Copyright &copy; 2014 The Marscoin Foundation, Inc.</p>
  </div>
</footer>

<script src="/assets/wallet/js/libs/jquery-1.10.2.min.js"></script>
<script src="/assets/wallet/js/libs/bootstrap.min.js"></script>
<script src="/assets/wallet/js/plugins/flot/jquery.flot.js"></script>
<script src="/assets/wallet/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
<script src="/assets/wallet/js/plugins/flot/jquery.flot.pie.js"></script>
<script src="/assets/wallet/js/plugins/flot/jquery.flot.resize.js"></script>
<script src="/assets/wallet/js/mvpready-core.js"></script>
<script src="/assets/wallet/js/mvpready-admin.js"></script>


</body>
</html>