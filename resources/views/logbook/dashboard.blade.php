<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <title>Martian Republic - Logbook</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="The Marscoin Foundation">
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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">

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
            </div> 
        </header>
        @include('wallet.mainnav', array('active'=>'logbook', 'balance' => $balance))
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
    <script src="/assets/wallet/js/dist/my_bundle.js"></script>
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
<script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
<script>
        var simplemde = new SimpleMDE({ element: document.getElementById("description") });
</script>


<script>
  $(function(){
    $.fn.filepond.registerPlugin(FilePondPluginImagePreview);
    $('.my-pond').filepond();
    $('.my-pond').filepond('allowMultiple', true);
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

$("#saveLogLocalBtn").click(function(event) {
    event.preventDefault();
    var formData = new FormData()
    var files = $('.my-pond').filepond('getFiles');
	$(files).each(function (index) {
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
        success: function(data) {
            // Check if we found a folder hash
            if(data.Hash !== "") {
                $("#ipfs_path").val(data.Hash);
                alert("Successfully saved to the planetary file system!");
                location.href="/logbook/all#My"
            } else {
                // Handle the case where no folder hash was found
                console.error("No folder hash found in the response.");
                alert("An error occurred. Please try again.");
            }
        },
        error: function(errorResponse) {
            // Handle error
            console.log(errorResponse);
            alert("Failed to save to the planetary file system.");
        }
    });
});

$(".notarizemeModalBtn").click(async (e) => 
{
        const fee = 0.1;
        var publication = $(e.currentTarget).attr('rel');
        $(".modal-document").text(publication);
        $(".transaction-hash-link").text("");

        if ("{{ $balance }}" < fee) {
            $("#submit-notarization").prop("disabled", true)
            $("#modal-message-error").text("Not enough MARS to submit log entry")
            $(".modal-message").show()
            console.log("unable to confirm...")
            return false;

        } else {
            $(".modal-message").hide()
            $(".modal-footer").show();
            console.log("able to confirm..")
            $("#submit-notarization").prop("disabled", false)
            $("#submit-notarization").click(async () => {

                $("#loading").show()
                try {

                    cid = publication;
                    message = "LB_"+cid;
                    const io = await sendMARS(1, "<?=$public_address?>");
                    const fee = 0.01
                    const mars_amount = 0.01
                    const total_amount = fee + parseInt(mars_amount)

                    try {
                        const tx = await signMARS(message, mars_amount, io);
                        $(".transaction-hash-link").text("" + tx.tx_hash)
                        $(".transaction-hash-link").attr("href","https://explore.marscoin.org/tx/" + tx.tx_hash)
                        $(".modal-message").show();
                        $(".modal-footer").hide();
                        const data = await doAjax("/api/setfeed", {"type": "LB", "txid": tx.tx_hash, message: $("#modal-document").val(), "embedded_link": "https://ipfs.marscoin.org/ipfs/"+cid, "address": '<?=$public_address?>'});
                        
                    } catch (e) {
                        throw e;
                    }

                } catch (e) {
                    throw e;
                }

            })

        }


    })

    var cidToDelete;  
    var deleteRow; 

    $(".unpinModalBtn").click(async (e) => 
    {
        cidToDelete = $(e.currentTarget).attr('rel');
        deleteRow = $(this).closest('tr'); 
    })

    $("#confirmDelete").click(async (e) => 
    {
        if (!cidToDelete) {
            console.error('CID missing for deletion.');
            return;
        }

        try {
            const response = await $.ajax({
                url: '/api/removelog', // Adjust this to your actual endpoint
                method: 'POST',
                data: { cid: cidToDelete },
                dataType: 'json'
            });

            if (response.error) {
                $('#confirmDeleteModal').modal('hide');
                alert('Error deleting publication:' + response.error);
            } else {
                $('#confirmDeleteModal').modal('hide');
                console.log('Publication deleted successfully:', response.message);
                deleteRow.remove();
                location.reload();
            }
        } catch (error) {
            console.error('AJAX request failed:', error);
        }
    
    })


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


<script type="text/javascript">
$(document).ready( function () {
    $('.allentries').DataTable({
        "order": [[4, "desc"]] 
    });

    $('.myentries').DataTable({
        "order": [[3, "desc"]] 
    });
});
</script>

</body>

</html>
