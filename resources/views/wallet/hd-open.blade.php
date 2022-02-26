<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <title>Marscoin Wallet</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald:400,300,700">
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@700&family=Orbitron:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/wallet/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-admin.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-flat.css">
    <link rel="shortcut icon" href="/assets/favicon.ico">
    <link rel="stylesheet" href="/assets/wallet/css/hd-open/hd-open.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
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
                </div> 
                <nav class="collapse navbar-collapse" role="navigation">
                    @include('wallet.navbarleft')
                    @include('wallet.navbarright')
                </nav>
            </div> <!-- /.container -->
        </header>
        @include('wallet.mainnav', array('active'=>'wallet'))

        <div class="content">

            <div class="container">

                <div class="row">
                    

                    <div class="col-md-7 col-sm-7">
                        <div class="portlet">

                            <h3 class="portlet-title">
                                <u>Send Marscoin</u>
                            </h3>

                            <div class="portlet-body">

                                <div>
                                    <span for="name">Destination Address</span>
                                    <input type="text" name="recipient" id="recipient" class="form-control destination-address" style="width: 90%"
                                        data-required="true" placeholder="Marscoin Address">

                                    <a style="float: right; margin-top: -35px;margin-right: 67px;" href="#" onclick="scan();" class="btn btn-primary">Scan <i class="fa fa-qrcode "></i></a>

                                    <span id="address-error" style="display: none;" class="error-message">Enter a valid
                                        MARS address</span>
                                    <br />

                                    <span for="name">Amount To Send: <strong class="amount-to-send"> MARS
                                        </strong></span>
                                    <div class="row">

                                        <div class="col-md-6 col-sm-5">

                                            <div id="amount-cont" class="">
                                                <input type="number" min="1" step="any" name="amount" 
                                                    class="form-control input-placeholder " data-required="true"
                                                    placeholder="0.0 MARS">
                                                <span id="amount-error" style="display: none;"
                                                    class="error-message">Enter an amount</span>
                                                <span id="insufficient-error" style="display: none;"
                                                    class="error-message">Insufficient balance</span>

                                            </div>



                                            {{-- <i class="fa fa-exchange exchange" > </i> --}}
                                        </div>
                                        <div class="col-md-2 col-sm-5">
                                            <i class="fa fa-exchange exchange"></i>


                                        </div>
                                        <div class="col-md-4 col-sm-5">
                                            <div style="display: flex; flex-direction: row" class="converted-value">
                                                <h2 class="symbol"> $ </h2>
                                                <h2 class="conversion-rate"> 0 </h2>
                                                <h5 class="currency-abbr"> USD</h5>
                                            </div>


                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-12" style="display:flex;">

                                            <button id="send-preconfirm" type="button" class="btn btn-primary "
                                                data-toggle="modal" href=""
                                                style="margin-top: 5px; width: 50%; ">Send</button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="portlet" style="margin-top: 160px;">

                                <h3 class="portlet-title">
                                    <u>Mobile Marscoin Wallet</u>
                                </h3>

                                <div class="portlet-body" style="float:left">

                                <div >
                                                <a href="https://apps.apple.com/app/id1569062610" target="_blank" class="">
                                                    <img style="width: 200px;" src="/assets/landing/img/apple.png" alt="" loading="lazy"></a>
                               
                                
                                                <a href="https://play.google.com/store/apps/details?id=io.bytewallet.bytewallet" target="_blank" class="">
                                                    <img style="width: 200px;" src="/assets/landing/img/google.png" alt="" loading="lazy"></a>
                          
                                </div>


                                </div>
                        </div>


                    </div>

                    <div class="col-md-5 col-sm-5">


                        <div class="portlet">

                            <h3 class="portlet-title">
                                <u>Marscoin Balance</u>
                            </h3>

                            <div class="portlet-body">
                                <div
                                    style="display: flex; align-items: center;  ">

                                    <h2>
                                        {{ $balance }}  MARS
                                    </h2>


                                </div>


                            </div> <!-- /.portlet-body -->

                        </div> <!-- /.portlet -->

                        <div class="portlet">

                            <h3 class="portlet-title">
                                <u>Receive Marscoin</u>
                            </h3>

                            <div class="portlet-body" style="float:left">

                                <div class="pub-addr" style="height: 50px;">

                                    <h3 class="pub-addr-text" style="font-size: 21px;"><a
                                            href="https://explore.marscoin.org/address/{{ $public_addr }}"
                                            target="_blank">{{ $public_addr }}</a></h3>

                                    <i id="copy-addr" class="fa fa-copy copy-icon"> </i>
                                </div>

                                <div>
                                    <img id="qrious" height="200" width="200" class=" float: right;">
                                </div>



                            </div>

                        </div>

                    </div>


                </div>
                {{-- <div class="row">

                    <div class="col-md-12 col-sm-5">
                        <div class="portlet">

                            <h3 class="portlet-title">
                                <u>Transaction History</u>
                            </h3>

                            <div class="portlet-body">


                                <table class="table table-striped table-bordered " id="table-1"
                                    aria-describedby="table-1_info">
                                    <thead>
                                        <tr role="row">
                                            <th style="width: 250px;" class="sorting_asc" role="columnheader"
                                                tabindex="0" aria-controls="table-1" rowspan="1" colspan="1"
                                                aria-sort="ascending"
                                                aria-label="Browser: activate to sort column descending">Transaction
                                            </th>

                                            <th style="width: 250px;" class="text-center sorting" role="columnheader"
                                                tabindex="0" aria-controls="table-1" rowspan="1" colspan="1"
                                                aria-label="CSS grade: activate to sort column ascending">Sender</th>
                                            <th style="width: 110px;" class="sorting" role="columnheader"
                                                tabindex="0" aria-controls="table-1" rowspan="1" colspan="1"
                                                aria-label="Platform(s): activate to sort column ascending">MARS
                                            </th>
                                            <th style="width: 110px;" class="text-center sorting" role="columnheader"
                                                tabindex="0" aria-controls="table-1" rowspan="1" colspan="1"
                                                aria-label="Engine version: activate to sort column ascending">USD</th>
                                            <th style="width: 110px;" class="sorting" role="columnheader"
                                                tabindex="0" aria-controls="table-1" rowspan="1" colspan="1"
                                                aria-label="Rendering engine: activate to sort column ascending">
                                                Date</th>


                                        </tr>
                                    </thead>


                                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                                        <tr class="odd">
                                            <td valign="top" colspan="5" class="">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>


                            </div>

                        </div>


                    </div>



                </div> --}}

            </div>


            <!-------------------------------------------------------------------------->
            <!---- THE BASIC MODAL IN ACTION!!!!!!!!!!!! -->
            <div id="basicModal" class="modal fade" aria-hidden="true" style="display: none;">

                <div class="modal-dialog">

                    <div class="modal-content">

                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"
                                aria-hidden="true">×</button>
                            <div style="display: flex; align-itmes: center; justify-content: center">

                                <h3 class="modal-title">Confirm Transaction</h3>

                            </div>
                        </div> <!-- /.modal-header -->

                        <div class="modal-body ">
                            <div class="row confirm-transaction">
                                <div class="col-lg-12 from">
                                    From:
                                    <h4 id="#fullname">
                                        {{ $fullname }}
                                    </h4>
                                </div>

                            </div>
                            <div class="row confirm-transaction">
                                <div class="col-lg-12 to">
                                    To:
                                    <h4 class="destination-address-modal" id="#destination-address">

                                    </h4>
                                </div>
                            </div>
                            <div class="row confirm-transaction">
                                <div class="col-lg-12 to">
                                    Estimated Fee:
                                    <h4 class="estimated-fee">
                                        N/A
                                    </h4>
                                </div>
                            </div>
                            <div class="row confirm-transaction">
                                <div class="col-lg-12 amount-modal">
                                    Total:
                                    <div style="display: flex; flex-direction: row" class="converted-value">
                                        <h3 class="symbol">$</h3>
                                        <h3 class="conversion-rate" id="conversion-rate">0.00</h3>
                                        <h5 class="currency-abbr">USD</h5>
                                    </div>
                                </div>
                            </div>


                        </div> <!-- /.modal-body -->

                        <div class="modal-footer"
                            style="display: flex; align-itmes: center; justify-content: center;">
                            <button type="button" class="btn-lg btn-primary" id="send-mars">Send MARS</button>
                            <img src="/assets/citizen/loading.gif" alt="enter image description here"
                                style="display: none" id="loading">
                            <div class="success-message" style="display: none">
                                <i class="fa fa-check" style="color: rgb(33, 192, 33)"></i>
                                <a class='transaction-hash-link' href="" target="_blank">
                                    <h5 class="transaction-hash">
                                    </h5>
                                </a>
                            </div>
                        </div> <!-- /.modal-footer -->

                    </div> <!-- /.modal-content -->

                </div><!-- /.modal-dialog -->

            </div>
            <!---- THE BASIC MODAL IN ACTION!!!!!!!!!!!! -->
            <!-------------------------------------------------------------------------->




        </div> <!-- .content -->

    </div> <!-- /#wrapper -->



<div class="modal scan-popup" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
           <div id="app">
            <div class="sidebar">
            </div>
           <canvas hidden="" style="height: 100%; width: 100%; padding: 5px;" id="qr-canvas"></canvas>

          </div>
    </div>
  </div>
</div>
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
    <script src="/assets/wallet/js/plugins/scan/qrcode.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            // ================================================================
            // ================================================================
            // CONSTANTS....

            let cur_currency = "MARS";
            var mars_price = "{{ $mars_price }}";

            // ================================================================
            // ================================================================

            const decryptWallet = (seed) => {
                //  console.log(seed)


            }
            const encrypted_seed = "{{ $encrypted_seed }}"
            decryptWallet(encrypted_seed)


            // ================================================================
            // ================================================================
            // handle






            //=================================================================
            //=================================================================
            // Conversion rate

            $(".input-placeholder").on('input', (e) => {

                var conversion = convertRate(e)

                $('.conversion-rate').text(conversion)
            })

            function convertRate(e) {

                if (cur_currency == "USD") {
                    var conversion = Math.round((e.target.value / mars_price) * 100) / 100;
                } else if (cur_currency == "MARS") {
                    var conversion = Math.round((e.target.value * mars_price) * 100) / 100;
                }
                return conversion
            }




            //=================================================================
            //=================================================================
            // Switch Currencies MARS <--> USD
            $('.exchange').click(() => {
                toggleCurrency()


            })

            function toggleCurrency() {
                if (cur_currency == "USD") {

                    $('.currency-abbr').html(cur_currency)


                    var conversion = Math.round(($('.input-placeholder').val() * mars_price) * 100) / 100;

                    $('.conversion-rate').text(conversion)


                    cur_currency = "MARS";

                    $('.amount-to-send').html(cur_currency)
                    $('.input-placeholder').attr("placeholder", cur_currency)
                    $('.symbol').html('$')



                } else if (cur_currency == "MARS") {
                    $('.currency-abbr').html(cur_currency)

                    var conversion = Math.round(($('.input-placeholder').val() / mars_price) * 100) / 100;

                    $('.conversion-rate').text(conversion)



                    cur_currency = "USD"
                    $('.amount-to-send').html(cur_currency)
                    $('.input-placeholder').attr("placeholder", cur_currency)
                    $('.symbol').html('')


                }

            }

            //=================================================================
            //=================================================================
            // COPY ADDRESS TO CLIPBOARD

            $(".copy-icon").click(() => {
                copyClipboard()
            })

            function copyClipboard() {
                /* Get the text field */
                var copyText = $(".pub-addr-text")

                //  console.log(copyText.text())

                /* Select the text field */
                copyText.select();


                /* Copy the text inside the text field */
                navigator.clipboard.writeText(copyText.text());

                /* Alert the copied text */
                //alert("Copied the text: " + copyText.text());
                $("#copy-addr").toggleClass("fa-copy fa-check", "fa-copy");

            }

            // GEN TRANSACTION HEX....

            //=================================================================
            //=================================================================

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
                }
            };

            /*
              
                                          **====================================================================================================================
                                          
                                          ADDRESS FOR SBS3128 Testing123
                                          
                                          address: "MSCG9f78gHw6UYb1ukpv3KBL1TPBdcwxy3"
                                          menmonic: "element expose garden swing denial expand member cat need float daring gloom"
                                          prvKey: "KxzXmhDfvKCVR7Z5bfrBfdKqzVc1KLjDwjjszTSM5q8nkzDKrn6M"
                                          pubKey: "035f16de6c8381bfaf4ed37ab17aa9f0fe1ea3d3eef6c3255a81a9f9b9a746ef51"
                                          xprv: "tprv8ZgxMBicQKsPd4XcZQ1afgScyJybBL6DEH5sQp1tkbbFrzG2iw3mBn1ZfxS3UmX4rgvEQP83TYqhqjw7aaaitQ7rY8no2i78ZqvGVgEC4f2"
                                          
                                          ADDRESS FOR Sebastian@byte...
                                          
                                          address: "MP5Lrzt22axX7EgCe1W5zoDqRbFSK84HTK"
                                          menmonic: "club slot ocean gadget predict orchard burst among say zone mesh aspect"
                                          prvKey: "L1b5c3Km7wfUjeLgukBbFs9KtrTVxrrcrzMZGFi7qwYshC1PAyZh"
                                          pubKey: "02ae0ab754dbd49ebb1ed97cc48e580ee88652906d9e937eaafa399bad14f76ad6"
                                          xprv: "tprv8ZgxMBicQKsPeE79vh39p7kkF3Xr3tvwVSGx3EQtapGrpjWCbHKW4vNHmPFdRTP2jwcWcFCzCS7jpSm5vkvpnkPCuRoARowCiwxhxrLsAbx"

                                          **======================================================================================================================
                                      
                                        
                                    ATTEMPT 3
                                    sbs3128...
                                    address: "M9ySTdcVCkvfzn6o6m48Y5VGy54TgxVYu7"
                                    mnemonic: "invite feature forget axis radar stone bind squirrel dog crash trap equip"
                                    pubKey: "03506118ac7a4d71ed23e8cf7bf7b8e687f06d08820b9dee5db69491618dff6c6b"
                                    xprv: "tprv8ZgxMBicQKsPdAsTJwwcQLgJTCBx1Jk1kY6AbgRZFZYCtjFqckLV5sRwCo3kVQgFFqbk7sXW2dznRsHafaiNDNP4cLJXhk4wqYkjivPn5G2"

                                    sebs@byte
                                    address: "M8hzdznobkpd2QHoXrD1biraQijZhULawa"
                                    mnemonic: "face they lemon ignore link crop above thing buffalo tide category soup"
                                    pubKey: "02c651998179afa0becde99d609b58385cc98a9cd311ce535c446b47102c200ce2"
                                    xprv: "tprv8ZgxMBicQKsPe6qoCZKjqHNj8Abrygb4MpPkspmQ1NGW2XyLQt23QgFGumQmFUN9Z8DYKuJzrXtSyqfTvX5jFKt2gkhjAtiu48osJa7bWVy"          

                                    // userid: 5702
                                    mnem: business tattoo current news edit bronze ketchup wrist thought prize mistake supply
                                      
            */

            // Get Seed given menmonic
            function getSeed(mnemonic) {
                const seed = my_bundle.bip39.mnemonicToSeedSync(mnemonic);

                return seed

            }

            // Get Public key given mnemonic
            function getXpub(mnemonic) {
                //console.log("==== [MARS] GetXPub()");

                const seed = getSeed(mnemonic);
                const root = bitcoin.bip32.fromSeed(seed, Marscoin.mainnet);

                const path = getDerivationPath();
                const child = root.derivePath(path).neutered();
                const xpub = child.toBase58();

                return xpub;
            }

            // public address
            function getDerivationPath() {
                return "m/44'/2'/0'";
            }


            function getAddress(xpub) {
                const hdNode = HDNode.fromBase58(xpub, Marscoin.mainnet);
                const node0 = hdNode.derive(node);
                address = nodeToLegacyAddress(_node0.derive(index));

            }

            function nodeToLegacyAddress(hdNode) {
                return my_bundle.bitcoin.payments.p2pkh({
                    pubkey: hdNode.publicKey,
                    network: Marscoin.mainnet,
                }).address;
            }


            //
            // Send button is clicked....
            let tx_i_o;

            $("#send-preconfirm").click(async (e) => {
                $(".destination-address-modal").text($(".destination-address").val())
                let addy = $(".destination-address-modal").text()
                let amount = $(".input-placeholder").val()
                let m = addy.charAt(0) === "M" ? true : false
                let len = addy.length === 34 ? true : false

                // Handle Input for Tx
                if (addy && amount && m && len) {

                    if ("{{ $balance }}" > parseFloat(amount)) {
                        $("#address-error").hide()
                        $("#amount-error").hide()
                        $("#send-preconfirm").attr("href", "#basicModal")
                    } else {
                        $("#insufficient-error").show()
                    }

                } else {
                    $("#amount-error").show()
                    $("#address-error").show()
                    $("#send-preconfirm").attr("href", "")
                }

                if (!addy || !m || !len) {
                    $("#address-error").show()
                } else {
                    $("#address-error").hide()
                }

                if (!amount) {
                    $("#amount-error").show()
                } else {
                    $("#amount-error").hide()
                }




                /// Trying to breakdown the logic of gettig fee on modal click
                var mars_amount;
                var receiver_address = $(".destination-address").val();

                if (cur_currency == "MARS") {
                    mars_amount = $(".input-placeholder").val();
                } else if (cur_currency == "USD") {
                    mars_amount = $(".conversion-rate").text();
                }
                //console.log("MARS AMOUNT: ", mars_amount)

                const io = await sendMARS(mars_amount, receiver_address);

                const fee = marsConvert(io.fee);
                console.log("THE FEE: ", fee);

                const total_amount = fee + parseInt(mars_amount)
                $(".estimated-fee").text("$ " + fee)
                $(".conversion-rate").text(total_amount)

                $("#send-mars").click(async () => {

                    // var sending_mars;
                    // var receiver_address = $(".destination-address").val();

                    // if (cur_currency == "MARS") {
                    //     sending_mars = $(".input-placeholder").val();
                    // } else if (cur_currency == "USD") {
                    //     sending_mars = $(".conversion-rate").text();
                    // }

                    // console.log("SENDING MARS: ", sending_mars);
                    // console.log("Sendin TO: ", receiver_address)

                    $("#send-mars").hide()
                    $("#loading").show()

                    //await sendMARS(sending_mars, receiver_address);
                    try {
                        const tx = await signMARS(mars_amount, io)
                        $("#loading").hide()
                        $(".success-message").show()
                        $(".transaction-hash-link").attr("href",
                            "https://explore.marscoin.org/tx/" + tx.tx_hash)
                        $(".transaction-hash").text("" + tx.tx_hash)



                    } catch (e) {
                        handleError("unable to sign")
                        throw e;
                    }





                })





            })

            $('#basicModal').on('hidden.bs.modal', function() {
                location.reload();
            })
           

            const sendMARS = async (mars_amount, receiver_address) => {
                //console.log("send mars running...")
                //let mnemonic = "invite feature forget axis radar stone bind squirrel dog crash trap equip"
                //const mnemonic = localStorage.getItem("key").trim();


                // obtain utxo i/o
                const sender_address = "{{ $public_addr }}".trim()

                try {
                    const io = await getTxInputsOutputs(sender_address, receiver_address,
                        mars_amount)

                    return io
                } catch (e) {
                    handleError("get input outputs")
                    throw e;
                }

                return null
            }

            const signMARS = async (mars_amount, tx_i_o) => {
                const mnemonic = localStorage.getItem("key").trim();
                const sender_address = "{{ $public_addr }}".trim()

                //const mnemonic = "business tattoo current news edit bronze ketchup wrist thought prize mistake supply"
                //console.log("Mnemonic:", mnemonic)

                const seed = my_bundle.bip39.mnemonicToSeedSync(mnemonic);

                // console.log("seed: ", seed)

                // ROOT === xprv
                const root = my_bundle.bip32.fromSeed(seed, Marscoin.mainnet)

                //private key
                const child = root.derivePath("m/44'/2'/0'/0/0");
                //const child = root.derivePath(getDerivationPath());

                const wif = child.toWIF()

                //=======================================================================

                const zubs = zubrinConvert(mars_amount)


                var key = my_bundle.bitcoin.ECPair.fromWIF(wif, Marscoin.mainnet);
                //console.log("Key:", key)

                var psbt = new my_bundle.bitcoin.Psbt({
                    network: Marscoin.mainnet,
                });
                psbt.setVersion(1)
                psbt.setMaximumFeeRate(100000);

                tx_i_o.inputs.forEach((input, i) => {
                    psbt.addInput({
                        hash: input.txId,
                        index: input.vout,
                        nonWitnessUtxo: my_bundle.Buffer.from(input.rawTx, 'hex'),
                    })
                })

                tx_i_o.outputs.forEach(output => {
                    // watch out, outputs may have been added that you need to provide
                    // an output address/script for
                    if (!output.address) {
                        output.address = sender_address
                    }

                    psbt.addOutput({
                        address: output.address,
                        value: output.value,
                    })

                })

                //console.log("length:",tx_i_o.inputs.length )
                for (let i = 0; i < tx_i_o.inputs.length; i++) {
                    psbt.signInput(i, key);
                }

                // psbt.signInput(0, key);

                //console.log(psbt.finalizeAllInputs().extractTransaction().toHex());

                const txhash = psbt.finalizeAllInputs().extractTransaction().toHex()

                try {
                    const txId = await broadcastTxHash(txhash);
                    return txId;


                } catch (e) {
                    handleError("broadcasting")
                    throw e;
                }


            }

            const handleError = (str) => {
                console.log("ERROR:", str)
            }


            //===============================================================================
            //===============================================================================
            // API CALLS

            const getTxInputsOutputs = async (sender_address, receiver_address, amount) => {
                // Default options are marked with *
                if (!sender_address || !receiver_address || !amount) {
                    throw new Error("Missing inputs for tx hash call...");
                }
                //console.log(sender_address)
                //console.log(receiver_address)
                //console.log(amount)

                const url =
                    `https://pebas.marscoin.org/api/mars/utxo?sender_address=${sender_address}&receiver_address=${receiver_address}&amount=${amount}`

                try {
                    const response = await fetch(url, {
                        method: 'GET', // *GET, POST, PUT, DELETE, etc.
                    });

                    return response.json()

                } catch (e) {
                    throw e;
                }



            }

            const broadcastTxHash = async (txhash) => {
                if (!txhash) {
                    throw new Error("Missing tx hash...");
                }

                const url =
                    `https://pebas.marscoin.org/api/mars/broadcast?txhash=${txhash}`
                try {
                    const response = await fetch(url, {
                        method: 'GET'
                    });
                    return response.json() // parses JSON response into native JavaScript objects
                } catch (e) {
                    throw e;
                }


            }

            //===============================================================================
            //===============================================================================
            //===============================================================================



            const zubrinConvert = (MARS) => {
                return (MARS * 100000000)
            }

            const marsConvert = (zubrin) => {
                return (zubrin / 100000000)
            }


            const genSeed = (mnemonic) => {

                //const mnemonic = my_bundle.bip39.generateMnemonic();
                // console.log(mnemonic)

                //const root = new my_bundle.BIP84.fromMnemonic(mnemonic, null, false, 107);

                const seed = my_bundle.bip39.mnemonicToSeedSync(mnemonic);

                // console.log("seed: ", seed)


                // ROOT === xprv
                const root = my_bundle.bitcoin.bip32.fromSeed(seed, Marscoin.mainnet)

                // console.log("root: ", root.toBase58());

                //private key
                const child = root.derivePath("m/44'/2'/0'/0'");
                // console.log("child: ", child.toWIF())

                // tpub == tpub
                let tpub = child.toBase58()
                // console.log("tpub: ", tpub)


                const hdNode = my_bundle.bitcoin.bip32.fromBase58(tpub, Marscoin.mainnet)
                const node = hdNode.derive(0)
                // console.log("My node: ", node)

                // Marscoin addy here
                const addy = nodeToLegacyAddress(node.derive(0))

                const publicKey = node.publicKey.toString('hex')
                // console.log("Public Key: ", publicKey)

                // console.log("addy: ", addy)



                const resp = {
                    address: addy,
                    pubKey: publicKey,
                    prvKey: child.toWIF(),
                    xprv: root.toBase58(),
                    mnemonic: mnemonic
                }


                return resp;

            };
        });

var qr = new QRious({
          element: document.getElementById('qrious'),
          value: '{{ $public_addr }}',
          size: '250'
        });

function scan(){
  $('.scan-popup').modal('show');
}

const video = document.createElement("video");
const canvasElement = document.getElementById("qr-canvas");
const canvas = canvasElement.getContext("2d");
const outputData = document.getElementById("outputData");
const btnScanQR = document.getElementById("btn-scan-qr");

let scanning = false;

$('.scan-popup').on('shown.bs.modal', function (e) {
  
  console.log("Here");
  navigator.mediaDevices
    .getUserMedia({ video: { facingMode: "environment" } })
    .then(function(stream) {
      scanning = true;
      canvasElement.hidden = false;
      video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
      video.srcObject = stream;
      video.play();
      tick();
      scan2();
    });

});




qrcode.callback = res => {
  if (res) {
    res = res.replace("bitcoin:", "");
    $("#recipient").val(res);
    scanning = false;
    $('.scan-popup').modal('hide');

    video.srcObject.getTracks().forEach(track => {
      track.stop();
    });

 
    canvasElement.hidden = true;
    btnScanQR.hidden = false;
    $('.scan-popup').modal('hide');

  }
};


function tick() {
  canvasElement.height = video.videoHeight;
  canvasElement.width = video.videoWidth;
  canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);

  scanning && requestAnimationFrame(tick);
}

function scan2() {
  try {
    qrcode.decode();
  } catch (e) {
    setTimeout(scan2, 300);
  }
}

</script>
</body>

</html>
