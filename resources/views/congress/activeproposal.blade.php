<h3 class="content-title"><u>Active Proposals</u></h3>
<p>The <b>Martian Congressional Republic</b> consists of known <a href="/citizen/all">citizens</a> who discuss public matters ("res publica") in an open and transparent way. They vote on changes - including the very code that runs this application ("<b>The Constitution</b>") - in an equally transparent yet fully anonymous way. Every vote is cryptographically secured and can be audited by everyone. </p> 

<p>Fair, transparent, immutable and auditable votes are the outcome. Our congressional archive lists all passed and failed proposals, bills, amendments and references all discussions. The Martian Congressional Republic embodies democracy as a living organism shared directly by all members of the Republic - allowing better ideas to win out and move Martian civilization forward. </p>

@foreach ($proposals as $proposal)
    <div class="feed-item feed-item-idea">

        <div class="feed-icon">
            <i class="fa fa-lightbulb-o"></i>
        </div> <!-- /.feed-icon -->
        <div class="feed-subject">
            <h5>Proposal #{{ $proposal->id }}</h5>
            <h3><a href="javascript:;">{{ $proposal->author }} </a> proposed: <a
                href="https://ipfs.io/ipfs/{{{$proposal->ipfs_hash}}}">{{ $proposal->title }} </a></h3>
        </div> <!-- /.feed-subject -->



        <div class="feed-content">
            <ul class="icons-list">
                <li>
                    <i class="icon-li fa fa-quote-left"></i>
                    <p style="font-size: 2rem">
                        {{ $proposal->description }}

                    </p>
                </li>
            </ul>
            <span>Vote Threshold: {{$proposal->threshold}}%</span>
            <div class="progress progress-sm" style="width: 50%">
                <div class="progress-bar progress-bar-primary" role="progressbar"
                    aria-valuenow={{$proposal->threshold}} aria-valuemin="0" aria-valuemax="100"
                    style="width: {{{$proposal->threshold}}}%">
                    <span class="sr-only">40% Complete (primary)</span>
                </div>
            </div>
            <div>
                <a data-toggle="modal" href="#ProposalModal_{{{$proposal->id}}}_yes" id="Y_{{{$proposal->ipfs_hash}}}"
                class="btn-lg btn-primary demo-element vote-modal-btn-yes">Yes</a>

                <a data-toggle="modal" href="#ProposalModal_{{{$proposal->id}}}_no" id="N_{{{$proposal->ipfs_hash}}}"
                class="btn-lg btn-primary demo-element vote-modal-btn-no">No</a>

                <a data-toggle="modal" href="#ProposalModal_{{{$proposal->id}}}_null" id="NU_{{{$proposal->ipfs_hash}}}"
                class="btn-lg btn-primary demo-element vote-modal-btn-null">Null</a>
                    

            </div>
        </div> <!-- /.feed-content -->


        <div class="feed-actions">
            
            <a href={{ $proposal->discussion }} class="pull-left discussion-link">
                {{ $proposal->discussion }} <i class="fa fa-external-link"></i></a>

            <a href="javascript:;" class="pull-right"><i class="fa fa-clock-o"></i>
                {{ $proposal->created_at }}</a>
        </div> <!-- /.feed-actions -->

    </div>

    <!--YES MODAL -->
    <div id="ProposalModal_{{{$proposal->id}}}_yes" class="modal fade dynamic-vote-modal">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title">Yes: {{$proposal->title}}</h3>
                </div> <!-- /.modal-header -->

                <div class="modal-body">
                    <div class="modal-body-box">
                        <h5>Category: </h5>
                        <p class="modal-category"> {{$proposal->category}}</p>
                    </div>


                    <div class="modal-body-box">
                        <h5> Description: </h5>
                        <p class="modal-description">{{$proposal->description}}</p>
                    </div>


                    <div class="modal-body-box">
                        <h5>Discussion URL: </h5>
                        <a class="modal-discussion-url" href=""> {{$proposal->discussion}}</a>
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
                    <div class="modal-message">
                        <a class="transaction-hash-link" href="" target="_blank" rel="noreferrer noopener">
                            <h5 class="transaction-hash">
                            </h5>
                        </a>
                    </div>
                </div> <!-- /.modal-body -->

                <div class="modal-footer">
                    <img src="https://i.stack.imgur.com/FhHRx.gif" alt="enter image description here"
                    style="display: none" id="loading">
                    <button id="submit-yes-vote" type="submit" class="btn btn-primary">Vote</button>
                    <img src="https://i.stack.imgur.com/FhHRx.gif" alt="enter image description here"
                        style="display: none" id="loading">
                </div> <!-- /.modal-footer -->

            </div> <!-- /.modal-content -->

        </div><!-- /.modal-dialog -->

    </div>

    <!--NO MODAL -->
    <div id="ProposalModal_{{{$proposal->id}}}_no" class="modal fade dynamic-vote-modal">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title">Yes: {{$proposal->title}}</h3>
                </div> <!-- /.modal-header -->

                <div class="modal-body">
                    <div class="modal-body-box">
                        <h5>Category: </h5>
                        <p class="modal-category"> {{$proposal->category}}</p>
                    </div>


                    <div class="modal-body-box">
                        <h5> Description: </h5>
                        <p class="modal-description">{{$proposal->description}}</p>
                    </div>


                    <div class="modal-body-box">
                        <h5>Discussion URL: </h5>
                        <a class="modal-discussion-url" href=""> {{$proposal->discussion}}</a>
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

                    <div class="modal-message">
                        
                        <a rel="noreferrer noopener" target="_blank" class="transaction-hash-link" href="" style="display: flex, text-align: center, align-items: center">
                            <h5 class="transaction-hash">
        
        
                            </h5>
                        </a>
                    </div>
                </div> <!-- /.modal-body -->
              

                <div class="modal-footer">
                    <span id="modal-message-success" style="font-weight: 600"> </span>
                    <button id="submit-no-vote" type="submit" class="btn btn-primary">Vote</button>
                    <img src="https://i.stack.imgur.com/FhHRx.gif" alt="enter image description here"
                        style="display: none" id="loading">
                </div> <!-- /.modal-footer -->

            </div> <!-- /.modal-content -->

        </div><!-- /.modal-dialog -->

    </div>

    <!--NULL MODAL -->
    <div id="ProposalModal_{{{$proposal->id}}}_null" class="modal fade dynamic-vote-modal">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title">Yes: {{$proposal->title}}</h3>
                </div> <!-- /.modal-header -->

                <div class="modal-body">
                    <div class="modal-body-box">
                        <h5>Category: </h5>
                        <p class="modal-category"> {{$proposal->category}}</p>
                    </div>


                    <div class="modal-body-box">
                        <h5> Description: </h5>
                        <p class="modal-description">{{$proposal->description}}</p>
                    </div>


                    <div class="modal-body-box">
                        <h5>Discussion URL: </h5>
                        <a class="modal-discussion-url" href=""> {{$proposal->discussion}}</a>
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
                    <div class="modal-message">

                        <a rel="noreferrer noopener"  target="_blank" class="transaction-hash-link" href="" style="display: flex, text-align: center, align-items: center">
                            <h5 class="transaction-hash">
        
                            </h5>
                        </a>
                    </div>
                    
    
                </div> <!-- /.modal-body -->
              
                <div class="modal-footer">
                    <button id="submit-null-vote" type="submit" class="btn btn-primary">Vote</button>
                    <img src="https://i.stack.imgur.com/FhHRx.gif" alt="enter image description here"
                        style="display: none" id="loading">
                </div> <!-- /.modal-footer -->

            </div> <!-- /.modal-content -->

        </div><!-- /.modal-dialog -->

    </div>
@endforeach
<script src="/assets/wallet/js/libs/jquery-1.10.2.min.js"></script>
<script src="/assets/wallet/js/dist/my_bundle.js"></script>
<script>
    $(document).ready(function() {

        $('.dynamic-vote-modal').on('hidden.bs.modal', function () {
            location.reload();
        })

        ////// Proposal creation Logic..........
        // YES VOTE
        $(".vote-modal-btn-yes").click(async (e)=>
        {
           await buildAndSign(e, "yes")
        })

        // NO VOTE
        $(".vote-modal-btn-no").click(async (e)=>
        {

           await  buildAndSign(e, "no")
        })

        // NULL VOTE
        $(".vote-modal-btn-null").click(async (e)=>
        {
            await buildAndSign(e, "null")
         
        })

        // Construct and send tx...
        const buildAndSign = async (e, vote) =>
        {
            const message = "PR" + e.target.id
            //console.log("message:",message)

            const io = await sendMARS(1, "{{$public_address}}");

            //const fee = marsConvert(io.fee);
            const fee = marsConvert(io.fee)
            //console.log("THE FEE: ", fee);
            const mars_amount = 0.001
            const total_amount = fee + parseInt(mars_amount)

            $(".modal-cost").text(total_amount + " MARS")

            $(`#submit-${vote}-vote`).click(async(e)=>
            {
                $("#loading").show()
                $(`#submit-${vote}-vote`).hide()
                try {
                    const tx = await signMARS(message, mars_amount, io)
                    $("#loading").hide()
                    $("#modal-message-success").show()
                    $(".transaction-hash-link").attr("href",
                        "https://explore.marscoin.org/tx/" + tx.tx_hash)
                    $(".transaction-hash").text("" + tx.tx_hash)

                } catch (e) {
                    handleError("signing mars")
                    throw e;
                }
            })
        }



        // Transaction OP-Return Building Logic
         ////// Proposal creation Logic..........
        const sendMARS = async (mars_amount, receiver_address) => {
        //console.log("send mars running...")

        // obtain utxo i/o
        const public_address = "{{$public_address}}"
        const sender_address = public_address.trim()


        // console.log("amount", mars_amount)
        // console.log("receiver", receiver_address)
        // console.log("sender", sender_address)

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
        
        const sender_address = "{{$public_address}}".trim()

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

        //console.log(psbt.finalizeAllInputs().extractTransaction().toHex());
        var txId = "";
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

    })
</script>
