<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->

<head>
    <title>Marscoin - Wallet Login</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald:400,300,700">
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@700&family=Orbitron:wght@500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/assets/wallet/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-admin.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-flat.css">
    <link rel="shortcut icon" href="favicon.ico">
</head>

<body class="account-bg" style="background-image: url(/assets/landing/img/mcolony.jpg); background-size: cover;">

    <header class="navbar navbar-inverse" role="banner">

        <div class="container">

            <div class="navbar-header">
                <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <i class="fa fa-cog"></i>
                </button>

                <a href="/" class="navbar-brand navbar-brand-img" style="font-family: 'Orbitron', sans-serif;">
                    <img style="font-family: 'Orbitron', sans-serif;width: 67px;"
                        src="/assets/landing/img/logomarscoinwallet.png" alt="Martian Republic Logo">
                    Martian Republic
                </a>
            </div> <!-- /.navbar-header -->

            <nav class="collapse navbar-collapse" role="navigation">
                <ul class="nav navbar-nav navbar-right">
                    <li>

                    </li>
                </ul>
            </nav>
        </div> <!-- /.container -->
    </header>

    <div class="account-wrapper">

        <div class="account-body">
            <h2>Enter 2FA Authentication Code</h2>
            <form id="form" class="form account-form" method="POST" action="/twofachallenge">
                @csrf
                <div  class="form-group" style="text-align: center;">
                    <input name="secret" id="secret"
                        style="height: 68px; font-size: 42px; font-weight: 700;  text-align: center;" type="text"
                        class="form-control" id="register-2fa" placeholder="Enter code" tabindex="1" autofocus>
                    <button type="submit"  class="btn btn-success btn-block btn-lg" tabindex="2">Complete 2FA challenge
                        &nbsp; <i id="btn" class="fa fa-arrow-right"></i>
                    </button>
                </div>
                <input type="hidden" value="meow" class="local" name="local" />

            </form>
            <a href="/logout"><i class="fa fa-angle-double-left"></i> &nbsp;Back to Login</a>
        </div>


    </div>
    <script src="/assets/wallet/js/libs/jquery-1.10.2.min.js"></script>
    <script src="/assets/wallet/js/libs/bootstrap.min.js"></script>
    <script src="/assets/wallet/js/mvpready-core.js"></script>
    <script src="/assets/wallet/js/mvpready-admin.js"></script>
    <script src="/assets/wallet/js/mvpready-account.js"></script>
    <script>
        $(document).ready(function() {

            // document.onload = function() {
            //     localStorage.clear();

            // }
            let item = localStorage.getItem("key")

            if (item != null && wordCount(item) == 12) {
                $(".local").val("true")
            } else {
                $(".local").val("false")
            }

            function wordCount(str) {
                return str.split(" ").length;
            }

        })
    </script>
    <script type="text/javascript">

    $(document).ready(function() {

        $("#secret").keyup(function() {
            if ($(this).val().length >= 6) {
                $("#btn").removeClass('fa-arrow-right');
                $("#btn").addClass('fa-spinner fa-spin');
                $("#form").submit();
            }
        });
    });
    </script>
</body>

</html>
