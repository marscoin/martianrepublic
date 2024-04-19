<h3 class="content-title"><u>Active Proposals</u></h3>
<div class="row">
  <div class="col-md-9">

@if ($active->isEmpty())
    <div class="alert alert-info">
        Currently no active proposals underway. Consider launching a <a href="/congress/voting/new" style="color: white;">new proposal</a>!
    </div>
@else
    @foreach ($active as $proposal)
    <div class="post" style="border-bottom: 1px dotted #ccc;margin-bottom: 50px;padding-bottom: 50px;">
            <div class="post-aside">
                @php
                    $createdAt = \Carbon\Carbon::parse($proposal->mined);
                @endphp
                <div class="post-date">
                    <span class="post-date-day">{{ $proposal->id }}</span>
                    <span class="post-date-month">#</span>
                    <span class="post-date-year">Bill</span>
                </div>
                <a href="/forum/t/{{ $proposal->discussion }}" class="post-comment">
                {{$proposal->post_count}}
                </a>
            </div> 
            <div class="post-main">
                <h3 class="post-title"><a href="/congress/proposal/{{$proposal->id}}">{{ $proposal->title }}</a></h3>
                <h4 class="post-meta">Submitted by <a target="_blank" href="/citizen/id/{{ $proposal->public_address }}">{{ $proposal->author }}</a> in <a href="javascript:;">{{str_replace("poll", "Certified Poll", $proposal->category)}}</a></h4>
                <h5>Proposal: {{ strtoupper(substr(str_replace("https://ipfs.marscoin.org/ipfs/", "", $proposal->ipfs_hash), 1, 8)) }} <a  target="_blank" href="{{$proposal->ipfs_hash}}"><i class="fa-solid fa-link"></i></a></h5>
                <div class="post-content">      
                <p>{{substr($proposal->description, 0, 400)}}<a href="/congress/proposal/{{$proposal->id}}"> Read More...</a></p>
                    <div class="row">
                        <div class="col-sm-4" style="padding-top: 14px;">
                            <a href="#" class="btn btn-success">Voting in Progress</a>
                        </div>
                        <div class="col-sm-4">
                            @if(!$proposal->mined && $proposal->active)
                            @else
                            @php
                                $endTime = \Carbon\Carbon::parse($proposal->mined)->addDays($proposal->duration)->format('Y-m-d H:i:s');
                            @endphp
                            <x-countdown-timer :proposal-id="$proposal->id" :end-time="$endTime" :start-time="$proposal->mined" />
                            @endif
                        </div>
                        <div class="col-sm-4">
                            @if(!$proposal->mined && $proposal->active)
                            @else
                            @livewire('voting-progress', ['proposalId' => $proposal->id])
                            @endif
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    @endforeach
    @endif
    </div>

    <div class="col-md-3">
        <p>The <b>Martian Congressional Republic</b> consists of known <a href="/citizen/all">citizens</a> who discuss public matters ("res publica") in an open and transparent way. They vote on changes - including the very code that runs this application ("<b>The Constitution</b>") - in an equally transparent yet fully anonymous way. Every vote is cryptographically secured and can be audited by everyone. </p> 

        <p>Fair, transparent, immutable and auditable votes are the outcome. Our congressional archive lists all passed and failed proposals, bills, amendments and references all discussions. The Martian Congressional Republic embodies democracy as a living organism shared directly by all members of the Republic - allowing better ideas to win out and move Martian civilization forward. </p>

    </div>
</div>


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
