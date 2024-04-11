<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <title>Marscoin Wallet</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ Session::token() }}">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald:400,300,700">
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@700&family=Orbitron:wght@500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/assets/wallet/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/bootstrap.css">
    <link rel="stylesheet" href="/assets/wallet/css/hd/hd.css">
    <link rel="stylesheet" href="/assets/wallet/js/plugins/magnific/magnific-popup.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-admin.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-admin-extended.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-flat.css">
    <link rel="stylesheet" href="/assets/wallet/css/jquery.steps.css">

    <link rel="shortcut icon" href="/favicon.ico">

    <script src="/assets/wallet/js/dist/bundle.js"></script>
    <style>
        /* span.qrcodeicon span {
            position: absolute;
            display: block;
            top: 7px;
            right: 21px;
            width: 18px;
            height: 18px;
            background: url('/assets/wallet/img/qrcode.png');
            cursor: pointer;
            z-index: 1;
        } */

        .mouse-box {
    width: 400px; /* Example width */
    height: 200px; /* Example height */
    position: relative; /* Ensures that the canvas can be absolutely positioned within */
}

.dot {
    /* ... your existing styles ... */
    z-index: 1000; /* high value to bring to front */
}
    </style>
    <script src="/assets/wallet/js/plugins/scan/qrcode-gen.min.js"></script>
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
        @include('wallet.mainnav', ['active' => 'wallet'])

        <div class="content">

            <div class="container">

                <div class="portlet">

                    <h3 class="portlet-title">
                        <u>Select Wallet</u>
                         <a style="float:right;" data-toggle="modal" href="#styledModal" class="btn-lg btn-primary demo-element" data-backdrop="static" data-keyboard="false">New Wallet</a>
                        <a style="float:right;" data-backdrop="static" data-keyboard="false" data-toggle="modal" href="#modalLogin" class="btn-lg btn-primary demo-element">Connect Wallet</a>
                    </h3>


                    {{-- Render Civic Wallet --}}
                    @if ($civic_wallet)
                        <div class="row">

                            <div class="col-md-5 col-sm-7 ">
                                <a data-toggle="modal" href="#unlockWalletModal" data-keyboard="false"
                                    class="wallet-card-link" data-wallet='{{ json_encode($civic_wallet, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_SLASHES) }}'
                                    id={{ $civic_wallet->public_addr }}>
                                    <div class="icon-stat wallet-card">

                                        <div class="row">
                                            <div class="col-xs-8 text-left">
                                                <h4>Civic Wallet: {{ $civic_wallet->wallet_type }}</h4>
                                                <span class="icon-stat-label">{{ $civic_wallet->public_addr }}</span>
                                                <!-- /.icon-stat-label -->
                                                <span class="icon-stat-value">${{$civic_balance}}</span> <!-- /.icon-stat-value -->

                                                <div style="display: flex; flex-direction: column;">


                                                    <p
                                                        style="display: flex; flex-direction: row; align-items: center; justify-content: space-between; margin: 0; width: 50%;">
                                                        Application:<i
                                                            class="fa fa-{{ $applied ? 'check bg-success' : 'times bg-primary' }} "
                                                            style="padding: .5rem; margin: .5rem; border-radius: 4px">
                                                        </i>
                                                    </p>

                                                    <p
                                                        style="display: flex; flex-direction: row; align-items: center; justify-content: space-between; margin: 0; width: 50%;">
                                                        General Public:<i
                                                            class="fa fa-{{ $general_public ? 'check bg-success' : 'times bg-primary' }} "
                                                            style="padding: .6rem; margin: .5rem; border-radius: 4px">
                                                        </i>
                                                    </p>

                                                    <p
                                                        style="display: flex; flex-direction: row; align-items: center; justify-content: space-between; margin: 0; width: 50%;">
                                                        Citizen:<i
                                                            class="fa fa-{{ $citizen ? 'check bg-success' : 'times bg-primary' }} "
                                                            style="padding: .6rem; margin: .5rem; border-radius: 4px">
                                                        </i>
                                                    </p>


                                                </div>


                                            </div><!-- /.col-xs-8 -->

                                            <div class="col-xs-4 text-center">
                                                <i class="fa fa-dollar icon-stat-visual bg-primary"></i>
                                                <i class="fa fa-user icon-stat-visual bg-secondary"></i>

                                                <!-- /.icon-stat-visual -->
                                            </div><!-- /.col-xs-4 -->
                                        </div><!-- /.row -->

                                        @isset($civic_wallet->opened_at)
                                            <div class="icon-stat-footer">
                                                <i class="fa fa-clock-o"></i> Opened: {{ \Carbon\Carbon::parse($civic_wallet->opened_at)->toFormattedDateString() }} ({{ \Carbon\Carbon::parse($civic_wallet->opened_at)->diffForHumans() }})
                                            </div>
                                        @endisset



                                    </div> <!-- /.icon-stat -->
                                </a>

                            </div>

                        </div>
                    @endif




                    {{-- Render all existing wallets --}}


                    @if ($wallets)
                        @foreach ($wallets as $wallet)
                            <div class="row">
                                <div class="col-md-5 col-sm-7">
                                <a  data-toggle="modal" @if($wallet->wallet_type == "Imported") href="#modalLogin" @else href="#unlockWalletModal" @endif data-keyboard="false"
                                        class="wallet-card-link" data-wallet='{{ json_encode($wallet, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_SLASHES) }}'
                                        id={{ $wallet->public_addr }}>
                                        <div class="icon-stat wallet-card">
                                            <div class="row">
                                               
                                            <div class="col-xs-8 text-left">
                                                    <h4>{{ $wallet->wallet_type }}</h4>
                                                    <span class="icon-stat-label">{{ $wallet->public_addr }}</span>
                                                    <!-- /.icon-stat-label -->
                                                    <span class="icon-stat-value">${{$wallet->balance}}</span>
                                                    <!-- /.icon-stat-value -->
                                                </div><!-- /.col-xs-8 -->

                                                <div class="col-xs-4 text-center">
                                                    <i class="fa fa-dollar icon-stat-visual bg-primary"></i>
                                                    <!-- /.icon-stat-visual -->
                                                </div><!-- /.col-xs-4 -->
                                            </div><!-- /.row -->

                                            <div class="icon-stat-footer">
                                                <i class="fa fa-clock-o"></i> Opened: {{ $wallet->created_at }}

                                                <button style="float: right; position: relative;" type="button" class="btn btn-danger btn-xs delete-wallet-button" data-wallet-id="{{ $wallet->id }}" style="margin-top: 5px;">
                                                <i class="fa fa-times"></i> Forget
                                                </button>
                                            </div>

                                        </div> <!-- /.icon-stat -->

                                    </a>
                                </div>

                            </div>
                        @endforeach

                    @endif


                    {{-- Render First time wallet banner OR exisiting card banner --}}
                    @if ($wallets || $civic_wallet)

                    @else
                        <div class="portlet-body"
                            style="display: flex; justify-content: center; align-items: center; flex-direction: column; background: transparent">




                            <div style="display: flex; justify-content: space-around; align-items: center">
                                <h2>Create new Marscoin wallet or Login to existing wallet</h2>

                            </div>

                            <div class="panel-group accordion-panel" id="accordion-paneled"
                                style="display: flex; justify-content: space-evenly; align-items: center; width: 40%; margin: 40px">


                                <a data-toggle="modal" href="#styledModal" class="btn-lg btn-primary demo-element"
                                    data-backdrop="static" data-keyboard="false">New Wallet</a>
                                <h4>OR</h4>
                                <a data-backdrop="static" data-keyboard="false" data-toggle="modal"
                                    href="#modalLogin" class="btn-lg btn-primary demo-element">Connect Wallet</a>




                            </div> <!-- /.accordion -->




                        </div> <!-- /.portlet-body -->
                    @endif

                    {{-- render this if !civic_wallet && !hd_wallet --}}




                </div> <!-- /.portlet -->


            </div> <!-- /.container -->


        </div> <!-- .content -->


    </div> <!-- /#wrapper -->
    <!--------------------------------------->
    <!------------- UNLOCK WALLET ----------->
    <div id="unlockWalletModal" class="modal modal-styled fade">
        <div class="modal-dialog">

            <div class="modal-content">


                <div class="modal-header">
                    <h3 class="modal-title">Unlock Wallet</h3>
                </div> <!-- /.modal-header -->


                <form class="form account-form " id="wallet-unlocker" method="POST"
                    action="/wallet/dashboard/hd-open">
                    @csrf

                <div class=""
                    style="padding: 5rem; display: flex; justify-content: center; align-items: center; flex-direction: column">
                    <div class="row">

                        <h4 class="unlock-name" style="text-align: center"></h4>
                        <h2 class="unlock-addy"></h2>
                    </div>


                    <div class="row" style="width: 50%;">


                        <input name="wallet" hidden id="selected_wallet"/>

                        <label for="name">Wallet Password</label>
                        <input type="password" id="unlock-password" name="unlock-password" class="form-control"
                            data-required="true" style="width: 100%">


                        <div class="row d-flex justify-content-center text-center" style="padding-top: 5rem;">

                            <button id="unlock-wallet" type="submit" class="btn btn-primary"
                                style="">Unlock</button>
                        </div>
                    </div>

                </div>
                </form>

            </div>

        </div>






    </div>





    <!--------------------------------------->
    <!------- OPEN WALLET Modal Start ------->

    <div id="styledModal" class="modal modal-styled fade">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 class="modal-title">Open MARS Wallet</h3>
                </div> <!-- /.modal-header -->

                <form class="form account-form" method="POST" action="/wallet/createwallet">
                    @csrf

                    <div class="modal-body">

                        <div class="col-md-3 col-sm-5">

                            <ul id="myTab" class="nav nav-pills nav-stacked">
                                <li class="active tab-1 tab">
                                    <a href="#entropy" data-toggle="tab"><i class="fa fa-puzzle-piece"></i>
                                        &nbsp;&nbsp;Entropy
                                    </a>
                                </li>

                                <li class="disabled tab-2 tab">
                                    <a href="#mnemonic" data-toggle="tab"><i class="fa fa-key disabled"></i>
                                        &nbsp;&nbsp;Mnemonic
                                    </a>
                                </li>
                                <li class="disabled tab-3 tab">
                                    <a href="#done" data-toggle="tab"><i class="fa fa-rocket disabled"></i>
                                        &nbsp;&nbsp;Generate Wallet
                                    </a>
                                </li>


                            </ul>

                        </div> <!-- /.col -->





                        <div id="myTabContent" class="col-md-9 col-sm-7 tab-content stacked-content">

                            {{-- <form class="" method="POST" action="/wallet/createwallet"> --}}

                            <div class="tab-pane fade active in" id="entropy">
                                <div>
                                    <div class="title-help">
                                        <h2> Generate Entropy </h2>
                                        <a class="btn btn-default demo-element ui-popover" data-toggle="tooltip"
                                            data-placement="right" data-trigger="hover"
                                            data-content="Generate randomness by wiggling your mouse inside the box. The more you wiggle the more random your private key will be. The more random your key is, the more secure it will be."
                                            title="" data-original-title="Entropy" href="#"><i
                                                class="fa fa-question-circle"></i></a>

                                    </div>


                                    <p> <strong>Note: </strong>
                                        Wiggle your mouse inside the box to create extra randomness when generating your
                                        wallets
                                        private key
                                    </p>

                                    <div class="container mouse-box">
                                    </div>

                                    <div id="progress-counter" style="font-size: 48px; position: absolute; top: 40%; left: 50%; transform: translate(-50%, -50%); z-index: 1001; color: #d74b4b;font-weight: 800">0%</div>


                                    <div class="progress progress-striped active">
                                        <div id="entropy-progress" class="progress-bar progress-bar-primary"
                                            role="progressbar" aria-valuenow="0" aria-valuemin="0"
                                            aria-valuemax="100" style="width: 0%">
                                            <span class="sr-only">0% Complete (primary)</span>
                                        </div>
                                    </div>

                                    <div class="success" style="display: none">
                                        <strong>
                                            <h4 class="success-message"> Randomness Complete <i
                                                    class="fa fa-check-circle-o" style="color: rgb(37, 206, 37)"></i>
                                            </h4>

                                        </strong>
                                    </div>

                                </div>

                                <div class="next-btn">
                                    <button href="#mnemonic" id="next-entropy" type="button"
                                        class="btn btn-primary" style="display: none">Next</button>
                                </div>



                            </div>


                            <div class="tab-pane fade" id="mnemonic" style="display: none" href="#mnemonic">
                                <div>




                                    <div class="title-help">

                                        <h2> Mnemonic </h2>

                                        <a class="btn btn-default left-margin  demo-element ui-popover"
                                            data-toggle="tooltip" data-placement="right" data-trigger="hover"
                                            data-content="This seed phrase is the key to your wallet. Write it down and store it somewhere safely or you can lose your funds."
                                            title="" data-original-title="Mnemonic = Seed Phrase"
                                            href="#"><i class="fa fa-question-circle"></i></a>



                                    </div>

                                    <p> <strong>Note: </strong> Write down your mnemonic. Without it you will not be
                                        able to
                                        log
                                        back in to your wallet. </p>

                                    <div class="mnemonic">
                                        <h2 class="mnemonic-text"></h2>
                                    </div>


                                    <div class="title-help">
                                        <h2> Backup Wallet </h2>
                                        <a class="btn btn-default left-margin demo-element ui-popover"
                                            data-toggle="tooltip" data-placement="right" data-trigger="hover"
                                            data-content="We will encrypt your mnemonic with a password you create in your browser. MartianRepublic.org will never have access to your wallet."
                                            title="" data-original-title="Backup Your Wallet" href="#"><i
                                                class="fa fa-question-circle"></i></a>
                                        {{-- <span> (Optional) </span> --}}

                                    </div>
                                    <div>



                                        <div class="btn-group " data-toggle="buttons">
                                            <label id="backup-phrase" class="btn btn-default active"
                                                style="width: 125px;">
                                                <input type="radio" name="options" id="option1"> Backup Phrase
                                            </label>
                                            {{-- <label id="no-backup-phrase" class="btn btn-default"
                                                style="width: 125px;">
                                                <input type="radio" name="options" id="option2"> No Backup
                                            </label> --}}
                                        </div>


                                        <div class="form-group password-encrypt-cont">
                                            <div class="password-encrypt">



                                                <label for="name">Password</label>
                                                <input type="password" id="password" name="password"
                                                    class="form-control parsley-validated" data-required="true" autocomplete="new-password">
                                                <label for="name">Re-Type Password</label>
                                                <input type="password" id="re-password" name="re-password"
                                                    class="form-control parsley-validated" data-required="true"  autocomplete="new-password">
                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <div class="next-btn" style="margin-top: -10px;">

                                    <button id="next-mnemonic" type="button" class="btn btn-primary ">Next</button>

                                </div>


                            </div> <!-- /.tab-pane -->




                            <div class="tab-pane fade" id="done">

                                <h2>Open Wallet</h2>

                                <input class="addr" id="public_addr" name="public_addr" style="display: none" />

                                <p>Public Address</p>


                                <div class="pub-addr">

                                    <h3 class="addr" name="public_addr"></h3>

                                    <i class="fa fa-copy copy-icon"> </i>
                                </div>

                                <div class="row">
                                    <p>Wallet Name</p>
                                    <input placeholder="MARS" class="form-control" name="wallet_name" maxlength="500" placeholder="Enter wallet name" value="MARS" />


                                </div>

                                <div class="row d-flex justify-content-center text-center" style="padding-top: 10px;">

                                    <button id="make-wallet" type="submit" class="btn btn-primary" style="">Open Wallet</button>
                                </div>

                            </div> <!-- /.tab-pane -->


                            {{-- </form > --}}

                        </div>





                    </div> <!-- /.modal-body -->

                </form>
                <!-- /.modal-footer -->

            </div> <!-- /.modal-content -->

        </div><!-- /.modal-dialog -->

    </div><!-- /.modal -->

    <!------- OPEN WALLET Modal End ------->
    <!------------------------------------->

    <div id="modalLogin" class="modal modal-styled fade">

        <div class="modal-dialog">

            <div class="modal-content">


                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 class="modal-title">Marscoin Wallet Login</h3>
                </div> <!-- /.modal-header -->


                {{-- <form class="form account-form" method="POST" action="/wallet/getwallet"> --}}

                <div class="modal-body ">

                    <div class="col-md-3 col-sm-5">

                        <ul id="loginTabs" class="nav nav-pills nav-stacked">
                            <li class="active">
                                <a href="#seedPhrase" data-toggle="tab"><i class="fa fa-puzzle-piece"></i>
                                    &nbsp;&nbsp;Unlock with Mnemonic
                                </a>
                            </li>
                            @if (empty($encrypted_seed))
                                <li>
                                    <a href="#passwordLogin" data-toggle="tab"><i class="fa fa-key"></i>
                                        &nbsp;&nbsp;Unlock with Password
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a href="#importWallet" data-toggle="tab"><i class="fa fa-upload"></i>
                                    &nbsp;&nbsp;Unlock with Keyfile
                                </a>
                            </li>

                        </ul>

                    </div> <!-- /.col -->


                    <div id="loginTabsContent" class="col-md-9 col-sm-7 tab-content stacked-content">

                        {{-- <form class="" method="POST" action="/wallet/createwallet"> --}}


                        <div class="tab-pane fade active in" id="seedPhrase">
                            <form class="form account-form wallet-getter-mnem" method="GET" action="">
                                <div class="row"
                                    style="display: flex; align-items: center; justify-content: center">
                                    <h3> Input seed phrase (mnemonic) in order </h3>
                                </div>
                                <div class="row"
                                    style="display: flex; align-items: center; justify-content: center">
                                    <div class="col-lg-10"
                                        style='width: 100%; display: flex; align-items: center; justify-content: center; flex-wrap: wrap; margin: 30px'>

                                        {{-- <label for="wallet-login-1">1</label> --}}
                                        <input name="wallet-login-1" id="wallet-login-1" class="seed-input"
                                            style="" placeholder="1." />

                                        {{-- <label for="wallet-login-2">2</label> --}}
                                        <input name="wallet-login-2" id="wallet-login-2" class="seed-input"
                                            style="" placeholder="2." />

                                        {{-- <label for="wallet-login-1">3</label> --}}
                                        <input name="wallet-login-3" id="wallet-login-3" class="seed-input"
                                            style="" placeholder="3." />

                                        {{-- <label for="wallet-login-1">4</label> --}}
                                        <input name="wallet-login-4" id="wallet-login-4" class="seed-input"
                                            style="" placeholder="4." />

                                        {{-- <label for="wallet-login-1">5</label> --}}
                                        <input name="wallet-login-5" id="wallet-login-5" class="seed-input"
                                            style="" placeholder="5." />

                                        {{-- <label for="wallet-login-1">6</label> --}}
                                        <input name="wallet-login-6" id="wallet-login-6" class="seed-input"
                                            style="" placeholder="6." />

                                        {{-- <label for="wallet-login-1">7</label> --}}
                                        <input name="wallet-login-7" id="wallet-login-7" class="seed-input"
                                            style="" placeholder="7." />

                                        {{-- <label for="wallet-login-1">8</label> --}}
                                        <input name="wallet-login-8" id="wallet-login-8" class="seed-input"
                                            style="" placeholder="8." />

                                        {{-- <label for="wallet-login-1">9</label> --}}
                                        <input name="wallet-login-9" id="wallet-login-9" class="seed-input"
                                            style="" placeholder="9." />

                                        {{-- <label for="wallet-login-1">10</label> --}}
                                        <input name="wallet-login-10" id="wallet-login-10" class="seed-input"
                                            style="" placeholder="10." />

                                        {{-- <label for="wallet-login-1">11</label> --}}
                                        <input name="wallet-login-11" id="wallet-login-11" class="seed-input"
                                            style="" placeholder="11." />

                                        {{-- <label for="wallet-login-1">12</label> --}}
                                        <input name="wallet-login-12" id="wallet-login-12" class="seed-input"
                                            style="" placeholder="12." />



                                    </div>
                                </div>
                                <div class="row"
                                    style="display: flex; align-items: center; justify-content: center">
                                    <div class="col-sm-12"
                                        style="display: flex; align-items: center; justify-content: center">
                                        <button id="login-wallet-mnemonic" type="submit" class="btn btn-primary"
                                            style="width: 20%; margin: 30px">Unlock</button>

                                    </div>
                                </div>
                            </form>

                        </div>
                        {{-- @if (empty($encrypted_seed))
                            <div class="tab-pane fade" id="passwordLogin">
                                <form class="form account-form wallet-getter" method="GET"
                                    action="/wallet/getwallet">
                                    <label for="name">Password</label>
                                    <input type="password" id="wallet-password" name="password"
                                        class="form-control parsley-validated" data-required="true">
                                    <button id="login-wallet-password" type="submit"
                                        class="btn btn-primary">Login</button>
                                </form>


                            </div>
                        @endif --}}


                        <div class="tab-pane fade in" id="importWallet">
                            <div>
                            <h2>Upload Marswallet JSON Key</h2>
                                <div class="form-group">

                                <input class="form-control" type="file" id="jsonFile" accept=".json" />
                                <button style="margin-top:10px;" class="btn" id="uploadButton"><i class="fa fa-upload"> </i> Upload</button>
                                </div>
                                
                            </div>

                        </div>








                    </div>







                </div>

                {{-- </form> --}}
            </div>
        </div>
    </div>



    <!------- Footer Start ------->

    <footer class="footer">
        @include('footer')
    </footer>

    <script src="/assets/wallet/js/dist/bundle.js"></script>
    <script src="/assets/wallet/js/dist/my_bundle.js"></script>
    <script src="/assets/wallet/js/libs/jquery-1.10.2.min.js"></script>
    <script src="/assets/wallet/js/libs/bootstrap.min.js"></script>
    <script src="/assets/wallet/js/jquery.slimscroll.min.js"></script>
    <script src="/assets/wallet/js/plugins/magnific/jquery.magnific-popup.min.js"></script>
    <script src="/assets/wallet/js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="/assets/wallet/js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script src="/assets/wallet/js/plugins/magnific/jquery.magnific-popup.js"></script>
    <script src="/assets/wallet/js/mvpready-core.js"></script>
    <script src="/assets/wallet/js/mvpready-admin.js"></script>
    <script src="/assets/wallet/js/mvpready-helpers.js"></script>
    <script src="/assets/wallet/js/demos/table_demo.js"></script>
    <script type="text/javascript">
    
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
    });  

        $(document).ready(function() {


            let iv = "{{ json_encode($iv) }}".replace("]", "").replace("[", "").split(",");
            iv = new Uint8Array(iv);



            let selected_wallet = null;


            $('.wallet-card-link').on("click", function(e) {

                var data = JSON.parse($(this).attr('data-wallet'))

                selected_wallet = data
                // now... handle data on the modal that popped open....

                $("#unlockWalletModal .unlock-name").text(data.wallet_type)
                $("#unlockWalletModal .unlock-addy").text(data.public_addr)



            })

            $("#next-mnemonic").click(() => {

                $(".dot").hide()

                 // Check if the password fields are empty
                var password = $('#password').val().trim();
                var rePassword = $('#re-password').val().trim();

                // If both password fields are empty, show a warning message
                if (password === '' && rePassword === '') {
                    var userConfirmed = confirm("Warning: If you do not provide a password, you will not be able to unlock the wallet by password and access may be lost unless you have written down the seed phrase or downloaded the keyfile. Do you want to continue without setting a password?");
                    
                    // If user did not confirm, stop the function
                    if (!userConfirmed) {
                        return;
                    }
                }
                
                $(".tab-3").removeClass("disabled").addClass("active")

                $(".tab-2").removeClass("active")
                $("#mnemonic").removeClass("active in")
                $("#done").show()
                $("#done").addClass("active in")
                $("#next-mnemonic").hide();
                $("#mnemonic").hide()
                $("#next-entropy").hide()

            })

            $(".tab-1").click(() => {
                $("#entropy").show()
                $("#mnemonic").hide()
                $("#done").hide()
            })
            $(".tab-2").click(() => {
                $("#entropy").hide()
                $("#mnemonic").show()
                $("#done").hide()
            })
            $(".tab-3").click(() => {
                $("#entropy").hide()
                $("#done").show()
                $("#mnemonic").hide()
            })



            // Prevent going to other tabs in tab-pills
            $(".nav-pills li").on("click", function(e) {

                //console.log($(this))
                if ($(this).hasClass("disabled")) {
                    e.preventDefault();
                    return false;
                }
            });

            // ===================================================================
            // ================= Handle Password Client Encryption ui logic ======
            // ===================================================================
            $("#no-backup-phrase").click(function(e) {

                if ($(this).hasClass("active")) {
                    e.preventDefault;
                } else {

                    $(".password-encrypt").toggle();

                }
            });
            $("#backup-phrase").click(function(e) {

                if ($(this).hasClass("active")) {
                    e.preventDefault;
                } else {

                    $(".password-encrypt").toggle();
                }
            });

            if ($("#no-backup-phrase.active")) {

            } else {

            }


            $("#no-backup-phrase").click(() => {
                $('#password').val('');
                $("#re-password").val('');
            })

            // ===================================================================
            // ============== Handle Make Wallet w/ Password  ====================
            // ===================================================================

            function handleMakeWallet() {
                var mnem;
                var password;
                var re_password;
                var hashed_password;
                var hashed_re_password;

                var encrypted_mnem;

                // ensure marswallet.json conf is retreived
                //var salt = "{{ $SALT }}"


                $('#password').on('input', function() {

                    mnem = $('.mnemonic-text').html();
                    password = $('#password').val().replace(/\s+/g, '');

                    // hashed_password = my_bundle.pbkdf2.pbkdf2Sync(
                    //     password,
                    //     "{{ $SALT }}", 1, 16, 'sha512').toString('hex')

                    hashed_password = hashPassword(password)

                    // console.log("hashed-pass: ", hashed_password)

                    encrypted_mnem = my_bundle.encrypt(mnem, hashed_password, iv)

                    // console.log("enc:", encrypted_mnem)


                });


                $('#re-password').on('input', function() {

                    mnem = $('.mnemonic-text').html();
                    re_password = $("#re-password").val().replace(/\s+/g, '');

                    hashed_re_password = hashPassword(re_password)

                    //   console.log("HASHED RE-pass:", hashed_re_password)
                    //   console.log("enc-mnem:", encrypted_mnem)
                    //   console.log("dec-mnem:", my_bundle.decrypt(encrypted_mnem, hashed_re_password, iv))
                });


                // set the input as the encryption.
                $("#next-mnemonic").click(() => {
                    $("#password").val(encrypted_mnem);
                    $("#re-password").val(hashed_re_password);

                })

            }
            handleMakeWallet()

            const hashPassword = (passcode) => {

                const ret = my_bundle.pbkdf2.pbkdf2Sync(
                    passcode,
                    "{{ $SALT }}", 1, 16, 'sha512').toString('hex')

                return ret
            }


            const hashPasswordWithRounds = (passcode, rounds) => {

                for (let i = 0; i < rounds; i++) {
                    console.log(`hash round: ${i}`)

                }


                const ret = my_bundle.pbkdf2.pbkdf2Sync(
                    passcode,
                    "{{ $SALT }}", 1, 16, 'sha512').toString('hex')

                return ret
            }


            //
            // ===================================================================
            // ============= Generate MARS HD Wallet: input=> mnemonic ===========
            // ===================================================================


            // MARS Derivation Path
            const Marscoin = {
                mainnet: {
                    messagePrefix: "\x19Marscoin Signed Message:\n",
                    bech32: "M",
                    bip44: 2,
                    bip32: {
                        public: 0x043587cf,
                        private: 0x04358394,
                    },
                    pubKeyHash: 0x32,
                    scriptHash: 0x32,
                    wif: 0x80,
                },
            };

            const genSeed = (mnemonic) => {
                // console.log("SALT: {{ $SALT }}")
                //mnemonic = "invite feature forget axis radar stone bind squirrel dog crash 

                const seed = my_bundle.bip39.mnemonicToSeedSync(mnemonic.trim());
                const root = my_bundle.bitcoin.bip32.fromSeed(seed, Marscoin.mainnet)
                const child = root.derivePath("m/44'/2'/0'").neutered();
                let tpub = child.toBase58()
                const hdNode = my_bundle.bip32.fromBase58(tpub, Marscoin.mainnet)
                const node = hdNode.derive(0)
                const addy = nodeToLegacyAddress(node.derive(0))
                const publicKey = node.publicKey.toString('hex')
                const resp = {
                    address: addy,
                    pubKey: publicKey,
                    xprv: root.toBase58(),
                    mnemonic: mnemonic
                }
                return resp;
            };



            function nodeToLegacyAddress(hdNode) {
                return my_bundle.bitcoin.payments.p2pkh({
                    pubkey: hdNode.publicKey,
                    network: Marscoin.mainnet,
                }).address;
            }


            let captureStart = true;
            var entropy = [];


            let progress = 0;
            const maxEntropyLength = 24; // Define max entropy length

            // Update the dot styles globally
            var style = document.createElement('style');
            document.head.appendChild(style);
            style.sheet.insertRule(`
                .dot {
                    position: absolute;
                    width: 5px;
                    height: 5px;
                    border-radius: 50%;
                    background: black;
                    pointer-events: none;
                    z-index: 1000; // Make sure this is above the modal
                }`, 0);



            $(document).on("mousemove", ".mouse-box", function(e) {

                var mnemonic;
                const percent_increase = 5
                var increase = percent_increase * entropy.length

                // Entropy Logic
                const MAX_LEN = 24; // size of entropy's array
                const now = Date.now();
                if (now >= 1 && (now % 10) !== 0) return;

                // mouse movement cords
                const px = e.pageX;
                const py = e.pageY;

                var word_list_length = 2048
                var elem = $('.mouse-box')[0].getBoundingClientRect()
                var w = elem.width;
                var h = elem.height;

                var cell_dim = w / Math.sqrt(word_list_length);
                var cell_count = (w / cell_dim)

                var x_pos = (px - elem.left) / cell_dim;
                var y_pos = (py - elem.top) / cell_dim;

                //var cells = cell_dim * 

                var cell = x_pos + (cell_count * y_pos);
                var ret = Math.round(cell)


                entropy.push(ret)

                if (increase < 100) {
                    // Create and append dot
                    var dot = document.createElement('div');
                    dot.className = 'dot';
                    dot.style.left = `${e.clientX}px`;
                    dot.style.top = `${e.clientY}px`;
                    dot.style.zIndex = 1100;
                    document.body.appendChild(dot);
                    document.getElementById('progress-counter').innerText = `${increase.toFixed(1)}%`;
                }else{
                    document.getElementById('progress-counter').innerText = "100%";
                }




                // increase progress bar as entropy increases
                $("#entropy-progress").css("width", `${increase}%`)


                // Ensure enough entropy has been created
                // Once entropy is completed then...
                if (increase == 100) {


                    $("#entropy-progress").css("width", `100%`)
                    $(".success").show()
                    $("#next-entropy").show();

                    // =====================================================================================

                    // Generating seed with entropy
                    // 1) shuffle entropy
                    // 2) Select 16 numbers
                    // 3) entropyToMnemonic -> mnemonicToSeed...

                    shuffle(entropy);

                    var final_entropy = entropy.slice(0, 16)


                    mnemonic = my_bundle.bip39.entropyToMnemonic(final_entropy)

                    // use genSeed function to make seed
                    const wallet = genSeed(mnemonic)

                    //console.log(wallet)

                    $(".addr").html(wallet.address)
                    $(".addr").val(wallet.address)




                    // ======================================================================================

                    // NEXT Button dependent on entropy completing
                    $("#next-entropy").click((e) => {

                        $(".mnemonic-text").html(mnemonic);

                        $("#next").addClass("tab-2-unlocked tab-3 unlocked")

                        $(".tab-2").removeClass("disabled")
                        $(".tab-2").addClass("active")

                        $(".tab-1").removeClass("active")
                        $("#entropy").removeClass("active in")
                        $("#entropy").hide()
                        $("#mnemonic").show()
                        $("#mnemonic").addClass("active in")
                        $("#next-entropy").hide();
                        $(".dot").hide();

                    })
                }

                // =====================================================================
                // =====================================================================
                // Reset Modal on close
                $("#styledModal").on('hide.bs.modal', function() {
                    increase = 0;
                    entropy.length = 0;
                    $("#entropy-progress").css("width", `0%`)
                    $(".success-message").html("")
                    $("#next").hide();
                    $(".tab-2").addClass("disabled")
                    $(".tab-2").removeClass("active")
                    $("#entropy").addClass("active in")
                    $("#mnemonic").removeClass("active in")
                    $(".tab-1").addClass("active")
                    progress = 0; // Reset progress
                    $("#progress-counter").text('0%');
                    $("#entropy-progress").css("width", `0%`);

                });
                // =====================================================================
                // if (!validation) {
                //     e.preventDefault();
                //     return false;
                // }




                // Shuffle array helper function...
                function shuffle(array) {
                    let currentIndex = array.length,
                        temporaryValue, randomIndex;
                    // While there remain elements to shuffle...
                    while (0 !== currentIndex) {
                        // Pick a remaining element...
                        randomIndex = Math.floor(Math.random() * currentIndex);
                        currentIndex -= 1;
                        // And swap it with the current element.
                        temporaryValue = array[currentIndex];
                        array[currentIndex] = array[randomIndex];
                        array[randomIndex] = temporaryValue;
                    }
                    return array;
                }


            


            });


            // Check if the mnemonic is valid and gens a pubaddr.......
            const checkMnemonic = (mnemonic) => {

                const root = new my_bundle.BIP84.fromMnemonic(mnemonic, null, false, 107);

                const child = root.deriveAccount(0);

                const account = new my_bundle.BIP84.fromZPrv(child, false, MARSCOIN);

                let resp = {
                    address: account.getAddress(0, false, 49),
                }

            }


             // LOGIN USING MNEMONIC INPUT
            $('#login-wallet-mnemonic').click(() => {
                // compile mnemonic
                var input_mnemonic = "";
                for (var i = 1; i < 13; i++) {

                    var mnem = $(`#wallet-login-${i}`).val();

                    input_mnemonic += `${mnem} `

                }
                //console.log(input_mnemonic)
                const response = genSeed(input_mnemonic)

                //console.log("response:", response)



                if ("{{ $wallets }}") {
                    if (response.address == "{{ $public_addr }}") {
                        // Logging in was successful... Opening wallet...
                        $(".wallet-getter-mnem").attr("action", "/wallet/getwallet")

                        localStorage.setItem("key", response.decrypted)
                        //console.error("Item Succesfully locally stored")
                    }
                    if (response.address != "")
                    {
                        var postData = {
                            password: '',
                            public_addr: response.address,
                            wallet_name: 'Imported' // Set the wallet name to 'Imported'
                        };
                        
                        $.post('/wallet/createwallet', postData)
                        .done(function(data) {
                            localStorage.setItem("key", response.decrypted);
                            location.href="/wallet/dashboard/hd-open";
                        })
                        .fail(function(error) {
                            // Handle errors here
                            console.error("Error occurred: ", error);
                        });

                    } 
                    else {
                        $(".wallet-getter-mnem").attr("action", "/wallet/failwallet")
                    }
                }

            })

            // LOGIN USING KEYFILE 
            document.getElementById('uploadButton').addEventListener('click', function() {
                var fileInput = document.getElementById('jsonFile');

                if (!fileInput.files.length) {
                    alert('Please select a file.');
                    return;
                }

                var file = fileInput.files[0];
                var reader = new FileReader();

                reader.onload = function(e) {
                    try {
                        var jsonData = JSON.parse(e.target.result);
                        var mnemonic = jsonData.key; // Assuming the JSON structure is { "key": "mnemonic words here" }
                        processMnemonic(mnemonic);
                    } catch (error) {
                        alert('Error reading or parsing the file.');
                        console.error('Error:', error);
                    }
                };

                reader.readAsText(file);
            });

            function processMnemonic(mnemonic) {
                // Clean up the mnemonic string if necessary
                mnemonic = mnemonic.trim();

                // Now, you can use your existing function to generate the seed and handle the rest
                const response = genSeed(mnemonic);

                if (response && response.address) {
                    // Redirect or do further processing as needed
                    
                    var postData = {
                        password: '',
                        public_addr: response.address,
                        wallet_name: 'Imported' // Set the wallet name to 'Imported'
                    };
                    
                    $.post('/wallet/createwallet', postData)
                    .done(function(data) {
                        console.log('Mnemonic processed and key stored in localStorage.');
                        localStorage.setItem("key", response.decrypted);
                        location.href="/wallet/dashboard/hd-open";
                    })
                    .fail(function(error) {
                        // Handle errors here
                        console.error("Error occurred: ", error);
                    });

                } else {
                    // Handle error
                    console.error('Failed to process mnemonic.');
                }
            }

            // LOGIN USING PASSWORD INPUT
            $('#login-wallet-password').click(() => {
                // console.log("SALT: {{ $SALT }}")
                // compile mnemonic

                var wallet_password = $("#wallet-password").val().replace(/\s+/g, '');
                // console.log(wallet_password)

                const hashed = hashPassword(wallet_password);
                //console.log("hashed:", hashed)

                const encrypted_mnem = "{{ $encrypted_seed }}".replace(/\s+/g, '');
                //const encrypted = my_bundle.encrypt("face they lemon ignore link crop above thing buffalo tide category soup", hashed)
                //console.log("Encrypted: ", encrypted)

                const decrypted = my_bundle.decrypt(encrypted_mnem, hashed, iv).trim()

                // console.log("Encrypted SEED: {{ $encrypted_seed }}")
                // console.log("MNEM:", decrypted)


                const response = genSeed(decrypted)

                // console.log("response:", response)
                if (response.address == "{{ $public_addr }}") {
                    // Logging in was successful... Opening wallet...
                    localStorage.setItem("key", decrypted)
                    //      console.error("Item Succesfully locally stored")
                } else {
                    $(".wallet-getter").attr("action", "/wallet/failwallet")

                }
                // Logging in was NOT-successful... Prompting user to retry login.

            })



            // UNLOCK WALLET from list of wallets......
            // $('#unlock-wallet').click(() => {
            //     // console.log("SALT: {{ $SALT }}")
            //     // compile mnemonic
            //     console.log("unlocking...")

            //     var wallet_password = $("#unlock-password").val().replace(/\s+/g, '');
            //     // console.log(wallet_password)

            //     const hashed = hashPassword(wallet_password);


            //     const user_wallet = selected_wallet
            //     //console.log("hashed:", hashed)

            //     const encrypted_mnem = user_wallet.encrypted_seed.replace(/\s+/g, '');
            //     //const encrypted = my_bundle.encrypt("face they lemon ignore link crop above thing buffalo tide category soup", hashed)
            //     //console.log("Encrypted: ", encrypted)

            //     const decrypted = my_bundle.decrypt(encrypted_mnem, hashed, iv).trim()

            //     // console.log("Encrypted SEED: {{ $encrypted_seed }}")
            //     // console.log("MNEM:", decrypted)


            //     const response = genSeed(decrypted)ion




            //     // console.log("response:", response)
            //     if (response.address == user_wallet.public_addr) {
            //         // Logging in was successful... Opening wallet...
            //         // localStorage.setItem("key", decrypted)
            //         localStorage.setItem("key", encrypted_mnem)


            //         //      console.error("Item Succesfully locally stored")
            //     } else {


            //         $(".wallet-getter").attr("action", "/wallet/failwallet")


            //     }
            //     // Logging in was NOT-successful... Prompting user to retry login.

            // })


            $("#unlock-wallet").click(function(e) {
                // do your validation here ...


                var validated = false;


                console.log("unlocking...")

                var wallet_password = $("#unlock-password").val().replace(/\s+/g, '');
                // console.log(wallet_password)

                const hashed = hashPassword(wallet_password);


                const user_wallet = selected_wallet
                //console.log("hashed:", hashed)

                const encrypted_mnem = user_wallet.encrypted_seed.replace(/\s+/g, '');
                //const encrypted = my_bundle.encrypt("face they lemon ignore link crop above thing buffalo tide category soup", hashed)
                //console.log("Encrypted: ", encrypted)

                const decrypted = my_bundle.decrypt(encrypted_mnem, hashed, iv).trim()

                // console.log("Encrypted SEED: {{ $encrypted_seed }}")
                // console.log("MNEM:", decrypted)


                const response = genSeed(decrypted)




                // console.log("response:", response)
                if (response.address == user_wallet.public_addr) {
                    // Logging in was successful... Opening wallet...

                    flushLocalStorage()
                    console.log("success...")
                    // console.table(selected_wallet)
                    //localStorage.setItem("key", encrypted_mnem)
                    localStorage.setItem("key", decrypted)

                    $("#selected_wallet").val(JSON.stringify(selected_wallet))


                    return true;
                    //      console.error("Item Succesfully locally stored")
                } else {

                    console.log("failure...")

                    validated = false
                    e.preventDefault();
                    window.location.reload()

                    return false;
                    // $(".wallet-getter").attr("action", "/wallet/failwallet")
                }

                // if (!validation) {

                // }


            });




            // test mnemonic...
            //eternal robot record fade pretty best stem movie recycle spend legend fence

            //retrieveWallet()


            const retrieveWalletPassword = () => {
                $('#login-wallet-mnemonic').click(() => {


                })



            }

            // =================================================================================================
            // =================================================================================================
            // ================================================================================================= 

            function flushLocalStorage() {


                localStorage.clear();
                localStorage.removeItem('key');


                // fallback double check if key exists...
                if ("key" in localStorage)
                    localStorage.clear()

                return
            }


        });


        $('.delete-wallet-button').click(function() {
        var walletId = $(this).data('wallet-id');
        if (confirm('Are you sure you want to delete this wallet?')) {
            $.ajax({
                url: '/wallet/forget', // Adjust the URL to your endpoint
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token
                    hdwallet_id: walletId
                },
                success: function(result) {
                    // Remove the wallet element from the DOM or refresh the page
                    location.reload(); // This is a simple way to refresh the page
                },
                error: function(xhr, status, error) {
                    // Handle error
                    alert('Error: Wallet could not be deleted.');
                }
            });
        }
    });


</script>

<script src="/assets/wallet/js/demos/parsley.js"></script>
<script src="/assets/wallet/js/libs/jquery.steps.js"></script>
<script src="/assets/wallet/js/mvpready-core.js"></script>
<script src="/assets/wallet/js/mvpready-helpers.js"></script>
<script src="/assets/wallet/js/mvpready-admin.js"></script>

@livewireScripts
</body>

</html>
