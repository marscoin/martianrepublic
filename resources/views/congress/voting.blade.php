<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->

<head>
    <title>Mars Basecamp - Congress</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Google Font: Open Sans -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald:400,300,700">
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@700&family=Orbitron:wght@500&display=swap" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="/assets/wallet/css/font-awesome.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/assets/wallet/css/bootstrap.min.css">
    <!-- App CSS -->
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-admin.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-flat.css">

    <link rel="stylesheet" href="/assets/wallet/css/voting/voting.css">

    <!-- <link href="/assets/wallet/css/custom.css" rel="stylesheet">-->
    <!-- Favicon -->
    <link rel="shortcut icon" href="/assets/favicon.ico">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
  <![endif]-->
    <style>
        span.qrcodeicon span {
            position: absolute;
            display: block;
            top: 7px;
            right: 21px;
            width: 18px;
            height: 18px;
            background: url('/assets/wallet/img/qrcode.png');
            cursor: pointer;
            z-index: 1;
        }

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
        @include('wallet.mainnav', array('active'=>'congress'))

        
        <div class="content">

            <div class="container">


                <?php if($wallet_open){ ?>
                <div class="portlet">
                    <div class="portlet-body">

                        <ul id="myTab1" class="nav nav-tabs">
                            <li class="active">
                                <a href="#Active" data-toggle="tab">Active</a>
                            </li>

                            <li class="">
                                <a href="#All" data-toggle="tab">All</a>
                            </li>

                            <li class="">
                                <a href="#New-Proposal" data-toggle="tab">New Proposal</a>
                            </li>
                        </ul>
                        <a class="archival pull-right" style="margin-top: -80px" href="https://ipfs.io/ipfs/{{{$ipfs_root_hash}}}" target="_blank"><i class="fa fa-hdd-o"></i>Archives</a>
                        <div id="myTab1Content" class="tab-content" style="margin-top: 50px;">

                            <div class="tab-pane fade active in" id="Active">
                                @include('congress.activeproposal') 
                            </div> 
                            <div class="tab-pane fade" id="All">
                                @include('congress.allproposals')
                            </div> 
                            <?php if($isCitizen){?>
                                <div class="tab-pane fade" id="New-Proposal">
                                    @include('congress.newproposal')
                                </div> 
                                <?php }else{ ?>
                                    <div class="tab-pane fade" id="New-Proposal">
                                    @include('congress.noteligableyet')
                                    </div> 
                                <?php } ?>
                            

                        </div>

                    </div> <!-- /.portlet-body -->

                </div>
                <?php }else{ ?>
                <div class="portlet">
                    <div class="portlet-body">
                        <h3>
                            Please open / connect your wallet in order to access the Martian Citizen Congress.
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
    <script src="/assets/wallet/js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="/assets/wallet/js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script src="/assets/wallet/js/mvpready-core.js"></script>
    <script src="/assets/wallet/js/mvpready-admin.js"></script>
    <script src="/assets/wallet/js/demos/table_demo.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});  

            $("#new_address").click(function(event) {
                event.preventDefault();
                $.post("/api/newAddress/{{ Auth::user()->email }}", function(data) {
                    location.reload();
                });
            });

            $(".addrnotes").blur(function() {
                var text = $(this).val();
                var index = $(this).attr("rel");
                // $.post("/api/newnote/{{ Auth::user()->email }}", { address: index , note: text },function(data){
                //      $("#"+index+"_span").text("Saved...")
                //   });

            });

            // time to start generating 3 different wallets for when a new proposal is submitted...
            //Process:
            // 1) On ready gen 3 new wallets... obj: {yes: "addy", no: "addy", null: "addy"} of 3 res
            // 3) pass the data thru php controller and push to db on "proposal" table under yes, no, null columns...
            // 4) post to ipfs in parallel...
            // 5) cry of happiness...
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

            function nodeToLegacyAddress(hdNode) {
                return my_bundle.bitcoin.payments.p2pkh({
                    pubkey: hdNode.publicKey,
                    network: Marscoin.mainnet,
                }).address;
            }

            const genSeed = (mnemonic) => {

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
                }

                return resp;

            };

            let votes = {
                yes_vote: "",
                no_vote: "",
                null_vote: ""
            }

            Object.keys(votes).forEach((key)=>
            {
                let random_mnem = my_bundle.bip39.generateMnemonic()
                votes[key] = genSeed(random_mnem).address

                $(`#${key}`).val(votes[key])
                // $(`#${key}`).text(votes[key])

            })
    
            console.log("VALUE: " + $("#yes_vote").val())
            


        });
    </script>
</body>

</html>
