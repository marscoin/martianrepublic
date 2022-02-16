<h3 class="content-title"><u>Create a Proposal</u></h3>
<form class="form account-form" method="POST" action="/congress/createproposal">
    <div class="row">
        <div class="form-group">

            <div class="col-lg-8">
                <label for="title">Title *</label>
                <input type="title" id="title" name="title" class="form-control parsley-validated" data-required="true">


                <label for="textarea-input">Description *</label>
                <textarea type="description" data-required="true" data-minlength="5" name="description" id="description"
                    cols="10" rows="20" style="min-height: 260px;" class="form-control"></textarea>

                <div style="display: flex; align-items: center; justify-content: flex-end; margin-top: 15px">

                </div>

            </div>

            <div class="col-lg-4">
                <div class="row">
                    <div class="col-lg-12">

                        <h4> Proposed by: <strong> {{ $fullname }} </strong></h4>

                        <br />

                        <label for="category">Proposal Category: </label>

                        <select name="category" id="category">

                            <option value="Basic">
                                Basic
                            </option>
                            <option value="Bill">
                                Bill
                            </option>
                            <option value="Amendment">
                                Amendment
                            </option>
                        </select>



                        <br />

                        <label for="name">Discussion URL *</label>
                        <div class="" style="display: flex; align-items: center; justify-content: center">

                            <input type="text" id="discussion" name="discussion" class="form-control parsley-validated"
                                data-required="true" placeholder="www.discussion.example.com">



                        </div>

                    </div>


                </div>


            </div>
        </div>

    </div>
    {{-- <button type="submit" class="btn-lg btn-primary">Submit</button> --}}
    <div>
        <span id="form-message" style="color:#d74b4b; font-weight: 600"> </span>
    </div>
    <a data-toggle="modal" href="#proposalModal" id="proposalModalBtn"
        class="btn-lg btn-primary demo-element">Confirm</a>

    {{-- <input type="text" id="yes_vote" name="yes_vote" class="form-control parsley-validated" data-required="true"
        style="display:none">
    <input type="text" id="no_vote" name="no_vote" class="form-control parsley-validated" data-required="true"
        style="display:none">
    <input type="text" id="null_vote" name="null_vote" class="form-control parsley-validated" data-required="true"
        style="display:none"> --}}


    <div id="proposalModal" class="modal fade">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title">Basic Modal</h3>
                </div> <!-- /.modal-header -->

                <div class="modal-body">
                    <div class="modal-body-box">
                        <h5>Category: </h5>
                        <p class="modal-category"> </p>
                    </div>


                    <div class="modal-body-box">
                        <h5> Description: </h5>
                        <p class="modal-description"></p>
                    </div>


                    <div class="modal-body-box">
                        <h5>Discussion URL: </h5>
                        <a class="modal-discussion-url" href=""> </a>
                    </div>


                    <div class="modal-body-box">
                        <h5>Cost of Proposal: </h5>
                        <h3 class="modal-cost"></h3>
                    </div>

                    <div class="modal-message" style="display: none">
                        <i class="fa fa-times-circle"></i>
                        <span id="modal-message-error" style="color:red; font-weight: 600"> </span>
                        <span id="modal-message-success" style="font-weight: 600"> </span>
                    </div>
                </div> <!-- /.modal-body -->
                <h5 class="transaction-hash">


                </h5>

                <div class="modal-footer">
                    <button id="submit-proposal" type="submit" class="btn btn-primary">Submit Proposal</button>
                    <img src="https://i.stack.imgur.com/FhHRx.gif" alt="enter image description here"
                        style="display: none" id="loading">
                </div> <!-- /.modal-footer -->

            </div> <!-- /.modal-content -->

        </div><!-- /.modal-dialog -->

    </div>



</form>
<script src="/assets/wallet/js/libs/jquery-1.10.2.min.js"></script>
<script src="/assets/wallet/js/dist/my_bundle.js"></script>

<script>
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
    // Click on confirm
    $("#proposalModalBtn").click(async (e) => {

        handleFormFilled()

        let title = $(".modal-title").text($("#title").val())
        let desc = $(".modal-description").text($("#description").val())
        let disc = $(".modal-discussion-url").text($("#discussion").val())
        $(".modal-discussion-url").attr("href", $("#discussion").val());
        let category = $(".modal-category").text($("#category").val())

        let prop = {
            Proposal_2: {
                title: $("#title").val(),
                description: $("#description").val(),
                discussion: $("#discussion").val(),
                category: $("#category").val(),
                timestamp: Date.now()
            }

        }


        const fee = 1;

        if ("{{ $balance }}" < fee) {
            $("#submit-proposal").prop("disabled", true)
            $("#modal-message-error").text("Not enough MARS to submit proposal")
            $(".modal-message").show()
            console.log("unable to confirm...")
            
            const io = await sendMARS(1, "<?= $public_address ?>");

            //const fee = marsConvert(io.fee);
            const fee = marsConvert(io.fee)
            //console.log("THE FEE: ", fee);
            const mars_amount = 0.001
            const total_amount = fee + parseInt(mars_amount)
            $(".modal-cost").text(total_amount + " MARS")

        } else {
            $(".modal-message").hide()

            console.log("able to confirm..")

            const cid = await addToIPFS(prop)

            console.log("CID:", cid)
            const message = "PR_" + cid;


            const io = await sendMARS(1, "<?= $public_address ?>");

            //const fee = marsConvert(io.fee);
            const fee = marsConvert(io.fee)
            //console.log("THE FEE: ", fee);
            const mars_amount = 0.001
            const total_amount = fee + parseInt(mars_amount)

            $(".modal-cost").text(total_amount + " MARS")


            // click on publish on modal..
            $("#submit-proposal").click(async () => {

                // Store messages to vote type on DB
                $("#yes_vote").text(message + "_yes")
                $("#no_vote").text(message + "_no")
                $("#null_vote").text(message + "_null")

                $("#loading").show()
                try {
                    const tx = await signMARS(message, mars_amount, io)
                    console.log(tx)
                    $("#loading").hide()
                    $("#modal-message-success").show()
                    $(".transaction-hash-link").attr("href",
                        "https://explore.marscoin.org/tx/" + tx.tx_hash)
                    $(".transaction-hash").text("" + tx.tx_hash)

                } catch (e) {
                    throw e;
                }

            })

        }


    })

    const handleFormFilled = () => {

        let title = $("#title").val()
        let desc = $("#description").val()
        let discussion = $("#discussion").val()

        if (title === "") {
            //title is blank
            $("#modal-message-error").text("Title is required...")
            $("#submit-proposal").prop("disabled", true)
            $(".modal-message").show()
            return false
        } else if (desc === "") {
            //desc is blank
            $("#modal-message-error").text("Description is required...")
            $("#submit-proposal").prop("disabled", true)
            $(".modal-message").show()

            return false
        } else if (discussion === "") {
            //discus is blank
            $("#modal-message-error").text("Discussion link is required...")
            $("#submit-proposal").prop("disabled", true)
            $(".modal-message").show()

            return false
        } else {
            $("#submit-proposal").prop("disabled", false)
            $(".modal-message").hide()
            return true
        }



    }



    ////// Proposal creation Logic..........
    const sendMARS = async (mars_amount, receiver_address) => {
        //console.log("send mars running...")

        // obtain utxo i/o
        const sender_address = "<?= $public_address ?>".trim()

        console.log("amount", mars_amount)
        console.log("receiver", receiver_address)
        console.log("sender", sender_address)

        try {
            const io = await getTxInputsOutputs(sender_address, receiver_address,
                mars_amount)

            return io
        } catch (e) {
            handleError("sending mars")
            throw e;
        }

        return null
    }

    const signMARS = async (message, mars_amount, tx_i_o) => {
        const mnemonic = localStorage.getItem("key").trim();
        const sender_address = "<?= $public_address ?>".trim()

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
        const embed = my_bundle.bitcoin.payments.embed({
            data: [data]
        });
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

        const txhash = psbt.finalizeAllInputs().extractTransaction().toHex()

        try {
            const txId = await broadcastTxHash(txhash);
            return txId

        } catch (e) {
            handleError()
            throw e;
        }

        return txId;

    }

    const addToIPFS = async (data) => {
        data = JSON.stringify(data)

        const URL = 'http://127.0.0.1:5001'

        // connect to the default API address http://localhost:5001
        const client = my_bundle.IPFS.create(URL)

        // connect using a URL

        // call Core API methods
        const {
            cid
        } = await client.add(data)


        return cid.toString()
    }



    const handleError = (text) => {
        console.log(text)
    }


    //===============================================================================
    //===============================================================================
    // API CALLS
    const PROD = "https://pebas.marscoin.org"
    const TEST = "http://127.0.0.1:3001"

    const getTxInputsOutputs = async (sender_address, receiver_address, amount) => {
        // Default options are marked with *
        if (!sender_address || !receiver_address || !amount) {
            throw new Error("Missing inputs for tx hash call...");
        }
        //console.log(sender_address)
        //console.log(receiver_address)
        //console.log(amount)

        const url =
            `${TEST}/api/mars/utxo?sender_address=${sender_address}&receiver_address=${receiver_address}&amount=${amount}`

        try {
            const response = await fetch(url, {
                method: 'GET', // *GET, POST, PUT, DELETE, etc.
            });

            return response.json()

        } catch (e) {
            console.log("error with io")
            throw e;
        }



    }

    const broadcastTxHash = async (txhash) => {
        if (!txhash) {
            throw new Error("Missing tx hash...");
        }

        const url =
            `${TEST}/api/mars/broadcast?txhash=${txhash}`
        try {
            const response = await fetch(url, {
                method: 'GET'
            });
            return response.json() // parses JSON response into native JavaScript objects
        } catch (e) {
            console.log("error with broadcast")
            throw e;
        }


    }


    const zubrinConvert = (MARS) => {
        return (MARS * 100000000)
    }

    const marsConvert = (zubrin) => {
        return (zubrin / 100000000)
    }

    // Check if user has enough funds to confirm proposal submission...
</script>
