<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <title>Martian Republic - Logbook</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald:400,300,700">
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@700&family=Orbitron:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/wallet/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-admin.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-flat.css">
    <link rel="stylesheet" href="/assets/wallet/css/simplemde.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/upload.css">
    <link rel="stylesheet" href="/assets/wallet/css/dropify.css">
    <link rel="shortcut icon" href="/assets/favicon.ico">
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet"/>
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
            </div> 
        </header>
        @include('wallet.mainnav', array('active'=>'logbook', 'info'=>$network, 'balance' => $balance))
        <div class="content">
            <div class="container">
                <?php if($wallet_open){ ?>
                <div class="portlet">
                    <div class="portlet-body">

                        <ul id="myTab1" class="nav nav-tabs">
                            <li class="active">
                                <a href="#New-Entry" data-toggle="tab">New Entry</a>
                            </li>
                            <li class="">
                                <a href="#All" data-toggle="tab">All Publications</a>
                            </li>
                            <li class="">
                                <a href="#My" data-toggle="tab">My Publications</a>
                            </li>
                        </ul>
                        <div id="myTab1Content" class="tab-content" style="margin-top: 50px;">                            
                            <div class="tab-pane fade  active in" id="New-Entry">
                                @include('logbook.logbook')
                            </div> 
                            <div class="tab-pane fade" id="All">
                                @include('logbook.allentries')
                            </div> 
                            <div class="tab-pane fade" id="My">
                                @include('logbook.myentries')
                            </div> 
                        </div>

                    </div>
                </div>
                 <?php }else{ ?>
                <div class="portlet">
                    <div class="portlet-body">
                        <h3>
                            Please open / connect your wallet in order to access the Research Logbook.
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
    <script src="/assets/wallet/js/libs/jquery-1.10.2.min.js"></script>
    <script src="/assets/wallet/js/libs/bootstrap.min.js"></script>
    <script src="/assets/wallet/js/plugins/flot/jquery.flot.js"></script>
    <script src="/assets/wallet/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="/assets/wallet/js/plugins/flot/jquery.flot.pie.js"></script>
    <script src="/assets/wallet/js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="/assets/wallet/js/mvpready-core.js"></script>
    <script src="/assets/wallet/js/mvpready-admin.js"></script>
    <script src="/assets/wallet/js/simplemde.min.js"></script>
    <script src="/assets/wallet/js/md5.min.js"></script>
    <script src="/assets/wallet/js/sha256.js"></script>
    <script src="/assets/wallet/js/dropify.js"></script>

    <!-- include jQuery library -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script> -->
<script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
<script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>

<script>
        var simplemde = new SimpleMDE({ element: document.getElementById("description") });
</script>


<script>
  $(function(){
  
    // First register any plugins
    $.fn.filepond.registerPlugin(FilePondPluginImagePreview);

    // Turn input element into a pond
    $('.my-pond').filepond();

    // Set allowMultiple property to true
    $('.my-pond').filepond('allowMultiple', true);
  
    // Listen for addfile event
    $('.my-pond').on('FilePond:addfile', function(e) {
        console.log('file added event', e);
    });
  
  });
</script>

<script>
$(document).ready(function() {


$.ajaxSetup({
headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});  


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




$("#saveLogLocalBtn").click(function() {
    event.preventDefault();
    var formData = new FormData()
    // $.each($("input[type='file']")[0].files, function(i, file) {
    //     formData.append('filenames[]', file);
    // });
    var files = $('.my-pond').filepond('getFiles');
	$(files).each(function (index) {
		console.log(files[index].fileExtension);
        formData.append('filenames[]', files[index].file);
	});
    formData.append('address', '<?=$public_address?>')
    formData.append('title', $("#title").val())
    formData.append('entry', simplemde.value())
    $.ajax({
        url:"/api/permapinlog", 
        type:"POST",
        data: formData,
        processData: false,
        contentType: false,
        success:function(data){
            cid = data.Hash;
            $("#ipfs_path").val(cid);
        },
        error: function(data){
            console.log(data);
        }   
    });
});


// Click on confirm
$("#logModalBtn").click(async (e) => {

        handleFormFilled()

        let ipfs_path = $("#ipfs_path").val()

        const fee = 1;

        if ("{{ $balance }}" < fee) {
            $("#submit-log").prop("disabled", true)
            $("#modal-message-error").text("Not enough MARS to submit log entry")
            $(".modal-message").show()
            console.log("unable to confirm...")
            return false;

        } else {
            $(".modal-message").hide()

            console.log("able to confirm..")
            $("#submit-log").prop("disabled", false)
            $("#submit-log").click(async () => {

                $("#loading").show()
                try {

                    var obj = new Object();
                    obj.data = {};
                    obj.meta = {};
                    obj.data.title = $("#title").val();
                    obj.data.description = simplemde.value();
    

                    var jsonString = JSON.stringify(obj.data);
                    var m = sha256(jsonString);
                    obj.meta.hash = m;
                    var jsonString = JSON.stringify(obj);
                    utcnow = new Date().getTime();
                    const data = await doAjax("/api/permapinjson", {"type": "proposal_"+utcnow, "payload": jsonString, "address": '<?=$public_address?>'});
                    if(data.Hash == "Error"){
                        alert("Pinning data failed. Check IPFS connection and try again later.")
                        return false;
                    }
                    cid = data.Hash;

                    message = "PR_"+cid;
                    const io = await sendMARS(1, "<?=$public_address?>");
                    const fee = 0.01
                    const mars_amount = 0.01
                    const total_amount = fee + parseInt(mars_amount)

                    try {
                        const tx = await signMARS(message, mars_amount, io);
                        $(".transaction-hash").text("" + tx.tx_hash)
                        $(".transaction-hash-link").attr("href","https://explore.marscoin.org/tx/" + tx.tx_hash)
                        const data = await doAjax("/api/setfeed", {"type": "PR", "txid": tx.tx_hash, message: $("#title").val(), "embedded_link": "https://ipfs.marscoin.org/ipfs/"+cid, "address": '<?=$public_address?>'});
                        if(data.Hash){
                            $("#modal-message-success").show()
                            $("#loading").hide()
                            $(".modal-footer").hide();
                            const data = await doAjax("/api/cacheproposal", {"type": "PR", "txid": tx.tx_hash, message: jsonString, "embedded_link": "https://ipfs.marscoin.org/ipfs/"+cid, "address": '<?=$public_address?>'});
                            if(data.Discussion){
                                //if(!alert('Submitted to Blockchain successfully')){location.href = '/forum/'+data.Discussion;}
                            }
                        }
                    } catch (e) {
                        throw e;
                    }

                } catch (e) {
                    throw e;
                }

            })

        }


    })

    const handleFormFilled = () => {

        let title = $("#title").val()
        let desc = $("#description").val()

        if (title === "") {
            //title is blank
            $("#modal-message-error").text("Title is required...")
            $("#submit-log").prop("disabled", true)
            $(".modal-message").show()
            return false
        } else if (desc === "") {
            //desc is blank
            $("#modal-message-error").text("Entry is required...")
            $("#submit-log").prop("disabled", true)
            $(".modal-message").show()

            return false
        }
    }



////////////////////////////

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
    const seed = my_bundle.bip39.mnemonicToSeedSync(mnemonic);
    const root = my_bundle.bip32.fromSeed(seed, Marscoin.mainnet)
    const child = root.derivePath("m/44'/2'/0'/0/0");
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
            method: 'GET',
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

    const url = 'https://pebas.marscoin.org/api/mars/broadcast?txhash='+txhash
    try {
        const response = await fetch(url, {
            method: 'GET'
        });
        const shorthash =  response.json() 
        return shorthash;
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
