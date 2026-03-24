<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <title>Congress - Martian Republic</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Martian Republic Congress - Direct democracy on Mars">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald:400,300,700">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@700&family=Orbitron:wght@500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400..900&display=swap" rel="stylesheet">
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
                    <div class="portlet-body" style="padding: 30px;">
                        <h2 style="font-family: 'Orbitron', sans-serif; margin-bottom: 20px;">
                            <i class="fa fa-university"></i> Martian Congress
                        </h2>
                        <p style="font-size: 16px; font-weight: 300; line-height: 1.8; max-width: 800px;">
                            The Martian Congress is the legislative body of the Martian Republic. Citizens propose and vote on measures that shape the future of Mars governance. All proposals and votes are permanently recorded on the Marscoin blockchain for full transparency.
                        </p>

                        <div class="row" style="margin-top: 30px;">
                            <div class="col-lg-4 col-md-6" style="margin-bottom: 20px;">
                                <div class="portlet" style="padding: 20px; text-align: center; min-height: 180px;">
                                    <i class="fa fa-file-text-o fa-3x" style="color: #e74c3c; margin-bottom: 15px;"></i>
                                    <h4>Proposals</h4>
                                    <p style="font-weight: 300;">Citizens submit proposals for community review and voting.</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6" style="margin-bottom: 20px;">
                                <div class="portlet" style="padding: 20px; text-align: center; min-height: 180px;">
                                    <i class="fa fa-check-square-o fa-3x" style="color: #e74c3c; margin-bottom: 15px;"></i>
                                    <h4>Voting</h4>
                                    <p style="font-weight: 300;">Each citizen gets one vote, cryptographically verified on-chain.</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6" style="margin-bottom: 20px;">
                                <div class="portlet" style="padding: 20px; text-align: center; min-height: 180px;">
                                    <i class="fa fa-link fa-3x" style="color: #e74c3c; margin-bottom: 15px;"></i>
                                    <h4>On-Chain</h4>
                                    <p style="font-weight: 300;">Every vote is immutably recorded on the Marscoin blockchain.</p>
                                </div>
                            </div>
                        </div>

                        @auth
                            <div style="margin-top: 20px;">
                                <a href="/congress/voting" class="btn btn-danger btn-lg">
                                    <i class="fa fa-gavel"></i> Enter Congress Hall
                                </a>
                            </div>
                        @else
                            <div style="margin-top: 20px; padding: 20px; background: #f8f9fa; border-radius: 6px;">
                                <p style="margin: 0; font-size: 15px;">
                                    <i class="fa fa-info-circle"></i>
                                    <a href="/login">Sign in</a> to participate in proposals and voting, or
                                    <a href="/signup">become a citizen</a> to join the Republic.
                                </p>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer">
        @include('footer')
    </footer>
    @livewireScripts
</body>
</html>
