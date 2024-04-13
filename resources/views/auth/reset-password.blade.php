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

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald:400,300,700">
  <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@700&family=Orbitron:wght@500&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="/assets/wallet/css/font-awesome.min.css">

  <link rel="stylesheet" href="/assets/wallet/css/bootstrap.min.css">

  <link rel="stylesheet" href="/assets/wallet/css/mvpready-admin.css">
  <link rel="stylesheet" href="/assets/wallet/css/mvpready-flat.css">
  <!-- <link href="/assets/wallet/css/custom.css" rel="stylesheet">-->

  <link rel="shortcut icon" href="/assets/favicon.ico">
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

            <h2>Password Reset</h2>

            <h5>Please select a new password</h5>

            @if (session('status'))
                <div>
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

 <form class="form account-form" method="POST"  action="{{ route('password.update') }}">
            @csrf

 <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
           
        <div class="form-group">
          <label for="signup-email" class="placeholder-hidden">Email Address</label>
          <input type="text" class="form-control" name="email" id="signup-email" placeholder="Your Email" tabindex="1" :value="old('email', $request->email)">
        </div> <!-- /.form-group -->

        
        <div class="form-group">
          <label for="login-password" class="placeholder-hidden">Password</label>
          <input type="password" name="password" class="form-control" id="password" placeholder="Password" tabindex="4">
        </div> <!-- /.form-group -->


        <div class="form-group">
          <label for="login-password" class="placeholder-hidden">Password</label>
          <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Password" tabindex="4">
        </div> <!-- /.form-group -->


            <div class="form-group">
              <button type="submit" class="btn btn-primary btn-block btn-lg" tabindex="4">Submit &nbsp; <i class="fa fa-play-circle"></i></button>
            </div> <!-- /.form-group -->

  </form>
            
          </div> <!-- /.account-body -->

        </div>

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



</body>
</html>
