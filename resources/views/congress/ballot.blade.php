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
	margin: 0 0 25px 0px;
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

.editor-toolbar.fullscreen {
    z-index: 2470 !important;
}

.CodeMirror,
.CodeMirror-scroll {
    min-height: 968px;
}

/* #messages {
  -webkit-mask-image: linear-gradient(to bottom, black 50%, transparent 100%);
  mask-image: linear-gradient(to bottom, black 50%, transparent 100%);
  overflow-y: scroll;
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
        @include('wallet.mainnav', array('active'=>'congress'))

        
        <div class="content">
            <div class="container">
                <?php if($wallet_open){ ?>
                <div class="portlet">
                    <div class="portlet-body">

                    <div class="alert alert-danger">
                        <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
                        <strong>Attention!</strong> <h4 class="noticebar-empty-title">DO NOT CLOSE THIS TAB AND BROWSER UNTIL YOUR BALLOT HAS BEEN RECEIVED.</h4>
                    </div>
                        
                    <h3 class="content-title"><u>Ballot acquisition for Proposal #{{ strtoupper(substr(str_replace("https://ipfs.marscoin.org/ipfs/", "", $proposal->ipfs_hash), 1, 8)) }}</u></h3>


                        <p>Citizen name, public address, your ballot request for an auditable yet private ballot has been received by the ballot coordination server.</p>
                        <p>You can monitor the ballot acquisition process on this page</p>
                    
                        <div id="messages" style="padding: 25px; border: 1px dotted; ">

                        </div>

                        <a target="_blank" href="https://marscoin.gitbook.io/marscoin-documentation/martian-republic/congress/ballot" style="margin-top: 35px;" class="btn btn-info btn-lg">Learn More</a>

                    </div> 
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
    <script src="/assets/wallet/js/jquery-ui.min.js"></script>
    <script src="/assets/wallet/js/simplemde.min.js"></script>
    <script src="/assets/wallet/js/md5.min.js"></script>
    <script src="/assets/wallet/js/sha256.js"></script>
    <script src="/assets/wallet/js/jsencrypt.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js" integrity="sha512-E8QSvWZ0eCLGk4km3hxSsNmGWbLtSCSUcewDQPQWZF6pEU8GlT8a5fF32wOl1i8ftdMhssTrF/OhyGWwonTcXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
$(document).ready(function() {

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
    var crypt = new JSEncrypt({default_key_size: 1024});
    crypt.getKey();
    var amount = 1
    var addr = '{{$public_address}}'
    var source = "ip"
    var hidden_target = "generated receiving address"
    var privkey  = crypt.getPrivateKeyB64()
    console.log(privkey)
    var ek = crypt.getPublicKeyB64() //ephemeral public key
    console.log(ek)
    var index = null
    var peers = null
    var order = null
    var is_last_shuffler = null
    var server_addr = server_addr
    var start_called = false
    //var messageKey = ""

    //test()

    function parseHexString(str) { 
        var result = [];
        while (str.length >= 2) { 
            result.push(parseInt(str.substring(0, 2), 16));
            str = str.substring(2, str.length);
        }

        return result;
    }

    function createHexString(arr) {
        var result = "";
        for (i in arr) {
            var str = arr[i].toString(16);
            str = str.length == 0 ? "00" :
                str.length == 1 ? "0" + str : 
                str.length == 2 ? str :
                str.substring(str.length-2, str.length);
            result += str;
        }
        return result;
    }

    function nodeToLegacyAddress(hdNode) {
        return my_bundle.bitcoin.payments.p2pkh({
            pubkey: hdNode.publicKey,
            network: Marscoin.mainnet,
        }).address;
    }

    function genSeed(mnemonic){
        const seed = my_bundle.bip39.mnemonicToSeedSync(mnemonic.trim());
        const root = my_bundle.bitcoin.bip32.fromSeed(seed, Marscoin.mainnet)
        const child = root.derivePath("m/999999'/107'/<?=$propid?>'").neutered();
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
    }

    function getProposalOutputAddress(){
        rb = '<?=$random_bytes?>';
        rb = parseHexString(createHexString(rb))
        mnemonic = my_bundle.bip39.entropyToMnemonic(rb)
        $("#messages").prepend('<br>Ballot Seed: ' + mnemonic);
        const wallet = genSeed(mnemonic)
        return wallet.address;
    }

    function find_index(order){
        index = -1;
        Object.keys(order).forEach(function(k){
            if(order[k].replace(/\s/g,'') == ek.replace(/\s/g,'')){
                index = k;
            }
        });
        return index;
    }

    //The de-facto unbiased shuffle algorithm is the Fisher-Yates (aka Knuth) Shuffle.
    function shuffle(array) {
        let currentIndex = array.length,  randomIndex;

        // While there remain elements to shuffle.
        while (currentIndex != 0) {

            // Pick a remaining element.
            randomIndex = Math.floor(Math.random() * currentIndex);
            currentIndex--;

            // And swap it with the current element.
            [array[currentIndex], array[randomIndex]] = [
            array[randomIndex], array[currentIndex]];
        }

        return array;
    }


    function shuffle_data(data){
        old_order = Object.keys(data)
        new_order = shuffle(old_order);
        new_data = {}
        for([i, v] of Object.entries(data)) {
            new_data[new_order[parseInt(i)]] = v
        }
        return new_data 
    }

    function decrypt_data(data){
        new_data = {}
        for([i, v] of Object.entries(data)) {
            console.log(i, v);
            vp = v;
            vp['target'] = decrypt(privkey, v["target"]);
            new_data[i] = vp; 
        }
        return new_data;
    }

    function encrypt(epkey, message){
        //Applies public key (hybrid) encryption to a given message when supplied
        //with a path to a public key (RSA in PEM format).
        //Load the recipients pubkey
        var crypt = new JSEncrypt();
        crypt.setKey(epkey);

        //Encrypt the message with AES-GCM using a newly selected key
        var password = "test";
        var iterations = 500;
        var keySize = 256;
        var salt = CryptoJS.lib.WordArray.random(128/8);
        //console.log(salt.toString(CryptoJS.enc.Base64));
        var output = CryptoJS.PBKDF2(password, salt, {
            keySize: keySize/32,
            iterations: iterations
        });
        messageKey = output.toString(CryptoJS.enc.Base64)
        console.log("Key: " + messageKey);
        var ctext = CryptoJS.AES.encrypt(message, messageKey);
        console.log("Encrypted Text: " + ctext.toString())
        
        //Encrypt the message key and prepend it to the ciphertext
        encMsg = ctext.toString();
        ckey = crypt.encrypt(messageKey);
        console.log("Encrypted Key: " + ckey)
        encMsgTotal = ckey + encMsg
        console.log("Encrypted cKey + encMsg: " + encMsgTotal)
        return encMsgTotal;
    }

    function decrypt(keypair, ctext){
        //Load the recipients privatekey
        var crypt = new JSEncrypt();
        crypt.setKey(keypair);
        encM = ctext
        encKey = encM.substring(0, 172)
        console.log("Or key enc: " + encKey)
        ctext = encM.substring(172, encM.length)
        console.log("Or ctext: " + ctext)
        messageKey = crypt.decrypt(encKey)
        console.log("Or unenc key: " + messageKey)
        message = CryptoJS.AES.decrypt(ctext, messageKey);
        console.log(message.toString(CryptoJS.enc.Utf8))
        return message.toString(CryptoJS.enc.Utf8)
    }

    function test(){
        console.log("++++++++++++++++TEST++++++++++++++++++++++++++++")
        var message = "The Quick Brown Fox Jumps Over The Lazy Dog";
        console.log(message)
        e = encrypt(ek, message)
        d = decrypt(privkey, e)
        console.log(d)
        console.log("++++++++++++++++ENDTEST++++++++++++++++++++++++++++")
    }


    function encrypt_dest(){
        var t = hidden_target
        for (let i = num_peers-1;i > index; --i){
            console.log(t)
            t = encrypt(order[i], t)
            console.log("Encrypted: " + t)
        }
        return t
    }

    function construct_transactions(data, sources){
        return "";
    }

    $("#messages").html("Connecting to ballot server...")
    var socket;
    var domain = document.domain.split('.')[1]
    if(domain == "local")
        socket = new WebSocket("wss://127.0.0.1:3678");
    else
        socket = new WebSocket("wss://martianrepublic.org:3678");

    socket.onopen = function(e) {
        $("#messages").prepend("'<br>[open] Connection established");
        $("#messages").prepend("'<br>Sending to server");
        socket.send("{{$public_address}}_{{ strtoupper(substr(str_replace('https://ipfs.marscoin.org/ipfs/', '', $proposal->ipfs_hash), 1, 8)) }}");
    };

    socket.onmessage = function(event) {
        $("#messages").prepend(`<br>[BALLOT SERVER]: ${event.data}`);
        if(event.data == "JOINED_ACK")
        {
            //Generate a new receive address for the ballot
            $("#messages").prepend('<br>Generating ballot for proposal');

            //generate ephemeral public key and shuffling keypair
            socket.send("SUBMIT_KEY#"+ek+"#")

            hidden_target = getProposalOutputAddress();
            $("#messages").prepend('<br>Generated: ' + hidden_target);
            $("#messages").prepend('<br>Ballot shuffle in progress... ');

        }
        if(event.data.includes("INITIATE_SHUFFLE_"))
        {
            json = event.data.split("_")[2]
            data = JSON.parse(json);
            start_called = true
            peers = JSON.parse(data.peers)
            num_peers = Object.keys(peers).length;
            order = JSON.parse(data.order)
            ord_length = Object.keys(order).length;
            index = parseInt(find_index(order))
            is_last_shuffler = (index + 1 == ord_length)
            encrypted_target = encrypt_dest()
            if (index != 0)
                socket.send("SHUFFLE_INIT_COMPLETE_"+JSON.stringify(encrypted_target)); //<- next one in order until last one is us, then this:
        }
        if(event.data.includes("PERFORM_SHUFFLE"))
        {
            json = event.data.split("_")[2]
            data = JSON.parse(json);
            json = event.data.split("_")[3]
            sources = JSON.parse(json);
            if(index != data.length)
                console.log("Wrong order...")
            data = decrypt_data(data)
            data[index] = {
                "public_key": ek,
                "target": encrypted_target
            }
            sources[index] = {"source": "tx_input"}
            data =  shuffle_data(data)
            if (index == num_peers - 1)
                construct_transactions(sources, data)
            else
                socket.send("PERFORM_SHUFFLE_ACK_{'data': "+JSON.stringify(data) + "," + "'sources: ' "+JSON.stringify(sources)+"}")
        }
    };

    socket.onclose = function(event) {
    if (event.wasClean) {
        alert(`[close] Connection closed cleanly, code=${event.code} reason=${event.reason}`);
    } else {
        // e.g. server process killed or network down
        // event.code is usually 1006 in this case
        $("#messages").prepend('<br>[close] Connection died');
    }
    };

    socket.onerror = function(error) {
        $("#messages").prepend(`<br>[error] ${error.message}`);
    };

})
</script>
<script type="text/javascript">
    $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });  

const signMARS = async (message, mars_amount, tx_i_o) => {
    const mnemonic = localStorage.getItem("key").trim();
    const sender_address = "<?=$public_address?>".trim()

    const seed = my_bundle.bip39.mnemonicToSeedSync(mnemonic);

    const root = my_bundle.bip32.fromSeed(seed, Marscoin.mainnet)

    const child = root.derivePath("m/99999'/107'/0'/0/0");

    const wif = child.toWIF()

    const zubs = zubrinConvert(mars_amount)

    var key = my_bundle.bitcoin.ECPair.fromWIF(wif, Marscoin.mainnet);
    
    var psbt = new my_bundle.bitcoin.Psbt({
        network: Marscoin.mainnet,
    });
    psbt.setVersion(1)
    psbt.setMaximumFeeRate(10000000);

    unspent_vout = 0
    var data = my_bundle.Buffer(message)
    const embed = my_bundle.bitcoin.payments.embed({ data: [data] });
    
    psbt.addOutput({
    script: embed.output,
    value: 0,
    })
    
    tx_i_o.inputs.forEach((input, i) => {
        psbt.addInput({
            hash: input.txId,
            index: input.vout,
            nonWitnessUtxo: my_bundle.Buffer.from(input.rawTx, 'hex'),
        })
    })

    tx_i_o.outputs.forEach(output => {
        if (!output.address) {
            output.address = sender_address
        }

        psbt.addOutput({
            address: output.address,
            value: output.value,
        })
    })

    for (let i = 0; i < tx_i_o.inputs.length; i++) {
        try{
            psbt.signInput(i, key);
        } catch (e) {
            alert("Problem while trying to sign with your key. Please try to reconnect your wallet...");
        }
    }

    const tx = psbt.finalizeAllInputs().extractTransaction(); 
    const txhash = tx.toHex()
    console.log(txhash)

    try {
        const txId = await broadcastTxHash(txhash);
        return txId;

    } catch (e) {
        handleError()
        throw e;
    }

}


        });
</script>

</body>

</html>
