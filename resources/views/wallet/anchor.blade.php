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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@700&family=Orbitron:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/wallet/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-admin.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-flat.css">
    <link rel="stylesheet" href="/assets/wallet/js/plugins/fileupload/bootstrap-fileupload.css">
    <link rel="shortcut icon" href="favicon.ico">
    <script>var current_blob = null;</script>
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
        @include('wallet.mainnav', array('active'=>'citizen'))
        <div class="content">

            <div class="container">

                <p>Anchor test</p>
                <a href="#" id="publish" class="btn btn-primary">Publish</a>
                       
            </div> <!-- /.container -->
        </div> <!-- .content -->
    </div> <!-- /#wrapper -->
    <footer class="footer">
        @include('footer')
    </footer>
    <script src="/assets/wallet/js/dist/my_bundle.js"></script>
    <script src="/assets/wallet/js/libs/jquery-1.10.2.min.js"></script>
    <script src="/assets/wallet/js/libs/bootstrap.min.js"></script>
    <script src="/assets/wallet/js/plugins/flot/jquery.flot.js"></script>
    <script src="/assets/wallet/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="/assets/wallet/js/plugins/flot/jquery.flot.pie.js"></script>
    <script src="/assets/wallet/js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="/assets/wallet/js/plugins/fileupload/bootstrap-fileupload.js"></script>
    <script src="/assets/wallet/js/plugins/magnific/jquery.magnific-popup.min.js"></script>
    <script src="/assets/wallet/js/mvpready-core.js"></script>
    <script src="/assets/wallet/js/mvpready-admin.js"></script>
    <script src="/assets/wallet/js/md5.min.js"></script>
    <script src="/assets/wallet/js/sha256.js"></script>


<script type="text/javascript">
        
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

$("#publish").click(async (e) => {
    event.preventDefault();
    cid = "QmSyMr2CjV1qS4cBpVv9EpeXZDuaWxMSyY5Z7Hwrpdb1Mi";
    message = "test_"+cid;

    const io = await sendMARS(1, "<?=$public_address?>");

    //const fee = marsConvert(io.fee);
    const fee = 0.001
    //console.log("THE FEE: ", fee);
    const mars_amount = 0.001
    const total_amount = fee + parseInt(mars_amount)
    $(".estimated-fee").text("$ " + fee)
    $(".conversion-rate").text(total_amount)

    try {
        const tx = await signMARS(message, mars_amount, io)
        //$("#loading").hide()
        //$(".success-message").show()
        // $(".transaction-hash-link").attr("href",
        //     "https://explore.marscoin.org/tx/" + tx.tx_hash)
        //$(".transaction-hash").text("" + tx.tx_hash)

    } catch (e) {
        throw e;
    }

})

const sendMARS = async (mars_amount, receiver_address) => {
    //console.log("send mars running...")

    // obtain utxo i/o
    const sender_address = "<?=$public_address?>".trim()

    try {
        const io = await getTxInputsOutputs(sender_address, receiver_address,
            mars_amount)

        return io
    } catch (e) {
        handleError()
        throw e;
    }

    return null
}

const signMARS = async (message, mars_amount, tx_i_o) => {
    const mnemonic = localStorage.getItem("key").trim();
    const sender_address = "<?=$public_address?>".trim()

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

    unspent_vout = 0
    var data = my_bundle.Buffer(message)
    const embed = my_bundle.bitcoin.payments.embed({ data: [data] });
    //var dataScript = psbt.script.nullDataOutput(data)
    psbt.addOutput({
    script: embed.output,
    value: 0,
    })
    //psbt.addOutput(dataScript, 1000)

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

    } catch (e) {
        handleError()
        throw e;
    }

    return txId;

}

const handleError = () => {
    console.log("PANIC AN ERROR!!!!!!!!")
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


const zubrinConvert = (MARS) => {
    return (MARS * 100000000)
}

const marsConvert = (zubrin) => {
    return (zubrin / 100000000)
}


});

</script>
</body>

</html>
