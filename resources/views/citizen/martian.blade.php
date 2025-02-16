<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <title>Marscoin Wallet</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald:400,300,700">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@700&family=Orbitron:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/wallet/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-admin.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-flat.css">
    <link rel="stylesheet" href="/assets/wallet/js/plugins/fileupload/bootstrap-fileupload.css">
    <link rel="shortcut icon" href="/assets/favicon.ico">
    <link rel="stylesheet" href="/assets/wallet/js/plugins/magnific/magnific-popup.css">
    <script>var current_blob = null;</script>
    @livewireStyles
</head>

<body class=" ">
    <div id="wrapper">
        <header class="navbar navbar-inverse" role="banner">
            <div class="container">
                <div class="navbar-header">
                    @include('wallet.header')
                </div> <!-- /.navbar-header -->
                <nav class="collapse navbar-collapse" role="navigation">
                    @include('wallet.navbarleft')
                    @include('wallet.navbarright')
                </nav>
            </div> <!-- /.container -->
        </header>
        @include('wallet.mainnav', array('active'=>'citizen'))
        <div class="content">

            <div class="container">

                <?php if($wallet_open){ ?>
                <div class="portlet">
                    <div class="portlet-body">

<div class="row">

<div class="col-md-3 col-sm-5">

<div class="profile-avatar">
        <img src="{{ $citcache->avatar_link }}"  onerror="this.onerror=null; this.src='https://martianrepublic.org/assets/citizen/generic_profile.jpg'" class="profile-avatar-img thumbnail" alt="Profile Image. Source {{ $citcache->avatar_link }}">
      </div> 

  <h5 class="content-title"><u>Citizen Engagement</u></h5>

<div class="list-group">  

  <a href="javascript:;" class="list-group-item">
      <h3 class="pull-right"><i class="fa fa-thumbs-o-up text-primary"></i></h3>
      <h4 class="list-group-item-heading"><?=$endorsements?></h4>
      <p class="list-group-item-text">Endorsements</p>                  
    </a>

    <a href="javascript:;" class="list-group-item">
      <h3 class="pull-right"><i class="fa fa-thumbs-o-up text-primary"></i></h3>
      <h4 class="list-group-item-heading"><?=$endorsed?></h4>
      <p class="list-group-item-text">Endorsed</p>                  
    </a>

  <a href="javascript:;" class="list-group-item">
    <h3 class="pull-right"><i class="fa fa-files-o   text-primary"></i></h3>
    <h4 class="list-group-item-heading"><?=$proposals?></h4>
    <p class="list-group-item-text">Proposals Initiated</p>
  </a>

</div> <!-- /.list-group -->

</div> <!-- /.col -->



<div class="col-md-6 col-sm-7">

<h3><?=$citcache->firstname?> <?=$citcache->lastname?> </h3>

<h5 class="text-muted"><?=$citcache->shortbio?> </h5>
<h5 class="text-muted"><?=$citcache->public_address?> </h5>

  <hr>
  <ul class="icons-list">
    @if($isGP)
        <li><i class="icon-li fa fa-users"></i> Joined as Member of the Martian Republic on <?=date( 'M, jS, Y', strtotime($mePublic['mined']) )?></li>
    @endif
    @if(!is_null($meCitizen))
        <li><i class="icon-li fa fa-drivers-license"></i> Gained Citizenship status on <?=date( 'M, jS, Y', strtotime($meCitizen['mined']) )?></li>
    @endif
        <li><i class="icon-li fa fa-globe"></i> Awaiting Starship flight</li>
  </ul>
  <hr>
      
  @livewire('civic-activity-feed', ['userId' => $citcache->userid])

    <br class="visible-xs">            
    <br class="visible-xs">

  </div> 
  <div class="col-md-3">

  @livewire('blockchain-activity-feed')
    
  </div> 
</div> 

                    </div>
                </div>
            <?php }else{ ?>
                <div class="portlet">
                    <div class="portlet-body">
                        <h3>
                            Please <a href="/wallet/dashboard/hd">unlock</a> your civic wallet in order to access the Citizen platform.
                        </h3>
                    </div>
                </div>
            <?php } ?>    

                       
            </div> <!-- /.container -->
        </div> <!-- .content -->
    </div> <!-- /#wrapper -->
    <footer class="footer">
        @include('footer')
    </footer>
    <script src="/assets/wallet/js/dist/my_bundle.js"></script>
    <script src="/assets/wallet/js/libs/jquery-1.10.2.min.js"></script>
    <script src="/assets/wallet/js/libs/bootstrap.min.js"></script>
    <script src="/assets/wallet/js/plugins/flot/jquery.flot.js"></script>
    <script src="/assets/wallet/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="/assets/wallet/js/plugins/flot/jquery.flot.pie.js"></script>
    <script src="/assets/wallet/js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="/assets/wallet/js/plugins/fileupload/bootstrap-fileupload.js"></script>
    <script src="/assets/wallet/js/plugins/magnific/jquery.magnific-popup.min.js"></script>
    <script src="/assets/wallet/js/mvpready-core.js"></script>
    <script src="/assets/wallet/js/mvpready-admin.js"></script>
    <script src="/assets/wallet/js/md5.min.js"></script>
    <script src="/assets/wallet/js/sha256.js"></script>
    @livewireScripts
</body>
</html>