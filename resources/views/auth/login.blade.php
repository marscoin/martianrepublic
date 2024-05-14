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
  <style>
    .note {
  position: relative;
  width: 500px;
  padding: 45px;
  text-align: justify;
  overflow: hidden;
  border-radius: 10px;
}
.note:before {
  content: "";
  display: block; 
  width: 0;
  position: absolute;
  top: -1px;
  right: -1px;
  border: 30px solid transparent;
  border-bottom-left-radius: 10px;
  border-left: 30px solid #fff;
  border-bottom: 30px solid #fff; 
  box-shadow: 0px 2px 4px rgba(0,0,0,0.4), -1px 1px 4px rgba(0,0,0,0.4);
  background-color: #eee;
}
.note .qr-icon {
  position: absolute;
  top: 5px;
  right: 5px;
  width: 22px;
  height: 22px;
  background: url('/assets/landing/img/qricon.png') no-repeat center center;
  background-size: cover; 
}
.cardBack {
    display: none;
}

.cardBack.flipped {
    display: block;
    width: 500px;
padding: 45px;
border-radius: 10px;
}

.cardFront.flipped {
    display: none;
}

.shield {
    margin: 0;
    padding: 3px 0 3px 23px;
    list-style: none;
    display: flex;
    align-items: center;
}

.spinner {
    -webkit-animation: rotator 1.4s linear infinite;
    animation: rotator 1.4s linear infinite;
}

    </style>
@livewireStyles
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

  <form method="POST" class="form account-form" action="{{ route('login') }}">
            @csrf

    <div id="cardFront" class="account-body loginbox bg-white note cardFront">
      <a href="#" id="flip-btn" class="qr-icon"></a>
      <h3>Welcome back to the Martian Republic</h3>
        @if(Session::has('message'))
        <div class="alert alert-success">
                    <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
                    <strong>{{Session::get('message')}}</strong>
                  </div>
        @endif
      <h5>Please sign in to access.</h5>

        <x-auth-validation-errors class="mb-4" :errors="$errors" />

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


   

      <div style="margin: 0 0 0px;">
      Don't have an account? &nbsp;
      <a href="{{ route('register') }}" class="">Create an account!</a>
</div>

    </div> <!-- /.account-body -->
    <div id="cardBack" class="account-body  loginbox bg-white cardBack" >
                <div class="card-body">
                <div class="auth-full-page-content rounded d-flex p-3 my-2" bis_skin_checked="1">
                    <div class="w-100" bis_skin_checked="1">
                    <div class="d-flex flex-column h-100" bis_skin_checked="1">
                        <div class="auth-content my-auto" bis_skin_checked="1">
                        <div class="text-center" bis_skin_checked="1">
                            <a href="#" id="flip-btn4" class="mb-0 fw-semibold" style="font-size: 40px; color: #ed1c24;">Go back</a>
                            <p style="font-size: 20px;" class="text-muted">
                            Scan with Martian Republic Mobile <a class="fw-semibold" id="flip-btn" style="color: #ed1c24;" href="#">app</a><br> to securely log in.
                            </p>
                        </div>

                        <div class="mt-4 pt-3 text-center" bis_skin_checked="1">
        
                            <div class="d-flex justify-content-center mt-3">
                                <div class="p-2 border rounded">
                                @livewire('q-r-login')
                                </div>
                            </div>
                            
                        </div>
                        <div class="mt-4 pt-3 text-center" bis_skin_checked="1">
                            <p style="font-size: 15px;" class="text-muted mb-1">
                            No account yet?
                            </p>
                            <a class="link-primary"  href="#" style="cursor: pointer; font-size: 15px;">
                            Sign up with MartianRepublic Mobile App
                            </a>
                        </div>

                    </div>

                    </div>
                    </div>
                </div>
                </div>
                <div class="text-left" bis_skin_checked="1">
                <img src="/assets/landing/img/secured.avif" style="float: right;height: 46px;margin-right: 6px;/* margin-bottom: 10px; */margin: 19px;">
                <p style="font-size: 10px;margin: 20px;" class="text-muted ">Biometrically <br> & Cryptographically<br> Secured<br>  
                  </p>
                </div>
            </div>

            </form>
  </div> <!-- /.account-wrapper -->

<script src="/assets/wallet/js/libs/jquery-1.10.2.min.js"></script>
<script src="/assets/wallet/js/libs/bootstrap.min.js"></script>
<script src="/assets/wallet/js/mvpready-core.js"></script>
<script src="/assets/wallet/js/mvpready-admin.js"></script>
<script src="/assets/wallet/js/mvpready-account.js"></script>
<script>
  $(document).ready(function(){
   
  });
  </script>
 @livewireScripts
    <script>
        const front = document.getElementById('cardFront')
        const back = document.getElementById('cardBack')
        const btn = document.getElementById('flip-btn')
        const btn4 = document.getElementById('flip-btn4')

        function handleFlip() {
            front.classList.toggle('flipped')
            back.classList.toggle('flipped')
        }

        btn.addEventListener('click', handleFlip)
        btn4.addEventListener('click', handleFlip)

    </script>
    <script>
    let currentSid = '';

    document.addEventListener('DOMContentLoaded', () => {
        window.Livewire.on('sidUpdated', (data) => {
            currentSid = data[0].sid; // Update the current SID whenever the event is emitted
        });

        const checkAuthStatus = () => {
            if (!currentSid) return; // Don't proceed if SID is empty

            fetch(`/api/checkauth?sid=${currentSid}`) // Use the current SID
                .then(response => response.json())
                .then(data => {
                    if (data.authenticated) {
                        window.location.href = `/api/wauth?sid=${currentSid}`;
                    }
                })
                .catch(error => console.error('Error checking authentication status:', error));
        };

        setInterval(checkAuthStatus, 5000);
    });
    </script>



</body>
</html>
