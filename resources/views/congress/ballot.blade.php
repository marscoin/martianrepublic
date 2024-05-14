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
    <link rel="stylesheet" href="/assets/wallet/css/ballot.css">
    <link rel="shortcut icon" href="/assets/favicon.ico">
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
                </nav>
            </div>
        </header>
        
        <div class="content">
            <div class="container">
                <?php if($wallet_open){ ?>
                <div class="portlet">
                    <div class="portlet-body">

                    <div id="pre-ballot" style="display: show; margin-top: 50px;">

                        <div class="alert alert-danger">
                            <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
                            <strong>Attention!</strong> <h4 class="noticebar-empty-title">DO NOT CLOSE THIS TAB AND BROWSER UNTIL YOUR BALLOT HAS BEEN RECEIVED.</h4>
                        </div>

                        <h3 class="content-title"><u>You are voting on Proposal #{{ strtoupper(substr(str_replace("https://ipfs.marscoin.org/ipfs/", "", $proposal->ipfs_hash), 1, 8)) }}</u></h3>
                            <div class="well" style="padding: 40px;">
                                <ul class="icons-list">
                                <li>
                                    <i class="icon-li fa fa-quote-left"></i>
                                    <p style="font-size: 2rem">
                                        {{ $proposal->title }}
                                    </p>
                                    <a target="_blank" href='/forum/t/{{ $slug }}' class="pull-right discussion-link">Citizen's discussion <i class="fa fa-external-link"></i></a>
                                </li>
                                </ul>
                            </div>

                       
                            
                        <h3 class="content-title"><u>Ballot acquisition for Proposal #{{ strtoupper(substr(str_replace("https://ipfs.marscoin.org/ipfs/", "", $proposal->ipfs_hash), 1, 8)) }}</u></h3>

                            <p>You can monitor the ballot acquisition process on this page</p>
                            <p>As soon as enough voters are present the ballot issuing process will begin. Once you receive your ballot, you will be able to cast your vote from this page. Do NOT close this page until you received your secret ballot and cast your vote!</p>
                        
                            <div id="messages" style="padding: 25px; border: 1px dotted; ">

                            </div>

                            <a target="_blank" href="https://marscoin.gitbook.io/marscoin-documentation/martian-republic/congress/ballot" style="margin-top: 35px;" class="btn btn-info btn-lg">Learn More</a>

                        </div> 
                    </div>
                    <div id="conf-ballot" style="display: none; margin-top: 50px;">

                             <div class="alert alert-warning">
                                <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
                                <strong>Attention!</strong> <h4 class="noticebar-empty-title">YOUR PRIVATE BALLOT IS BEING REGISTERED ON THE BLOCKCHAIN. PLEASE WAIT A MOMENT...</h4>
                                <p>Voting will start shortly...</p>
                            </div>
                            <img src="/assets/wallet/img/blockchain.gif" style="width: 223px;float: right;margin-top: -19px;">

                            <h3 class="content-title"><u>You are voting on Proposal #{{ strtoupper(substr(str_replace("https://ipfs.marscoin.org/ipfs/", "", $proposal->ipfs_hash), 1, 8)) }}</u></h3>
                            <div class="well" style="padding: 40px;">
                                <ul class="icons-list">
                                <li>
                                    <i class="icon-li fa fa-quote-left"></i>
                                    <p style="font-size: 2rem">
                                        {{ $proposal->title }}
                                    </p>
                                    <a href='/forum/t/{{ $proposal->discussion }}' class="pull-right discussion-link">Citizen's discussion <i class="fa fa-external-link"></i></a>
                                </li>
                                </ul>
                            </div>

                    </div>
                    <div id="post-ballot" style="display: none; margin-top: 50px;">
                        
                            <div class="alert alert-success">
                                <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
                                <strong>Attention!</strong> <h4 class="noticebar-empty-title">YOUR PRIVATE BALLOT HAS BEEN ISSUED. PLEASE CAST YOUR VOTE NOW:</h4>
                            </div>

                            <h3 class="content-title"><u>You are voting on Proposal #{{ strtoupper(substr(str_replace("https://ipfs.marscoin.org/ipfs/", "", $proposal->ipfs_hash), 1, 8)) }}</u></h3>
                            <div class="well" style="padding: 40px;">
                                <ul class="icons-list">
                                <li>
                                    <i class="icon-li fa fa-quote-left"></i>
                                    <p style="font-size: 2rem">
                                        {{ $proposal->title }}
                                    </p>
                                    <a href='/forum/t/{{ $proposal->discussion }}' class="pull-right discussion-link">Citizen's discussion <i class="fa fa-external-link"></i></a>
                                </li>
                                </ul>
                            </div>

                            <a id="pry" target="_blank" href="#" style="margin-top: 35px; font-size: 72px; margin-right: 50px;" class="btn btn-success btn-lg" >YES</a>
                            <a id="prn" target="_blank" href="#" style="margin-top: 35px; font-size: 72px; margin-right: 50px;" class="btn btn-danger btn-lg" >NO</a>
                            <a id="pra" target="_blank" href="#" style="margin-top: 35px; font-size: 72px; margin-right: 50px;" class="btn btn-warning btn-lg" >ABSTAIN</a>
                        
                            <h4 class="content-title" style="margin-top: 100px;"><u></u></h4>
                            <p>Your vote will be notarized on the blockchain. Due to the nature of your secret ballot, your vote WILL NOT be traceable back to you. At the same time, as your ballot originated indirectly from a citizen address it will be fully auditable and
                                provide evidence that all votes cast originate only from citizens found in the voter registry. One voter, one vote. End-to-end auditable. Immutable. Transparent. A Congress by the people for the people.</p>

                    </div>
                    <div id="post-cast" style="display: none; margin-top: 50px;">

                            <div class="alert alert-success">
                                <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
                                <strong>Attention!</strong> <h4 class="noticebar-empty-title">YOUR PRIVATE BALLOT WAS CAST: <a id="cast_confirmation" href="" ></a></h4>
                            </div>

                            <h3 class="content-title"><u>You voted successfully on Proposal #{{ strtoupper(substr(str_replace("https://ipfs.marscoin.org/ipfs/", "", $proposal->ipfs_hash), 1, 8)) }}</u></h3>
                            <div class="well" style="padding: 40px;">
                                <ul class="icons-list">
                                <li>
                                    <i class="icon-li fa fa-quote-left"></i>
                                    <p style="font-size: 2rem">
                                        {{ $proposal->title }}
                                    </p>
                                    <a href='/forum/t/{{ $proposal->discussion }}' class="pull-right discussion-link">Citizen's discussion <i class="fa fa-external-link"></i></a>
                                </li>
                                </ul>
                            </div>

                    </div>
                </div>
                <?php }else{ ?>
                <div class="portlet">
                    <div class="portlet-body">
                        <h3>
                            Please <a href="/wallet/dashboard/hd">unlock</a> your civic wallet in order to access the Citizen platform.
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
    var amount = 0.1
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
    var bpk = "";
    var bpkk = "";
    var local_key = "";
    var inputBlock = {}
    var ptimer = null
    //var messageKey = ""

    //test()
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
    });  

    const getLocalKey = async  => {
        const mnemonic = localStorage.getItem("key").trim();
        const sender_address = "<?=$public_address?>".trim()
        const seed = my_bundle.bip39.mnemonicToSeedSync(mnemonic);
        const root = my_bundle.bip32.fromSeed(seed, Marscoin.mainnet)
        const child = root.derivePath("m/44'/2'/0'/0/0");
        console.log(child)
        const wif = child.toWIF()
        local_key = my_bundle.bitcoin.ECPair.fromWIF(wif, Marscoin.mainnet);
        yk = local_key.privateKey.toString('hex')
        yp = local_key.publicKey.toString('hex')
        yb = my_bundle.Buffer.from(yp, 'hex')
        console.log("Originating Private Key:")
        console.log(yk)
        console.log("Originating Public Key:")
        console.log(yp)
        console.log("Originating Public Key Buffer:")
        console.log(yb)
        console.log("Originating EC Pair:")
        console.log(local_key)
    }

    const init = async () => {
        hidden_target = getProposalOutputAddress();
        $("#messages").append('<br>Generated: ' + hidden_target);
        $("#messages").append('<br>Ballot shuffle in progress... ');
        inputBlock = await getInputBlock()
        $("#messages").append('<br>Generated: inputs for hidden target');
        $("#messages").append('<br>Ballot shuffle in progress... ');
        await getLocalKey()
    }

    const getInputBlock = async () => {
        sources = []
        sender_address = addr;
        receiver_address = hidden_target;
        const io = await getTxInputsOutputs(sender_address, receiver_address, 0.1)
        console.log(io)
        io.inputs.forEach((input, i) => {
            var obj = {'txId': input.txId, 'vout': input.vout, 'rawTx':  my_bundle.Buffer.from(input.rawTx, 'hex'), 'value': input.value, 'originator': "{{$public_address}}"};
            sources.push(obj); 
        })
        sources_string = JSON.stringify(sources);
        console.log("Originating Address Inputs:")
        console.log(sources_string)
        return sources_string;
    }

    const getTxInputsOutputs = async (sender_address, receiver_address, amount) => {
    // Default options are marked with *
        if (!sender_address || !receiver_address || !amount) {
            throw new Error("Missing inputs for tx hash call...");
        }
        console.log(sender_address)
        console.log(receiver_address)
        console.log(amount)
        const url = `https://pebas.marscoin.org/api/mars/utxo?sender_address=${sender_address}&receiver_address=${receiver_address}&amount=${amount}`
        try {
            const response = await fetch(url, {
                method: 'GET', 
            });
            return response.json()
        } catch (e) {
            throw e;
        }
    }

    init();

    function pollConfirmation(txId) {
        $.get("https://pebas.marscoin.org/api/mars/txdetails?txid="+txId, {}, function(data) {
				if(data && data.confirmations && data.confirmations > 0){
                    $("#pre-ballot").hide();
                    $("#conf-ballot").hide();
                    $("#post-ballot").show();
                    clearTimeout(ptimer);
                }
		});
        ptimer = setTimeout(pollConfirmation, 30000, txId);
    }

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
        // seed = my_bundle.bip39.mnemonicToSeedSync(mnemonic.trim());
        // root = my_bundle.bitcoin.bip32.fromSeed(seed, Marscoin.mainnet)
        // child = root.derivePath("m/999999'/107'/<?=$propid?>'");
        // tpub = child.toBase58()
        // node = my_bundle.bip32.fromBase58(tpub, Marscoin.mainnet)
        // //node = hdNode.derive(0)
        // addy = nodeToLegacyAddress(node.derive(0))
        // publicKey = node.publicKey.toString('hex')
        // bpk = node.privateKey.toString('hex')
        // console.log("PrivateKey For BallotAddress")
        // console.log(bpk)
        // wif2 = child.toWIF()
        // bpkk = my_bundle.bitcoin.ECPair.fromWIF(wif2, Marscoin.mainnet);
        // //bpkk2 = my_bundle.bitcoin.ECPair.fromPrivateKey(my_bundle.Buffer.from(bpk, 'hex'), Marscoin.mainnet);
        // console.log(bpkk)
        // //console.log(bpkk2)
        // const resp = {
        //     address: addy,
        //     pubKey: publicKey,
        //     xprv: root.toBase58(),
        //     mnemonic: mnemonic
        // }
        // return resp;
        seed = my_bundle.bip39.mnemonicToSeedSync(mnemonic);
        root = my_bundle.bip32.fromSeed(seed, Marscoin.mainnet)
        child = root.derivePath("m/999999'/107'/<?=$propid?>'");
        wif2 = child.toWIF()
        bpkk = my_bundle.bitcoin.ECPair.fromWIF(wif2, Marscoin.mainnet);
        yk = bpkk.privateKey.toString('hex')
        yp = bpkk.publicKey.toString('hex')
        yb = my_bundle.Buffer.from(yp, 'hex')
        addy = nodeToLegacyAddress(bpkk)
        console.log("Ballot Private Key:")
        console.log(yk)
        console.log("Ballot Public Key:")
        console.log(yp)
        console.log("Ballot Public Key Buffer:")
        console.log(yb)
        console.log("Ballot EC Pair:")
        console.log(bpkk)
        const resp = {
            address: addy,
            pubKey: bpkk.publicKey.toString('hex'),
            xprv: root.toBase58(),
            mnemonic: mnemonic
        }
        return resp;
    }

    function getProposalOutputAddress(){
        rb = '<?=$random_bytes?>';
        rb = parseHexString(createHexString(rb))
        mnemonic = my_bundle.bip39.entropyToMnemonic(rb)
        $("#messages").append('<br>Ballot Seed: ' + mnemonic);
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

    function construct_transaction(data, sources){
        raw_tx = "";
        return raw_tx;
    }

    $("#messages").html("Connecting to ballot server...")
    var socket;
    var domain = document.domain.split('.')[1]
    if(domain == "local")
        socket = new WebSocket("wss://127.0.0.1:3678");
    else
        socket = new WebSocket("wss://martianrepublic.org:3678");

    socket.onopen = function(e) {
        $("#messages").append("'<br>[open] Connection established");
        $("#messages").append("'<br>Sending to server");
        socket.send("{{$public_address}}_{{ strtoupper(substr(str_replace('https://ipfs.marscoin.org/ipfs/', '', $proposal->ipfs_hash), 1, 8)) }}");
    };

    socket.onmessage = function(event) {
        if(!event.data.includes("#") && !event.data.includes("_"))
            $("#messages").append(`<br>[BALLOT SERVER]: ${event.data}`);
        
        if(event.data == "JOINED_ACK")
        {
            //Generate a new receive address for the ballot
            $("#messages").append('<br>[BALLOT SERVER]: Generating ballot for proposal');
            //generate ephemeral public key and shuffling keypair
            socket.send("SUBMIT_KEY#"+ek+"#")
        }
        if(event.data.includes("INITIATE_SHUFFLE_"))
        {
            $("#messages").append('<br>[BALLOT SERVER]: Initiating ballot shuffle...');
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
            $("#messages").append('<br>[BALLOT SERVER]: Performing ballot shuffle...');
            json = event.data.split("#")[1]
            data = JSON.parse(json);
            json = event.data.split("#")[2]
            sources = JSON.parse(json);
            if(index != data.length)
                console.log("Wrong order...")
            data = decrypt_data(data)
            data[index] = {
                "public_key": ek,
                "target": encrypted_target
            }
            sources[index] = inputBlock; 
            data =  shuffle_data(data)
            if (index == num_peers - 1){
                $("#messages").append('<br>[BALLOT SERVER]: Collecting signatures...');
                raw_tx = createRawTransaction(sources, data)
                socket.send("COLLECT_SIGNATURES#" + raw_tx)
            }
            else{
                socket.send("PERFORM_SHUFFLE_ACK#"+index+"#{'data': "+JSON.stringify(data) + "," + "'sources': "+JSON.stringify(sources)+"}")
            }
        }
        if(event.data.includes("SIGN_TX"))
        {
            raw_tx = event.data.split("#")[1];
            signed_raw_tx = signPartial(raw_tx );
            console.log("Partially signed message: " + index + ": "+ md5(signed_raw_tx))
            socket.send("SIGN_TX_COMPLETE#"+index+"#" + signed_raw_tx); 
        }
        if(event.data.includes("COMBINE_AND_BROADCAST"))
        {
            $("#messages").append('<br>[BALLOT SERVER]: Combining signatures and broadcasting...');
            raw_tx = event.data.split("#")[1];
            signed_raw_tx = combineAndBroadcastTransaction(raw_tx);
        }
    };

    socket.onclose = function(event) {
    if (event.wasClean) {
        alert(`[close] Connection closed cleanly, code=${event.code} reason=${event.reason}`);
    } else {
        $("#messages").append('<br>[close] Connection died');
    }
    };

    socket.onerror = function(error) {
        $("#messages").append(`<br>[error] ${error.message}`);
    };

    
    const zubrinConvert = (MARS) => {
                return (MARS * 100000000)
    }

    const marsConvert = (zubrin) => {
        return (zubrin / 100000000)
    }


    function createRawTransaction(sources, destinations)
    {
        console.log("Sources");
        console.log(sources);
        console.log("Destinations");
        console.log(destinations)
        var psbt = new my_bundle.bitcoin.Psbt({
            network: Marscoin.mainnet,
        });
        psbt.setVersion(1)
        psbt.setMaximumFeeRate(10000000);

        const zubs = zubrinConvert(amount)
        origins = []

        Object.keys(sources).forEach(function(k){
            iB = sources[k]
            console.log(iB);
            inputBlock = JSON.parse(iB)[0]
            psbt.addInput({
                hash: inputBlock.txId,
                index: inputBlock.vout,
                nonWitnessUtxo: my_bundle.Buffer.from(inputBlock.rawTx, 'hex'),
            })
            //change addresses back to citizen address, one per input(s)
            if (!origins.includes(inputBlock.originator)) {
                origins.push(inputBlock.originator);
                psbt.addOutput({
                    address: inputBlock.originator,
                    value: inputBlock.value - (zubs + zubrinConvert(0.1)),
                }) 
            }else{
                console.log("Already seen...")
            }
            
        });
        
        Object.keys(destinations).forEach(function(k){
            output = destinations[k];
            target = output['target']
            psbt.addOutput({
                address: target,
                value: zubs,
            }) 
            
        });
        
        // We have multiple signers sign in parallel and combine them.
        const psbtBaseText = psbt.toBase64();
        return psbtBaseText;
    }

    function signPartial(psbtBaseText)
    {
        // each signer imports
        const signer1 =  my_bundle.bitcoin.Psbt.fromBase64(psbtBaseText);
        signer1.signAllInputs(local_key);
        const s1text = signer1.toBase64();
        
        return s1text;
    }

    const combineAndBroadcastTransaction = async (signedTexts) => {
        signedTexts = JSON.parse(signedTexts)
        initial = signedTexts[0];
        const psbt =  my_bundle.bitcoin.Psbt.fromBase64(initial);
        console.log(signedTexts)
        console.log(Object.keys(signedTexts).length)
        console.log("Signer: 0")
        console.log(psbt.validateSignaturesOfInput(0));

        for (let i = 0; i < Object.keys(signedTexts).length; i++) {
            stext = signedTexts[i];
            console.log("Signer: " + i)
            fin =  my_bundle.bitcoin.Psbt.fromBase64(stext);
            psbt.combine(fin);
            console.log(psbt.validateSignaturesOfInput(i))
        }

        // build and broadcast our RegTest network
        const tx = psbt.finalizeAllInputs().extractTransaction(true); 
        const txhash = tx.toHex()
        console.log(txhash)

        try {
            const tx = await broadcastTxHash(txhash);
            console.log("Ballot issued and broadcasted... awaiting confirmation....");
            $("#pre-ballot").hide();
            $("#conf-ballot").show();
            pollConfirmation(tx.tx_hash);
            return tx;

        } catch (e) {
            handleError()
            throw e;
        }

    }

//YES vote with ballot
$("#pry").click(async (e) => {
    event.preventDefault();
    message = "PRY_<?=str_replace("https://ipfs.marscoin.org/ipfs/", "", $proposal->ipfs_hash)?>";
    const io = await sendMARS(0.01, hidden_target);
    const fee = 0.09
    const mars_amount = 0.01
    const total_amount = fee + parseInt(mars_amount)
    console.log("Fee for casting ballot (miner incentive): " + fee)
    console.log("ballot spent amount: " + mars_amount)

    try {
        const tx = await signMARS(message, mars_amount, io);
        $("#pre-ballot").hide();
        $("#conf-ballot").hide();
        $("#post-ballot").hide();
        $("#post-cast").show();
        $("#cast_confirmation").text(tx.tx_hash);
        $("#cast_confirmation").attr("href", "https://explore.marscoin.org/tx/"+ tx.tx_hash);
        const data = await doAjax("/api/setfeed", {"type": "PRY", "txid": tx.tx_hash, "embedded_link": "https://ipfs.marscoin.org/ipfs/"+cid, "address": '<?=$public_address?>'});
        if(data.Hash){
            alert('Submitted to Marscoin Blockchain successfully');
        }
    } catch (e) {
        throw e;
    }
})
//NO vote with ballot
$("#prn").click(async (e) => {
    event.preventDefault();
    message = "PRN_<?=str_replace("https://ipfs.marscoin.org/ipfs/", "", $proposal->ipfs_hash)?>";
    const io = await sendMARS(0.01, hidden_target);
    const fee = 0.09
    const mars_amount = 0.01
    const total_amount = fee + parseInt(mars_amount)
    console.log("Fee for casting ballot (miner incentive): " + fee)
    console.log("ballot spent amount: " + mars_amount)

    try {
        const tx = await signMARS(message, mars_amount, io);
        $("#pre-ballot").hide();
        $("#conf-ballot").hide();
        $("#post-ballot").hide();
        $("#post-cast").show();
        $("#cast_confirmation").text(tx.tx_hash);
        $("#cast_confirmation").attr("href", "https://explore.marscoin.org/tx/"+ tx.tx_hash);
        const data = await doAjax("/api/setfeed", {"type": "PRN", "txid": tx.tx_hash, "embedded_link": "https://ipfs.marscoin.org/ipfs/"+cid, "address": '<?=$public_address?>'});
        if(data.Hash){
            alert('Submitted to Marscoin Blockchain successfully');
        }
    } catch (e) {
        throw e;
    }
})
//ABSTAIN vote with ballot
$("#pra").click(async (e) => {
    event.preventDefault();
    message = "PRA_<?=str_replace("https://ipfs.marscoin.org/ipfs/", "", $proposal->ipfs_hash)?>";
    const io = await sendMARS(0.01, hidden_target);
    const fee = 0.09
    const mars_amount = 0.01
    const total_amount = fee + parseInt(mars_amount)
    console.log("Fee for casting ballot (miner incentive): " + fee)
    console.log("ballot spent amount: " + mars_amount)

    try {
        const tx = await signMARS(message, mars_amount, io);
        $("#pre-ballot").hide();
        $("#conf-ballot").hide();
        $("#post-ballot").hide();
        $("#post-cast").show();
        $("#cast_confirmation").text(tx.tx_hash);
        $("#cast_confirmation").attr("href", "https://explore.marscoin.org/tx/"+ tx.tx_hash);
        const data = await doAjax("/api/setfeed", {"type": "PRA", "txid": tx.tx_hash, "embedded_link": "https://ipfs.marscoin.org/ipfs/"+cid, "address": '<?=$public_address?>'});
        if(data.Hash){
            alert('Submitted to Marscoin Blockchain successfully');
        }
    } catch (e) {
        throw e;
    }
})

const sendMARS = async (mars_amount, receiver_address) => {
    const sender_address = receiver_address;
    try {
        const io = await getTxInputsOutputs(sender_address, receiver_address,mars_amount)
        return io
    } catch (e) {
        handleError()
        throw e;
    }
    return null
}

const signMARS = async (message, mars_amount, tx_i_o) => {

    const sender_address = hidden_target
    const zubs = zubrinConvert(mars_amount)

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
    //we burn the outputs as fee for the miners. this is a data-only tx
    for (let i = 0; i < tx_i_o.inputs.length; i++) {
        try{
            psbt.signInput(i, bpkk );
        } catch (e) {
            alert("Problem while trying to sign with your key. Please try to reconnect your wallet...");
        }
    }
    const tx = psbt.finalizeAllInputs().extractTransaction(); 
    const txhash = tx.toHex()
    console.log(txhash)
    try {
        const tx = await broadcastTxHash(txhash);
        console.log(tx.tx_hash);
        return tx;
    } catch (e) {
        handleError()
        throw e;
    }
}

const handleError = (str) => {
    console.log("ERROR:", str)
}



const broadcastTxHash = async (txhashstring) => {
    if (!txhashstring) {
        throw new Error("Missing tx hash...");
    }
    const url = "https://pebas.marscoin.org/api/mars/broadcast"
    try {
        const config = {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({a: 1, txhash: txhashstring})
        }
        const response = await fetch(url, config)
        if (response.ok) { 
            return response.json()
        } else {
            console.log(response)
        }
    } catch (error) {
        console.log(error)
    }

}


});
</script>

</body>

</html>
