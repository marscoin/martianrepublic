<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->

<head>
    <title>Marscoin Wallet</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Google Font: Open Sans -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald:400,300,700">
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@700&family=Orbitron:wght@500&display=swap"
        rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="/assets/wallet/css/font-awesome.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/assets/wallet/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/bootstrap.css">

    <link rel="stylesheet" href="/assets/wallet/css/hd/hd.css">


    <!-- App CSS -->
    <link rel="stylesheet" href="/assets/wallet/js/plugins/magnific/magnific-popup.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-admin.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-flat.css">
    <link rel="stylesheet" href="/assets/wallet/css/jquery.steps.css">



    <!-- <link href="/assets/wallet/css/custom.css" rel="stylesheet">-->
    <!-- Favicon -->
    <link rel="shortcut icon" href="/favicon.ico">

    <script src="/assets/wallet/js/dist/bundle.js"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
  <![endif]-->
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
    </style>
    <script src="/assets/wallet/js/plugins/scan/qrcode-gen.min.js"></script>
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
                        <u>Wallet</u>
                    </h3>

                    <div class="portlet-body"
                        style="display: flex; justify-content: center; align-items: center; flex-direction: column">




                        <div style="display: flex; justify-content: space-around; align-items: center">
                            <h2>Create new Marscoin wallet or Login to existing wallet</h2>

                        </div>

                        <div class="panel-group accordion-panel" id="accordion-paneled"
                            style="display: flex; justify-content: space-evenly; align-items: center; width: 40%; margin: 40px">


                            <a data-toggle="modal" href="#styledModal" class="btn-lg btn-primary demo-element"
                                data-backdrop="static" data-keyboard="false">New Wallet</a>
                            <h4>OR</h4>
                            <a data-backdrop="static" data-keyboard="false" data-toggle="modal" href="#modalLogin"
                                class="btn-lg btn-primary demo-element">Connect Wallet</a>




                        </div> <!-- /.accordion -->




                    </div> <!-- /.portlet-body -->


                </div> <!-- /.portlet -->


            </div> <!-- /.container -->


        </div> <!-- .content -->


    </div> <!-- /#wrapper -->

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
                                                    class="form-control parsley-validated" data-required="true">
                                                <label for="name">Re-Type Password</label>
                                                <input type="password" id="re-password" name="re-password"
                                                    class="form-control parsley-validated" data-required="true">
                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <div class="next-btn">

                                    <button id="next-mnemonic" type="button" class="btn btn-primary ">Next</button>

                                </div>


                            </div> <!-- /.tab-pane -->




                            <div class="tab-pane fade" id="done">

                                <h2>Wallet Complete</h2>

                                <input class="addr" id="public_addr" name="public_addr" style="display: none" />

                                <p>Send MARS to This Address: </p>


                                <div class="pub-addr">

                                    <h3 class="addr" name="public_addr"></h3>

                                    <i class="fa fa-copy copy-icon"> </i>
                                </div>


                                <button id="make-wallet" type="submit" class="btn btn-primary">Open Wallet</button>


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
                            @if ($encrypted_seed)
                                <li>
                                    <a href="#passwordLogin" data-toggle="tab"><i class="fa fa-key"></i>
                                        &nbsp;&nbsp;Unlock with Password
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a href="#importWallet" data-toggle="tab"><i class="fa fa-upload"></i>
                                    &nbsp;&nbsp;Import wallet
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
                                            style="width: 20%; margin: 30px">Login</button>

                                    </div>
                                </div>
                            </form>

                        </div>
                        @if ($encrypted_seed)
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
                        @endif


                        <div class="tab-pane fade in" id="importWallet">
                            <h2>Upload Marswallet JSON Key</h2>
                            <div>
                                <p>upload</p>
                                <i class="fa fa-upload"> </i>
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

    <!------- Footer End ------->




    <!-- Bootstrap core JavaScript
================================================== -->
    <!-- Custom CSS FOR HD Wallet -->
    <!-- Core JS -->

    <script src="/assets/wallet/js/dist/bundle.js"></script>
    <script src="/assets/wallet/js/dist/my_bundle.js"></script>

    <script src="/assets/wallet/js/libs/jquery-1.10.2.min.js"></script>
    <script src="/assets/wallet/js/libs/bootstrap.min.js"></script>
    <script src="/assets/wallet/js/jquery.slimscroll.min.js"></script>
    <!--[if lt IE 9]>
<script src="/assets/wallet/js/libs/excanvas.compiled.js"></script>
<![endif]-->
    <!-- Plugin JS -->
    <script src="/assets/wallet/js/plugins/magnific/jquery.magnific-popup.min.js"></script>
    <script src="/assets/wallet/js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="/assets/wallet/js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script src="/assets/wallet/js/plugins/magnific/jquery.magnific-popup.js"></script>

    <!-- App JS -->
    <script src="/assets/wallet/js/mvpready-core.js"></script>
    <script src="/assets/wallet/js/mvpready-admin.js"></script>
    <script src="/assets/wallet/js/mvpready-helpers.js"></script>

    <!-- Plugin JS -->
    <script src="/assets/wallet/js/demos/table_demo.js"></script>

    <!-- SALTY SALT -->
    <script type="text/javascript">
        $(document).ready(function() {

            let iv = "{{ json_encode($iv) }}".replace("]", "").replace("[", "").split(",");
            iv = new Uint8Array(iv);

            // ===================================================================
            // =================== Handle Modal Tabs Logic =======================
            // ===================================================================

            $("#next-mnemonic").click(() => {
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
            // ===================================================================
            //
            //
            //
            //
            //
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
            // ===================================================================
            //
            //
            //
            //
            //
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


                });


                $('#re-password').on('input', function() {

                    mnem = $('.mnemonic-text').html();
                    re_password = $("#re-password").val().replace(/\s+/g, '');
                    // Supercal77
                    //2dba919542bf0e3ac825ff3470db282f

                    // hashed_re_password = my_bundle.pbkdf2.pbkdf2Sync(
                    //     re_password,
                    //     "{{ $SALT }}", 1, 16, 'sha512').toString('hex')

                    hashed_re_password = hashPassword(re_password)

                    //   console.log("HASHED RE-pass:", hashed_re_password)
                    //   console.log("enc-mnem:", encrypted_mnem)
                    //   console.log("dec-mnem:", my_bundle.decrypt(encrypted_mnem, hashed_re_password, iv))
                });



                $("#make-wallet").click(() => {
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

                for(let i = 0; i < rounds; i++)
                {
                    console.log(`hash round: ${i}`)
                    
                }


                const ret = my_bundle.pbkdf2.pbkdf2Sync(
                    passcode,
                    "{{ $SALT }}", 1, 16, 'sha512').toString('hex')

                return ret
            }



            //          hashed: 3272ef82de36a61f12c93f823781a928
            //          hd:1314 Encrypted SEED: a7MVqMKaxLlA7NXfkuFb+ENw6qp/XytJSVbgZMnZoQndzdeYmqRcjtre8LxJ8hy09WXQblUVDoZy8JUbdgEXs5gtxYjYD34=
            //          hd:1315 MNEM: Y�n�l�J�>��چ�s���<��5�YL+ǟ��߲	R���W���x����օ��+��R���7C����

            // HASHED RE-pass: 3272ef82de36a61f12c93f823781a928
            // hd:928 enc-mnem: a7MVqMKaxLlA7NXfkuFb+ENw6qp/XytJSVbgZMnZoQndzdeYmqRcjtre8LxJ8hy09WXQblUVDoZy8JUbdgEXs5gtxYjYD34=
            // hd:929 dec-mnem: ribbon eight clerk learn jeans team net trap define paddle spare castle

            //fa7b9jhGm+7HqimQP8xrliW7UJUMYp/Kymh0q0PDT4RJgMxZsZXBfq1Wob1u8odfkB5n0B/PBW/KRVGrq/1uf7fZ+XOJLtltZPll
            //fa7b9jhGm+7HqimQP8xrliW7UJUMYp/Kymh0q0PDT4RJgMxZsZXBfq1Wob1u8odfkB5n0B/PBW/KRVGrq/1uf7fZ+XOJLtltZPll
            //
            // ===================================================================
            // ===================================================================
            //
            //
            //
            //
            //
            // ===================================================================
            // ============= Generate MARS HD Wallet: input=> mnemonic ===========
            // ===================================================================


            // LTC Derivation Path
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



            // **====================================================================================================================
            //
            // ADDRESS FOR SBS3128 Testing123
            //
            // address: "MSCG9f78gHw6UYb1ukpv3KBL1TPBdcwxy3"
            // mnemonic: "element expose garden swing denial expand member cat need float daring gloom"
            // prvKey: "KxzXmhDfvKCVR7Z5bfrBfdKqzVc1KLjDwjjszTSM5q8nkzDKrn6M"
            // pubKey: "035f16de6c8381bfaf4ed37ab17aa9f0fe1ea3d3eef6c3255a81a9f9b9a746ef51"
            // xprv: "tprv8ZgxMBicQKsPd4XcZQ1afgScyJybBL6DEH5sQp1tkbbFrzG2iw3mBn1ZfxS3UmX4rgvEQP83TYqhqjw7aaaitQ7rY8no2i78ZqvGVgEC4f2"
            //
            // **======================================================================================================================



            //VERSION 2!
            // Given a mnemonic gen seed
            const genSeed = (mnemonic) => {
                // console.log("SALT: {{ $SALT }}")
                //mnemonic = "invite feature forget axis radar stone bind squirrel dog crash trap equip"

                //const mnemonic = my_bundle.bip39.generateMnemonic();
                //  console.log(mnemonic)

                //const root = new my_bundle.BIP84.fromMnemonic(mnemonic, null, false, 107);

                const seed = my_bundle.bip39.mnemonicToSeedSync(mnemonic.trim());


                // ROOT === xprv
                const root = my_bundle.bitcoin.bip32.fromSeed(seed, Marscoin.mainnet)


                //private key
                const child = root.derivePath("m/44'/2'/0'").neutered();
                //console.log("child: ", child)

                // tpub == tpub
                let tpub = child.toBase58()


                const hdNode = my_bundle.bip32.fromBase58(tpub, Marscoin.mainnet)
                const node = hdNode.derive(0)

                // Marscoin addy here
                const addy = nodeToLegacyAddress(node.derive(0))


                const publicKey = node.publicKey.toString('hex')

                //console.log("addy: ", addy)

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

            //==============================================================================
            //==============================================================================


            /** 
             * Mouse Moving Entropy Generator on browser.
             */

            let captureStart = true;
            var entropy = [];

            $(document).on("mousemove", ".mouse-box", function(e) {

                var mnemonic;
                const percent_increase = 1
                var increase = percent_increase * entropy.length

                //=============================================================================
                //=============================================================================
                // Entropy Logic

                const MAX_LEN = 24; // size of entropy's array
                const now = Date.now();
                if (now >= 1 && (now % 10) !== 0) return;

                // ===========================================================
                // NEW ENTROPY LOGIC

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
                //entropy.push(pad(ret.toString(2), 11));

                //=============================================================================
                //=============================================================================

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


                });
                // =====================================================================
                // =====================================================================



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

            // =================================================================================================
            // ========================================= WALLET LOGIN ==========================================
            // =================================================================================================

            // Check if the mnemonic is valid and gens a pubaddr.......
            const checkMnemonic = (mnemonic) => {

                const root = new my_bundle.BIP84.fromMnemonic(mnemonic, null, false, 107);

                const child = root.deriveAccount(0);

                const account = new my_bundle.BIP84.fromZPrv(child, false, MARSCOIN);

                let resp = {
                    address: account.getAddress(0, false, 49),
                }

            }


            // test:
            // mansion raven expect sustain wing stairs kite mimic alpha bleak scene adjust
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
                if (response.address == "{{ $public_addr }}") {
                    // Logging in was successful... Opening wallet...
                    $(".wallet-getter-mnem").attr("action", "/wallet/getwallet")

                    localStorage.setItem("key", decrypted)
                    //console.error("Item Succesfully locally stored")

                } else {
                    $(".wallet-getter-mnem").attr("action", "/wallet/failwallet")
                }
            })

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

        });
    </script>





    <!-- Bootstrap core JavaScript
    ============================================================================= -->
    <!-- Plugin JS -->
    <script src="/assets/wallet/js/demos/parsley.js"></script>
    <script src="/assets/wallet/js/libs/jquery.steps.js"></script>
    <script src="/assets/wallet/js/demos/wizard.js"></script>

    <!-- App JS -->
    <script src="/assets/wallet/js/mvpready-core.js"></script>
    <script src="/assets/wallet/js/mvpready-helpers.js"></script>
    <script src="/assets/wallet/js/mvpready-admin.js"></script>


    <!-- Demo JS -->
</body>

</html>
