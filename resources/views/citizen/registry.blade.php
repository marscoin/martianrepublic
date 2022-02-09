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
    <link rel="shortcut icon" href="/assets/favicon.ico">
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
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
                    @include('wallet.navbarleft', array('info' => $network ))
                    @include('wallet.navbarright')
                </nav>
            </div> <!-- /.container -->
        </header>
        @include('wallet.mainnav', array('active'=>'citizen', 'info'=>$network, 'balance' => $balance))
        <div class="content">

            <div class="container">

                <div class="portlet">
                    <div class="portlet-body">

                        <ul id="myTab1" class="nav nav-tabs">
                            <li class="active">
                                <?php if(!$isCitizen && !$isGP){?>
                                    <a href="#new" data-toggle="tab">Join Mars!</a>
                                <?php }else{ ?>
                                    <a href="#new" data-toggle="tab">Welcome back, Martian!</a>
                                <?php } ?>
                            </li>
                            <li class="">
                                <a href="#citizens" data-toggle="tab">Citizens</a>
                            </li>

                            <li class="">
                                <a href="#all" data-toggle="tab">Public</a>
                            </li>
                        </ul>
                        <div id="myTab1Content" class="tab-content" style="margin-top: 50px;">

                            <div class="tab-pane fade active in" id="new">
                                <?php if(!$isCitizen && !$isGP){?>
                                    @include('citizen.joinpublic')
                                <?php }else{ ?>
                                    @include('citizen.profile')
                                <?php } ?>
                            </div>
                            <!-- Show citizens allegable to vote -->
                            <div class="tab-pane fade " id="citizens">
                                @include('citizen.allcitizens')
                            </div>

                            <!-- Show the general public -->
                            <div class="tab-pane fade" id="all">
                                @include('citizen.allpublic')
                            </div>
                            <!-- New public registration form -->
   

                        </div>

                    </div>

                </div> <!-- /.portlet -->
                       
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

<script>
$(document).ready(function() {

    $('.input-form').blur(function() {   
        firstName = $('#firstname').val();
        $("#s_firstname").text(firstName);
        lastName = $('#lastname').val();
        $("#s_lastname").text(lastName);
        displayname = $('#displayname').val();
        $("#s_displayname").text(displayname);
        shortbio = $('#shortbio').val();
        $("#s_shortbio").text(shortbio);
});

$("#saveprofilebutton").click(function() {
    //api
    pic = $("#photo").attr("src");
    event.preventDefault();
    $.post("/api/permapinpic", {"type": "profile_pic", "picture": pic, "address": '<?=$public_address?>'} , function(data) {
        if(data){
            cid = data.Hash;
            $("#s_ipfs_profile_pic").text("https://ipfs.marscoin.org/ipfs/"+ cid);
            $("#photo").attr("src", "https://ipfs.marscoin.org/ipfs/"+ cid);
        }
    });
});


$("#savevideo").click(function() {
    //api
    vid = $("#finished-video").attr("src");
    event.preventDefault();
    console.log(vid);
    var formData = new FormData()
    formData.append('file', current_blob)
    formData.append('address', '<?=$public_address?>')
    $.ajax({
        url:"/api/permapinvideo",
        type:"POST",
        data: formData,
        processData: false,
        contentType: false,
        success:function(data){
            cid = data.Hash;
            $("#s_ipfs_liveness_vid").text("https://ipfs.marscoin.org/ipfs/"+ cid);
            $("#finished-video").attr("src", "https://ipfs.marscoin.org/ipfs/"+ cid);
        },
        error: function(data){
            console.log(data);
        }   
    });
});

$("#publish").click(async (e) => {
    event.preventDefault();
    //api
    $("#publish_progress").show();
    $("#publish_progress_message").text("Publishing...").delay(2500).fadeOut();
    $("#publish_progress_message").text("Generating passport...").delay(2500).fadeOut();
    var obj = new Object();
    obj.data = {};
    obj.meta = {};
    obj.data.firstName = $('#firstname').val();
    obj.data.lastName = $('#lastname').val();
    obj.data.displayname = $('#displayname').val();
    obj.data.shortbio = $('#shortbio').val();
    obj.data.picture = $('#s_ipfs_profile_pic').text();
    obj.data.video = $('#s_ipfs_liveness_vid').text();
    var jsonString = JSON.stringify(obj.data);
    $("#publish_progress_message").text("Generating data hash...").delay(2500).fadeOut();
    var m = sha256(jsonString);
    obj.meta.hash = m;
    var jsonString = JSON.stringify(obj);
    $("#publish_progress_message").text("Writing data to IPFS and cache...");
    // $.post("/api/permapinjson", {"type": "registration", "payload": jsonString, "address": '<?=$public_address?>'} , function (data) {
    //     if(data){
    //         cid = data.Hash;
    //         // $("#s_ipfs_profile_pic").text("https://ipfs.marscoin.org/ipfs/"+ cid);
    //         // $("#photo").attr("src", "https://ipfs.marscoin.org/ipfs/"+ cid);
    //         $("#publish_progress_message").text("CID acquired: " + cid).delay(2500).fadeOut();
    //         $("#publish_progress_message").text("Blockchain anchoring in progress...").delay(2500).fadeOut();
    //         //creating transaction with OP_RETURN "GP_CID"
    //         $("#publish_progress_message").text("Finalizing passport...");


    //     }
    // })

    const data = await doAjax("/api/permapinjson", {"type": "registration", "payload": jsonString, "address": '<?=$public_address?>'});
    cid = data.Hash;

    message = "GP_"+cid;
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
        $("#publish_progress_message").text("Published successfully...").delay(2500).fadeOut();
        $("#publish_progress_message").text(tx.tx_hash);
        document.location.reload();

    } catch (e) {
        throw e;
    }
})


async function doAjax(ajaxurl, args) {
    let result;

    try {
        result = await $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: args
        });

        return result;
    } catch (error) {
        console.error(error);
    }
}



$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
  e.target // newly activated tab
  //alert(e.target)
  //alert(e.target.id)
  if(e.target.hash == "#profile-1")
  {
    $("#top_help_text").html("Welcome to the <br><b>Martian Citizen Registry</b>.  <br><br>In a first step simply register your profile.  <br><br>This allows others to endorse you and elevate you to Citizen status which in turn gives you the ability to launch public proposals and vote.<br><br> There are two quick parts to this application: a) Some basic information about you and b) A short video clip proving you are human<br><br> In a last step your application will be uploaded to a decentralized public file system known as IPFS. <br> Your <b>Martian Identity</b> is then automatically connected to your public Marscoin address. <br><br> Think of this as a voter database that is transparent, incorruptible, decentralized and efficient.")
  }
  if(e.target.hash == "#profile-2")
  {
    $("#top_help_text").html("<p>As part of your <b>Martian Citizen Registry</b> profile, a small video clip will be included that proves that you are human (<b>Proof of Humanity</b>). <br><br>Please record a short clip of yourself saying the following words: <br><br><b>\"I herewith declare that I, (your name), am human and a member of the Martian Republic.\"</b> while <i>holding your Marscoin address into the camera</i>. <br><br><a target='_blank' href='/citizen/printout'>Print Me!</a> <br><br> <a href=''>Example 1</a>  <a href=''>Example 2</a></p>")
  }
  if(e.target.hash == "#profile-3")
  {
    $("#top_help_text").html("<p><b>Summary</b> <br><br>The following information will be published to IPFS - a shared public file storage system - and anchored into the Marscoin blockchain to establish your decentralized Martian Identity.</p>")
  }
})



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
    var txId = "";
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