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
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <link rel="stylesheet" href="/assets/wallet/js/plugins/magnific/magnific-popup.css">
    <script>var current_blob = null;</script>
</head>
<body class="">
    <div id="wrapper">
        <header class="navbar navbar-inverse" role="banner">
            <div class="container">
                <div class="navbar-header">
                    @include('wallet.header')
                </div>
                <nav class="collapse navbar-collapse" role="navigation">
                    @include('wallet.navbarleft')
                    @include('wallet.navbarright')
                </nav>
            </div>
        </header>
        @include('wallet.mainnav', array('active'=>'congress'))
        <div class="content">
            <div class="container">
                <div class="portlet">
                    <div class="portlet-body">
                        <h3>
                            Hello! It looks like you're not yet part of the Public Voter Registry. 
                        </h3>
                        <div class="col-lg-8">
                            <div class="row">
                                <h4 style="font-weight: 300">
                                Don't worry, though – it's an easy process to become a citizen. Our community thrives on the support and endorsement of its members. To get started, <a href="/citizen/all#new">post your application</a>. We invite you to <a href="/forum/t/12-new-members-endorsement-hub-join-the-republic-and-become-a-citizen">visit our forum thread</a> specifically designed for new members <a href="/citizen/all#all">seeking endorsements</a>.</h4> <h4 style="font-weight: 300">This is a great opportunity to introduce yourself and connect with citizens of the Republic who can endorse your addition to the Public Voter Registry. Looking forward to seeing you there and welcoming you as a citizen!<br>PS: If you like to learn more about the Martian Republic and how it works, take a look at our <a href="https://marscoin.gitbook.io/marscoin-documentation/">documentation</a></h4>

                            </div>
                        </div>
                        <div class="col-lg-4"></div>
                    </div>
                </div>
            </div> 
        </div> 
    </div> 
    <footer class="footer">
        @include('footer')
    </footer>
</body>
</html>


