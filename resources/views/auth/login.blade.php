<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head>
  <title>Martian Republic - Login</title>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">

  <!-- Google Font: Open Sans -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald:400,300,700">
  <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@700&family=Orbitron:wght@500&display=swap" rel="stylesheet">

  <!-- Font Awesome CSS -->
  <link rel="stylesheet" href="/assets/wallet/css/font-awesome.min.css">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="/assets/wallet/css/bootstrap.min.css">

  <!-- App CSS -->
  <link rel="stylesheet" href="/assets/wallet/css/mvpready-admin.css">
  <link rel="stylesheet" href="/assets/wallet/css/mvpready-flat.css">
  <!-- <link href="/assets/wallet/css/custom.css" rel="stylesheet">-->

  <!-- Favicon -->
  <link rel="shortcut icon" href="/assets/favicon.ico">

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
  <![endif]-->
</head>

<body class="account-bg" style="background-image: url(/assets/landing/img/u8jQzd5.jpg); background-size: cover;">

  <header class="navbar navbar-inverse" role="banner">

    <div class="container">

      <div class="navbar-header">
        <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only">Toggle navigation</span>
          <i class="fa fa-cog"></i>
        </button>

        <a href="/" class="navbar-brand navbar-brand-img" style="font-family: 'Orbitron', sans-serif;">
          <img style="font-family: 'Orbitron', sans-serif;width: 67px;" src="/assets/landing/img/logomarscoinwallet.png" alt="Martian Republic Logo" >
        Martian Republic
        </a>
      </div> <!-- /.navbar-header -->

      <nav class="collapse navbar-collapse" role="navigation">
        <ul class="nav navbar-nav navbar-right">
          <li>
            <a href="/"><i class="fa fa-angle-double-left"></i> &nbsp;Back to Home</a>
          </li>
        </ul>
      </nav>

    </div> <!-- /.container -->

  </header>

  <div class="account-wrapper">

    <div class="account-body">

      <h3>Welcome back to the Martian Republic</h3>
@if(Session::has('message'))
<div class="alert alert-success">
            <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
            <strong>{{Session::get('message')}}</strong>
          </div>
@endif
      <h5>Please sign in to access.</h5>
 <!-- 
      <div class="row">

       <div class="col-sm-4">
          <a href="/social/google" class="btn btn-google btn-block ">
          <i class="fa fa-google"></i>
          &nbsp;&nbsp;Login
          </a>
        </div> 

        <div class="col-sm-4">
          <a href="/social/facebook" class="btn btn-facebook btn-block">
          <i class="fa fa-facebook"></i>
          &nbsp;&nbsp;Login 
          </a>
        </div> 

	<div class="col-sm-4">
          <a style="border-color:black;" href="/social/apple" class="btn btn-apple btn-block">
          <i class="fa fa-apple"></i>
          &nbsp;&nbsp;Login 
          </a>
        </div>  


      </div>

      <span class="account-or-social text-muted">OR, SIGN IN BELOW</span>

       <!-- /.row -->

       <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

      <form method="POST" class="form account-form" action="{{ route('login') }}">
            @csrf

        <div class="form-group">
          <input type="text" name="email" class="form-control" id="login-email" placeholder="Email" tabindex="1">
        </div> <!-- /.form-group -->

        <div class="form-group">
          <input type="password" name="password" class="form-control" id="login-password" placeholder="Password" tabindex="2">
        </div> <!-- /.form-group -->

        <div class="form-group clearfix">
          <div class="pull-left">
            <label class="checkbox-inline">
            <input type="checkbox" class="" value="" tabindex="3"> <small>Remember me</small>
            </label>
          </div>

          <div class="pull-right">
            <small><a href="{{ route('password.request') }}">Forgot Password?</a></small>
          </div>
        </div> <!-- /.form-group -->

        <div class="form-group">
          <button type="submit" class="btn btn-primary btn-block btn-lg" tabindex="4">Signin &nbsp; <i class="fa fa-play-circle"></i></button>
        </div> <!-- /.form-group -->


      </form>

      <div style="margin: 0 0 0px;">
      Don't have an account? &nbsp;
      <a href="{{ route('register') }}" class="">Create an account!</a>
</div>

    </div> <!-- /.account-body -->


  </div> <!-- /.account-wrapper -->

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Core JS -->
<script src="/assets/wallet/js/libs/jquery-1.10.2.min.js"></script>
<script src="/assets/wallet/js/libs/bootstrap.min.js"></script>

<!--[if lt IE 9]>
<script src="/assets/wallet/js/libs/excanvas.compiled.js"></script>
<![endif]-->
<!-- App JS -->
<script src="/assets/wallet/js/mvpready-core.js"></script>
<script src="/assets/wallet/js/mvpready-admin.js"></script>

<!-- Plugin JS -->
<script src="/assets/wallet/js/mvpready-account.js"></script>

<script>
  $(document).ready(function(){
    // localStorage.clear();
   
  });
  </script>


</body>
</html>
