<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->

<head>
    <title>Martian Republic - Congress</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald:400,300,700">
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@700&family=Orbitron:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/wallet/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-admin.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-flat.css">
    <link rel="stylesheet" href="/assets/wallet/css/voting/voting.css">
    <link rel="stylesheet" href="/assets/wallet/css/simplemde.min.css">
    <link rel="shortcut icon" href="/assets/favicon.ico">
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


        .price-box {
    margin: 0 auto;
	background: #E9E9E9;
	border-radius: 10px;
	padding: 40px 15px;
	width: 500px;
}

.ui-widget-content {
	border: 1px solid #bdc3c7;
	background: #e1e1e1;
	color: #222222;
	margin-top: 4px;
}

.ui-slider .ui-slider-handle {
	position: absolute;
	z-index: 2;
	width: 5.2em;
	height: 2.2em;
	cursor: default;
	margin: 0 -40px auto !important;
	text-align: center;	
	line-height: 30px;
	color: #FFFFFF;
	font-size: 15px;
}

.ui-slider .ui-slider-handle .glyphicon {
	color: #FFFFFF;
	margin: 0 3px; 
	font-size: 11px;
	opacity: 0.5;
}

.ui-corner-all {
	border-radius: 20px;
}

.ui-slider-horizontal .ui-slider-handle {
	top: -.9em;
}

.ui-state-default,
.ui-widget-content .ui-state-default {
	border: 1px solid #f9f9f9;
	background: #3498db;
}

.ui-slider-horizontal .ui-slider-handle {
	margin-left: -0.5em;
}

.ui-slider .ui-slider-handle {
	cursor: pointer;
}

.ui-slider a,
.ui-slider a:focus {
	cursor: pointer;
	outline: none;
}

.price, .lead p {
	font-weight: 600;
	font-size: 32px;
	display: inline-block;
	line-height: 60px;
}

h4.great {
	background: #00ac98;
	margin: 0 0 25px -60px;
	padding: 7px 15px;
	color: #ffffff;
	font-size: 18px;
	font-weight: 600;
	border-radius: 5px;
	display: inline-block;
	-moz-box-shadow:    2px 4px 5px 0 #ccc;
  	-webkit-box-shadow: 2px 4px 5px 0 #ccc;
  	box-shadow:         2px 4px 5px 0 #ccc;
}

.total {
	border-bottom: 1px solid #7f8c8d;
	/*display: inline;
	padding: 10px 5px;*/
	position: relative;
	padding-bottom: 20px;
}

.total:before {
	content: "";
	display: inline;
	position: absolute;
	left: 0;
	bottom: 5px;
	width: 100%;
	height: 3px;
	background: #7f8c8d;
	opacity: 0.5;
}

.price-slider {
	margin-bottom: 70px;
}

.price-slider span {
	font-weight: 200;
	display: inline-block;
	color: #7f8c8d;
	font-size: 13px;
}

.form-pricing {
	background: #ffffff;
	padding: 20px;
	border-radius: 4px;
}

.price-form {
	background: #ffffff;
	margin-bottom: 10px;
	padding: 20px;
	border: 1px solid #eeeeee;
	border-radius: 4px;
	/*-moz-box-shadow:    0 5px 5px 0 #ccc;
  	-webkit-box-shadow: 0 5px 5px 0 #ccc;
  	box-shadow:         0 5px 5px 0 #ccc;*/
}

.form-group {
	margin-bottom: 0;
}

.form-group span.price {
	font-weight: 200;
	display: inline-block;
	color: #7f8c8d;
	font-size: 14px;
}

.help-text {
	display: block;
	margin-top: 32px;
	margin-bottom: 10px;
	color: #737373;
	position: absolute;
	/*margin-left: 20px;*/
	font-weight: 200;
	text-align: right;
	width: 188px;
}

.price-form label {
	font-weight: 200;
	font-size: 21px;
}

img.payment {
	display: block;
    margin-left: auto;
    margin-right: auto
}

.ui-slider-range-min {
	background: #2980b9;
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
    <script src="/assets/wallet/js/jquery-ui.min.js"></script>
    <script src="/assets/wallet/js/simplemde.min.js"></script>
   
    <script>
        var simplemde = new SimpleMDE({ element: document.getElementById("description") });
    </script>
    <script>
    $(document).ready(function() {
          $("#slider").slider({
              animate: true,
              value:1,
              min: 0,
              max: 100,
              step: 1,
              slide: function(event, ui) {
                  update(1,ui.value); //changed
              }
          });

          $("#slider2").slider({
              animate: true,
              value:0,
              min: 0,
              max: 680,
              step: 1,
              slide: function(event, ui) {
                  update(2,ui.value); //changed
              }
          });
          $("#slider3").slider({
              animate: true,
              value:0,
              min: 51,
              max: 100,
              step: 1,
              slide: function(event, ui) {
                  update(2,ui.value); //changed
              }
          });
          $("#slider4").slider({
              animate: true,
              value:0,
              min: 0,
              max: 2672,
              step: 1,
              slide: function(event, ui) {
                  update(2,ui.value); //changed
              }
          });

          //Added, set initial value.
          $("#amount").val(0);
          $("#duration").val(0);
          $("#amount-label").text(0);
          $("#duration-label").text(0);
          
          update();
      });

      //changed. now with parameter
      function update(slider,val) {
        //changed. Now, directly take value from ui.value. if not set (initial, will use current value.)
        var $amount = slider == 1?val:$("#amount").val();
        var $duration = slider == 2?val:$("#duration").val();

        /* commented
        $amount = $( "#slider" ).slider( "value" );
        $duration = $( "#slider2" ).slider( "value" );
         */

         $total = "$" + ($amount * $duration);
         $( "#amount" ).val($amount);
         $( "#amount-label" ).text($amount);
         $( "#duration" ).val($duration);
         $( "#duration-label" ).text($duration);
         $( "#total" ).val($total);
         $( "#total-label" ).text($total);

         $('#slider a').html('<label><span class="glyphicon glyphicon-chevron-left"></span> '+$amount+' <span class="glyphicon glyphicon-chevron-right"></span></label>');
         $('#slider2 a').html('<label><span class="glyphicon glyphicon-chevron-left"></span> '+$duration+' <span class="glyphicon glyphicon-chevron-right"></span></label>');
      }
</script>

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
