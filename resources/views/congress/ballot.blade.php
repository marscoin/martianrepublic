<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <title>Cast Ballot - Bill #{{ $proposal->id }} - Martian Republic Congress</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Cast your vote on the Martian Republic blockchain">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/assets/wallet/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-admin.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-flat.css">
    <link rel="shortcut icon" href="/assets/favicon.ico">
    <script src="/assets/wallet/js/plugins/scan/qrcode-gen.min.js"></script>

    <style>
    :root {
        --mr-void: #06060c;
        --mr-dark: #0c0c16;
        --mr-surface: #12121e;
        --mr-surface-raised: #1a1a2a;
        --mr-mars: #c84125;
        --mr-cyan: #00e4ff;
        --mr-green: #34d399;
        --mr-amber: #f59e0b;
        --mr-red: #ef4444;
        --mr-text: #e4e4e7;
        --mr-text-dim: #8a8998;
        --mr-border: rgba(255,255,255,0.06);
        --mr-border-bright: rgba(255,255,255,0.12);
    }

    html, body { background: #06060c !important; }
    .footer { z-index: 100; position: relative; clear: both; }
    #wrapper { overflow: hidden; }

    .ballot-page { min-height: 100vh; display: flex; flex-direction: column; }
    .ballot-page .content { flex: 1; }
    .ballot-page .footer { margin-top: auto; }

    .ballot-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 40px 0 60px;
    }

    /* ---- Proposal Quote Card ---- */
    .proposal-quote {
        background: var(--mr-surface);
        border: 1px solid var(--mr-border);
        border-left: 3px solid var(--mr-mars);
        border-radius: 0 10px 10px 0;
        padding: 28px 32px;
        margin-bottom: 32px;
    }
    .proposal-quote-id {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--mr-text-dim);
        margin-bottom: 10px;
    }
    .proposal-quote-title {
        font-size: 22px;
        font-weight: 700;
        color: #fff;
        line-height: 1.4;
        margin-bottom: 12px;
    }
    .proposal-quote-link {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        color: var(--mr-cyan);
        text-decoration: none;
    }
    .proposal-quote-link:hover { color: #fff; text-decoration: none; }

    /* ---- Status Alerts ---- */
    .ballot-alert {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 20px 24px;
        border-radius: 10px;
        margin-bottom: 28px;
        font-size: 14px;
        line-height: 1.6;
    }
    .ballot-alert i { font-size: 20px; margin-top: 2px; flex-shrink: 0; }
    .ballot-alert.alert-warning-custom {
        background: rgba(245,158,11,0.08);
        border: 1px solid rgba(245,158,11,0.2);
        color: var(--mr-amber);
    }
    .ballot-alert.alert-info-custom {
        background: rgba(0,228,255,0.06);
        border: 1px solid rgba(0,228,255,0.15);
        color: var(--mr-cyan);
    }
    .ballot-alert.alert-success-custom {
        background: rgba(52,211,153,0.08);
        border: 1px solid rgba(52,211,153,0.2);
        color: var(--mr-green);
    }
    .ballot-alert strong { display: block; font-size: 13px; font-family: 'Orbitron', sans-serif; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 4px; }

    /* ---- Messages Console ---- */
    .ballot-console {
        background: var(--mr-dark);
        border: 1px solid var(--mr-border);
        border-radius: 8px;
        padding: 20px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 12px;
        line-height: 1.8;
        color: var(--mr-green);
        max-height: 300px;
        overflow-y: auto;
        margin-bottom: 24px;
    }

    .ballot-section-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 12px;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--mr-text-dim);
        margin-bottom: 16px;
    }

    /* ---- Vote Buttons ---- */
    .vote-buttons {
        display: flex;
        gap: 16px;
        justify-content: center;
        margin: 40px 0;
    }
    .vote-btn {
        flex: 1;
        max-width: 220px;
        padding: 32px 24px;
        border-radius: 12px;
        text-align: center;
        font-family: 'Orbitron', sans-serif;
        font-size: 20px;
        font-weight: 800;
        letter-spacing: 3px;
        text-transform: uppercase;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        display: block;
    }
    .vote-btn:hover { text-decoration: none; transform: translateY(-2px); }

    .vote-btn-yes {
        background: rgba(52,211,153,0.1);
        color: var(--mr-green);
        border-color: rgba(52,211,153,0.3);
    }
    .vote-btn-yes:hover {
        background: var(--mr-green);
        color: var(--mr-void);
        box-shadow: 0 0 40px rgba(52,211,153,0.3);
        border-color: var(--mr-green);
    }
    .vote-btn-no {
        background: rgba(239,68,68,0.1);
        color: var(--mr-red);
        border-color: rgba(239,68,68,0.3);
    }
    .vote-btn-no:hover {
        background: var(--mr-red);
        color: #fff;
        box-shadow: 0 0 40px rgba(239,68,68,0.3);
        border-color: var(--mr-red);
    }
    .vote-btn-abstain {
        background: rgba(245,158,11,0.1);
        color: var(--mr-amber);
        border-color: rgba(245,158,11,0.3);
    }
    .vote-btn-abstain:hover {
        background: var(--mr-amber);
        color: var(--mr-void);
        box-shadow: 0 0 40px rgba(245,158,11,0.3);
        border-color: var(--mr-amber);
    }
    .vote-btn i { display: block; font-size: 28px; margin-bottom: 10px; }

    /* ---- Privacy Notice ---- */
    .privacy-notice {
        background: var(--mr-surface);
        border: 1px solid var(--mr-border);
        border-radius: 10px;
        padding: 24px;
        margin-top: 24px;
    }
    .privacy-notice p {
        font-size: 13px;
        line-height: 1.8;
        color: var(--mr-text-dim);
        margin: 0;
    }
    .privacy-notice strong { color: var(--mr-text); }

    /* ---- Blockchain animation ---- */
    .blockchain-anim {
        text-align: center;
        margin: 20px 0;
    }
    .blockchain-anim img { width: 180px; opacity: 0.8; }

    /* ---- Confirmation ---- */
    .cast-confirmation {
        text-align: center;
        padding: 40px 0;
    }
    .cast-confirmation .check-icon {
        width: 80px; height: 80px;
        border-radius: 50%;
        background: rgba(52,211,153,0.1);
        border: 2px solid var(--mr-green);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        color: var(--mr-green);
        margin-bottom: 20px;
    }
    .cast-confirmation h2 {
        font-family: 'Orbitron', sans-serif;
        font-size: 18px;
        letter-spacing: 2px;
        color: var(--mr-green);
        text-transform: uppercase;
    }

    .learn-more-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--mr-cyan);
        text-decoration: none;
        font-family: 'JetBrains Mono', monospace;
        font-size: 12px;
        margin-top: 16px;
        transition: color 0.2s;
    }
    .learn-more-link:hover { color: #fff; text-decoration: none; }

    @keyframes pulse { 0%,100%{opacity:1;} 50%{opacity:0.4;} }
    .pulse-dot {
        display: inline-block;
        width: 8px; height: 8px;
        border-radius: 50%;
        margin-right: 8px;
        animation: pulse 1.5s infinite;
    }
    </style>
</head>

<body class="ballot-page">
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
                @if($wallet_open)
                <div class="ballot-container">

                    {{-- ============ PRE-BALLOT: Waiting for shuffle ============ --}}
                    <div id="pre-ballot">
                        <div class="ballot-alert alert-warning-custom">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <div>
                                <strong>Do Not Close This Page</strong>
                                Keep this tab open until your ballot has been received and your vote cast.
                            </div>
                        </div>

                        <div class="proposal-quote">
                            <div class="proposal-quote-id">
                                <i class="fa-solid fa-landmark"></i>
                                Proposal #{{ strtoupper(substr(str_replace("https://ipfs.marscoin.org/ipfs/", "", $proposal->ipfs_hash), 1, 8)) }}
                            </div>
                            <div class="proposal-quote-title">{{ $proposal->title }}</div>
                            <a target="_blank" href="/forum/t/{{ $slug }}" class="proposal-quote-link">
                                <i class="fa-solid fa-comments"></i> View citizen discussion <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:9px;"></i>
                            </a>
                        </div>

                        <div class="ballot-section-title">Ballot Acquisition</div>
                        <p style="color:var(--mr-text-dim); font-size:14px; margin-bottom:16px; line-height:1.7;">
                            Monitoring the ballot shuffle process. As soon as enough voters are present, your secret ballot will be issued.
                            Once received, you can cast your vote from this page.
                        </p>

                        <div class="ballot-console" id="messages"></div>

                        <a target="_blank" href="https://marscoin.gitbook.io/marscoin-documentation/martian-republic/congress/ballot" class="learn-more-link">
                            <i class="fa-solid fa-book-open"></i> Learn how ballot shuffling works
                        </a>
                    </div>

                    {{-- ============ CONF-BALLOT: Ballot being registered ============ --}}
                    <div id="conf-ballot" style="display:none;">
                        <div class="ballot-alert alert-info-custom">
                            <i class="fa-solid fa-spinner fa-spin"></i>
                            <div>
                                <strong>Ballot Registering on Blockchain</strong>
                                Your private ballot is being confirmed. Voting will start shortly...
                            </div>
                        </div>

                        <div class="blockchain-anim">
                            <img src="/assets/wallet/img/blockchain.gif" alt="Blockchain confirmation">
                        </div>

                        <div class="proposal-quote">
                            <div class="proposal-quote-id">
                                <i class="fa-solid fa-landmark"></i>
                                Proposal #{{ strtoupper(substr(str_replace("https://ipfs.marscoin.org/ipfs/", "", $proposal->ipfs_hash), 1, 8)) }}
                            </div>
                            <div class="proposal-quote-title">{{ $proposal->title }}</div>
                        </div>
                    </div>

                    {{-- ============ POST-BALLOT: Cast your vote ============ --}}
                    <div id="post-ballot" style="display:none;">
                        <div class="ballot-alert alert-success-custom">
                            <i class="fa-solid fa-check-circle"></i>
                            <div>
                                <strong>Ballot Issued</strong>
                                Your secret ballot has been received. Cast your vote now.
                            </div>
                        </div>

                        <div class="proposal-quote">
                            <div class="proposal-quote-id">
                                <i class="fa-solid fa-landmark"></i>
                                Proposal #{{ strtoupper(substr(str_replace("https://ipfs.marscoin.org/ipfs/", "", $proposal->ipfs_hash), 1, 8)) }}
                            </div>
                            <div class="proposal-quote-title">{{ $proposal->title }}</div>
                            <a href="/forum/t/{{ $proposal->discussion }}" class="proposal-quote-link">
                                <i class="fa-solid fa-comments"></i> View citizen discussion
                            </a>
                        </div>

                        <div class="vote-buttons">
                            <a id="pry" href="#" class="vote-btn vote-btn-yes">
                                <i class="fa-solid fa-thumbs-up"></i>
                                Yes
                            </a>
                            <a id="prn" href="#" class="vote-btn vote-btn-no">
                                <i class="fa-solid fa-thumbs-down"></i>
                                No
                            </a>
                            <a id="pra" href="#" class="vote-btn vote-btn-abstain">
                                <i class="fa-solid fa-minus"></i>
                                Abstain
                            </a>
                        </div>

                        <div class="privacy-notice">
                            <p>
                                Your vote will be notarized on the blockchain. Due to the nature of your <strong>secret ballot</strong>,
                                your vote will NOT be traceable back to you. At the same time, as your ballot originated indirectly
                                from a citizen address, it will be fully auditable &mdash; providing evidence that all votes cast
                                originate only from citizens found in the voter registry.
                                <strong>One voter, one vote. End-to-end auditable. Immutable. Transparent.</strong>
                            </p>
                        </div>
                    </div>

                    {{-- ============ POST-CAST: Vote confirmed ============ --}}
                    <div id="post-cast" style="display:none;">
                        <div class="cast-confirmation">
                            <div class="check-icon"><i class="fa-solid fa-check"></i></div>
                            <h2>Vote Cast Successfully</h2>
                            <p style="color:var(--mr-text-dim); font-size:14px; margin-top:12px;">
                                Your ballot has been permanently recorded on the Marscoin blockchain.
                            </p>
                            <p style="margin-top:16px;">
                                <a id="cast_confirmation" href="" style="font-family:'JetBrains Mono',monospace; font-size:12px; color:var(--mr-cyan); text-decoration:none;"></a>
                            </p>
                        </div>

                        <div class="proposal-quote">
                            <div class="proposal-quote-id">
                                <i class="fa-solid fa-landmark"></i>
                                Proposal #{{ strtoupper(substr(str_replace("https://ipfs.marscoin.org/ipfs/", "", $proposal->ipfs_hash), 1, 8)) }}
                            </div>
                            <div class="proposal-quote-title">{{ $proposal->title }}</div>
                            <a href="/forum/t/{{ $proposal->discussion }}" class="proposal-quote-link">
                                <i class="fa-solid fa-comments"></i> View citizen discussion
                            </a>
                        </div>

                        <div style="text-align:center; margin-top:24px;">
                            <a href="/congress/voting" style="display:inline-flex; align-items:center; gap:8px; color:var(--mr-cyan); text-decoration:none; font-size:13px;">
                                <i class="fa-solid fa-arrow-left"></i> Back to Congress Hall
                            </a>
                        </div>
                    </div>

                </div>
                @else
                <div style="text-align:center; padding:80px 20px;">
                    <i class="fa-solid fa-check-to-slot" style="font-size:56px; color:var(--mr-text-dim); margin-bottom:20px; display:block; opacity:0.5;"></i>
                    <h2 style="font-family:'Orbitron',sans-serif; font-size:20px; color:#fff; letter-spacing:1px; margin-bottom:12px;">Wallet Required</h2>
                    <p style="color:var(--mr-text-dim); font-size:14px; margin-bottom:24px;">Unlock your civic wallet to cast your vote.</p>
                    <a href="/wallet/dashboard/hd" style="display:inline-flex; align-items:center; gap:10px; background:var(--mr-mars); color:#fff; padding:14px 28px; border-radius:8px; font-family:'Orbitron',sans-serif; font-size:12px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; text-decoration:none;">
                        <i class="fa-solid fa-lock-open"></i> Unlock Wallet
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <footer class="footer" style="border-top:1px solid var(--mr-border,rgba(255,255,255,0.06)); padding:20px 0; background:var(--mr-void,#06060c); z-index:100;">
        @include('footer')
    </footer>

    <script src="/assets/wallet/js/dist/my_bundle.js"></script>
    <script src="/assets/wallet/js/libs/jquery-1.10.2.min.js"></script>
    <script src="/assets/wallet/js/libs/bootstrap.min.js"></script>
    <script src="/assets/wallet/js/jquery-ui.min.js"></script>
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
    var ek = crypt.getPublicKeyB64()
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

    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
    });

    async function doAjax(ajaxurl, args) {
        try {
            return await $.ajax({ url: ajaxurl, type: 'POST', data: args });
        } catch (error) {
            console.error(error);
        }
    }

    const getLocalKey = async () => {
        const mnemonic = WalletKey.get().trim();
        const sender_address = "<?=$public_address?>".trim()
        const seed = my_bundle.bip39.mnemonicToSeedSync(mnemonic);
        const root = my_bundle.bip32.fromSeed(seed, Marscoin.mainnet)
        const child = root.derivePath("m/44'/2'/0'/0/0");
        const wif = child.toWIF()
        local_key = my_bundle.bitcoin.ECPair.fromWIF(wif, Marscoin.mainnet);
        yk = local_key.privateKey.toString('hex')
        yp = local_key.publicKey.toString('hex')
        yb = my_bundle.Buffer.from(yp, 'hex')
    }

    const init = async () => {
        hidden_target = getProposalOutputAddress();
        $("#messages").append('<br>> Generated ballot target address');
        inputBlock = await getInputBlock()
        $("#messages").append('<br>> Prepared inputs for ballot');
        await getLocalKey()
        $("#messages").append('<br>> Local key loaded');
    }

    const getInputBlock = async () => {
        sources = []
        sender_address = addr;
        receiver_address = hidden_target;
        const io = await getTxInputsOutputs(sender_address, receiver_address, 0.1)
        io.inputs.forEach((input, i) => {
            var obj = {'txId': input.txId, 'vout': input.vout, 'rawTx':  my_bundle.Buffer.from(input.rawTx, 'hex'), 'value': input.value, 'originator': "{{$public_address}}"};
            sources.push(obj);
        })
        sources_string = JSON.stringify(sources);
        return sources_string;
    }

    const getTxInputsOutputs = async (sender_address, receiver_address, amount) => {
        if (!sender_address || !receiver_address || !amount) {
            throw new Error("Missing inputs for tx hash call...");
        }
        const url = `https://pebas.marscoin.org/api/mars/utxo?sender_address=${sender_address}&receiver_address=${receiver_address}&amount=${amount}`
        try {
            const response = await fetch(url, { method: 'GET' });
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
            str = str.length == 0 ? "00" : str.length == 1 ? "0" + str : str.length == 2 ? str : str.substring(str.length-2, str.length);
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
        seed = my_bundle.bip39.mnemonicToSeedSync(mnemonic);
        root = my_bundle.bip32.fromSeed(seed, Marscoin.mainnet)
        child = root.derivePath("m/999999'/107'/<?=$propid?>'");
        wif2 = child.toWIF()
        bpkk = my_bundle.bitcoin.ECPair.fromWIF(wif2, Marscoin.mainnet);
        yk = bpkk.privateKey.toString('hex')
        yp = bpkk.publicKey.toString('hex')
        yb = my_bundle.Buffer.from(yp, 'hex')
        addy = nodeToLegacyAddress(bpkk)
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
        $("#messages").append('<br>> Ballot seed generated');
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

    function shuffle(array) {
        let currentIndex = array.length, randomIndex;
        while (currentIndex != 0) {
            randomIndex = Math.floor(Math.random() * currentIndex);
            currentIndex--;
            [array[currentIndex], array[randomIndex]] = [array[randomIndex], array[currentIndex]];
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
            vp = v;
            vp['target'] = decrypt(privkey, v["target"]);
            new_data[i] = vp;
        }
        return new_data;
    }

    function encrypt(epkey, message){
        var crypt = new JSEncrypt();
        crypt.setKey(epkey);
        var password = "test";
        var iterations = 500;
        var keySize = 256;
        var salt = CryptoJS.lib.WordArray.random(128/8);
        var output = CryptoJS.PBKDF2(password, salt, { keySize: keySize/32, iterations: iterations });
        messageKey = output.toString(CryptoJS.enc.Base64)
        var ctext = CryptoJS.AES.encrypt(message, messageKey);
        encMsg = ctext.toString();
        ckey = crypt.encrypt(messageKey);
        encMsgTotal = ckey + encMsg
        return encMsgTotal;
    }

    function decrypt(keypair, ctext){
        var crypt = new JSEncrypt();
        crypt.setKey(keypair);
        encM = ctext
        encKey = encM.substring(0, 172)
        ctext = encM.substring(172, encM.length)
        messageKey = crypt.decrypt(encKey)
        message = CryptoJS.AES.decrypt(ctext, messageKey);
        return message.toString(CryptoJS.enc.Utf8)
    }

    function encrypt_dest(){
        var t = hidden_target
        for (let i = num_peers-1;i > index; --i){
            t = encrypt(order[i], t)
        }
        return t
    }

    function construct_transaction(data, sources){
        raw_tx = "";
        return raw_tx;
    }

    $("#messages").html("> Connecting to ballot server...")
    var socket;
    var domain = document.domain.split('.')[1]
    if(domain == "local")
        socket = new WebSocket("wss://127.0.0.1:3678");
    else
        socket = new WebSocket("wss://martianrepublic.org/wss/ballot");

    socket.onopen = function(e) {
        $("#messages").append("<br>> Connection established");
        $("#messages").append("<br>> Registering voter...");
        socket.send("{{$public_address}}_{{ strtoupper(substr(str_replace('https://ipfs.marscoin.org/ipfs/', '', $proposal->ipfs_hash), 1, 8)) }}");
    };

    socket.onmessage = function(event) {
        if(!event.data.includes("#") && !event.data.includes("_"))
            $("#messages").append(`<br>> ${event.data}`);

        if(event.data == "JOINED_ACK") {
            $("#messages").append('<br>> Generating ballot keypair...');
            socket.send("SUBMIT_KEY#"+ek+"#")
        }
        if(event.data.includes("INITIATE_SHUFFLE_")) {
            $("#messages").append('<br>> Initiating ballot shuffle...');
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
                socket.send("SHUFFLE_INIT_COMPLETE_"+JSON.stringify(encrypted_target));
        }
        if(event.data.includes("PERFORM_SHUFFLE")) {
            $("#messages").append('<br>> Performing ballot shuffle...');
            json = event.data.split("#")[1]
            data = JSON.parse(json);
            json = event.data.split("#")[2]
            sources = JSON.parse(json);
            if(index != data.length) console.log("Wrong order...")
            data = decrypt_data(data)
            data[index] = { "public_key": ek, "target": encrypted_target }
            sources[index] = inputBlock;
            data = shuffle_data(data)
            if (index == num_peers - 1){
                $("#messages").append('<br>> Collecting signatures...');
                raw_tx = createRawTransaction(sources, data)
                socket.send("COLLECT_SIGNATURES#" + raw_tx)
            } else {
                socket.send("PERFORM_SHUFFLE_ACK#"+index+"#{'data': "+JSON.stringify(data) + "," + "'sources': "+JSON.stringify(sources)+"}")
            }
        }
        if(event.data.includes("SIGN_TX")) {
            raw_tx = event.data.split("#")[1];
            signed_raw_tx = signPartial(raw_tx);
            socket.send("SIGN_TX_COMPLETE#"+index+"#" + signed_raw_tx);
        }
        if(event.data.includes("COMBINE_AND_BROADCAST")) {
            $("#messages").append('<br>> Combining signatures and broadcasting...');
            raw_tx = event.data.split("#")[1];
            signed_raw_tx = combineAndBroadcastTransaction(raw_tx);
        }
    };

    socket.onclose = function(event) {
        if (event.wasClean) {
            $("#messages").append(`<br>> Connection closed (code: ${event.code})`);
        } else {
            $("#messages").append('<br>> Connection lost');
        }
    };

    socket.onerror = function(error) {
        $("#messages").append(`<br>> Error: ${error.message}`);
    };

    const zubrinConvert = (MARS) => { return (MARS * 100000000) }
    const marsConvert = (zubrin) => { return (zubrin / 100000000) }

    function createRawTransaction(sources, destinations) {
        var psbt = new my_bundle.bitcoin.Psbt({ network: Marscoin.mainnet });
        psbt.setVersion(1)
        psbt.setMaximumFeeRate(10000000);
        const zubs = zubrinConvert(amount)
        origins = []
        Object.keys(sources).forEach(function(k){
            iB = sources[k]
            inputBlock = JSON.parse(iB)[0]
            psbt.addInput({ hash: inputBlock.txId, index: inputBlock.vout, nonWitnessUtxo: my_bundle.Buffer.from(inputBlock.rawTx, 'hex') })
            if (!origins.includes(inputBlock.originator)) {
                origins.push(inputBlock.originator);
                psbt.addOutput({ address: inputBlock.originator, value: inputBlock.value - (zubs + zubrinConvert(0.1)) })
            }
        });
        Object.keys(destinations).forEach(function(k){
            output = destinations[k];
            target = output['target']
            psbt.addOutput({ address: target, value: zubs })
        });
        return psbt.toBase64();
    }

    function signPartial(psbtBaseText) {
        const signer1 = my_bundle.bitcoin.Psbt.fromBase64(psbtBaseText);
        signer1.signAllInputs(local_key);
        return signer1.toBase64();
    }

    const broadcastTxHash = async (txhashstring) => {
        if (!txhashstring) throw new Error("Missing tx hash...");
        const url = "https://pebas.marscoin.org/api/mars/broadcast"
        try {
            const config = {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
                body: JSON.stringify({a: 1, txhash: txhashstring})
            }
            const response = await fetch(url, config)
            if (response.ok) { return response.json() } else { console.log("Broadcast response not OK:", response) }
        } catch (error) { console.error("Broadcast error:", error) }
    }

    const combineAndBroadcastTransaction = async (signedTexts) => {
        signedTexts = JSON.parse(signedTexts)
        initial = signedTexts[0];
        const psbt = my_bundle.bitcoin.Psbt.fromBase64(initial);
        for (let i = 0; i < Object.keys(signedTexts).length; i++) {
            stext = signedTexts[i];
            fin = my_bundle.bitcoin.Psbt.fromBase64(stext);
            psbt.combine(fin);
        }
        const tx = psbt.finalizeAllInputs().extractTransaction(true);
        const txhash = tx.toHex()
        try {
            const tx = await broadcastTxHash(txhash);
            $("#pre-ballot").hide();
            $("#conf-ballot").show();
            pollConfirmation(tx.tx_hash);
            return tx;
        } catch (e) {
            handleError()
            throw e;
        }
    }

    // YES vote
    $("#pry").click(async (e) => {
        event.preventDefault();
        message = "PRY_<?=str_replace("https://ipfs.marscoin.org/ipfs/", "", $proposal->ipfs_hash)?>";
        const io = await sendMARS(0.01, hidden_target);
        try {
            const tx = await signMARS(message, 0.01, io);
            $("#pre-ballot").hide(); $("#conf-ballot").hide(); $("#post-ballot").hide(); $("#post-cast").show();
            $("#cast_confirmation").text(tx.tx_hash);
            $("#cast_confirmation").attr("href", "https://explore.marscoin.org/tx/"+ tx.tx_hash);
            const data = await doAjax("/api/setfeed", {"type": "PRY", "txid": tx.tx_hash, "embedded_link": "https://ipfs.marscoin.org/ipfs/"+cid, "address": '<?=$public_address?>'});
        } catch (e) { throw e; }
    })

    // NO vote
    $("#prn").click(async (e) => {
        event.preventDefault();
        message = "PRN_<?=str_replace("https://ipfs.marscoin.org/ipfs/", "", $proposal->ipfs_hash)?>";
        const io = await sendMARS(0.01, hidden_target);
        try {
            const tx = await signMARS(message, 0.01, io);
            $("#pre-ballot").hide(); $("#conf-ballot").hide(); $("#post-ballot").hide(); $("#post-cast").show();
            $("#cast_confirmation").text(tx.tx_hash);
            $("#cast_confirmation").attr("href", "https://explore.marscoin.org/tx/"+ tx.tx_hash);
            const data = await doAjax("/api/setfeed", {"type": "PRN", "txid": tx.tx_hash, "embedded_link": "https://ipfs.marscoin.org/ipfs/"+cid, "address": '<?=$public_address?>'});
        } catch (e) { throw e; }
    })

    // ABSTAIN vote
    $("#pra").click(async (e) => {
        event.preventDefault();
        message = "PRA_<?=str_replace("https://ipfs.marscoin.org/ipfs/", "", $proposal->ipfs_hash)?>";
        const io = await sendMARS(0.01, hidden_target);
        try {
            const tx = await signMARS(message, 0.01, io);
            $("#pre-ballot").hide(); $("#conf-ballot").hide(); $("#post-ballot").hide(); $("#post-cast").show();
            $("#cast_confirmation").text(tx.tx_hash);
            $("#cast_confirmation").attr("href", "https://explore.marscoin.org/tx/"+ tx.tx_hash);
            const data = await doAjax("/api/setfeed", {"type": "PRA", "txid": tx.tx_hash, "embedded_link": "https://ipfs.marscoin.org/ipfs/"+cid, "address": '<?=$public_address?>'});
        } catch (e) { throw e; }
    })

    const sendMARS = async (mars_amount, receiver_address) => {
        const sender_address = receiver_address;
        try {
            const io = await getTxInputsOutputs(sender_address, receiver_address, mars_amount)
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
        var psbt = new my_bundle.bitcoin.Psbt({ network: Marscoin.mainnet });
        psbt.setVersion(1)
        psbt.setMaximumFeeRate(10000000);
        var data = my_bundle.Buffer(message)
        const embed = my_bundle.bitcoin.payments.embed({ data: [data] });
        psbt.addOutput({ script: embed.output, value: 0 })
        tx_i_o.inputs.forEach((input, i) => {
            psbt.addInput({ hash: input.txId, index: input.vout, nonWitnessUtxo: my_bundle.Buffer.from(input.rawTx, 'hex') })
        })
        for (let i = 0; i < tx_i_o.inputs.length; i++) {
            try { psbt.signInput(i, bpkk); } catch (e) { alert("Problem signing. Please reconnect your wallet."); }
        }
        const tx = psbt.finalizeAllInputs().extractTransaction();
        const txhash = tx.toHex()
        try {
            const tx = await broadcastTxHash(txhash);
            return tx;
        } catch (e) {
            handleError()
            throw e;
        }
    }

    const handleError = (str) => { console.log("ERROR:", str) }

});
</script>

</body>
</html>
