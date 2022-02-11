<h3 class="content-title"><u>General Public</u></h3>
<div class="row">
    <div class="col-md-9">
        <div class="table-responsive">
            <table class="table table-striped table-bordered thumbnail-table">
                <thead>
                    <tr>
                        <th style="width: 150px">Profile Picture</th>
                        <th>Public Address</th>
                        <th>Since</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($everyPublic as $gp){?>
                    <tr>
                        <td>
                            <img id="photo" src="/assets/citizen/<?= $gp->address ?>/profile_pic.png"
                                class="profile-avatar-img thumbnail" alt="Profile Image">
                        </td>
                        <td class="valign-middle">
                            <a href="javascript:;" title=""><?= $gp->fullname ?> </a>
                            <p><a target="_blank"
                                    href="https://explore.marscoin.org/tx/<?= $gp->txid ?>"><?= $gp->address ?></a></p>

                            {{-- <button class="endorse-btn primary-btn" id="{{{$gp->id}}}">Endorse </button> --}}

                            <a data-toggle="modal" href="#endorseModal_{{{$gp->id}}}" id="{{{$gp->address}}}"
                              class="btn-sm btn-primary demo-element endorse-btn">Endorse for Citizenship</a>
                        </td>
                        <td class="valign-middle"><?= $gp->mined ?></td>
                        <td class="file-info valign-middle">
                            <span class="label label-default demo-element public-status">General Public</span>
                        </td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td>
                            <img id="photo" src="/assets/citizen/generic_profile.jpg"
                                class="profile-avatar-img thumbnail" alt="Profile Image">
                        </td>
                        <td class="valign-middle"><a href="javascript:;" title="">Sandrine Kirino</a>
                            <p><a target="_blank"
                                    href="https://explore.marscoin.org/address/MGCxvRiRrScBWbCSX1QhtG1axMR5ku3XcK">MGCxvRiRrScBWbCSX1QhtG1axMR5ku3XcK</a>
                            </p>

                            <a data-toggle="modal" href="#endorseModal_" id=""
                              class="btn-sm btn-primary demo-element endorse-btn">Endorse for Citizenship</a>
                        </td>
                        <td class="valign-middle">Feb 12, 2022. 12:28</td>
                        <td class="file-info valign-middle">
                            <span class="label label-default demo-element public-status">General Public</span>
                        </td>
                    </tr>

                    {{-- <tr>
                        <td>
                            <img id="photo" src="/assets/citizen/generic_profile.jpg"
                                class="profile-avatar-img thumbnail" alt="Profile Image">
                        </td>
                        <td class="valign-middle"><a href="javascript:;" title="">Theresa Yao</a>
                            <p><a target="_blank"
                                    href="https://explore.marscoin.org/address/MGCxvRiRrScBWbCSX1QhtG1axMR5ku3XcK">MGCxvRiRrScBWbCSX1QhtG1axMR5ku3XcK</a>
                            </p>
                        </td>
                        <td class="valign-middle">Feb 12, 2022. 12:28</td>
                        <td class="file-info valign-middle">
                            <span class="label label-default demo-element public-status">General Public</span>
                        </td>
                    </tr> --}}

                    {{-- <tr>
                        <td>

                            <img id="photo" src="/assets/citizen/generic_profile.jpg"
                                class="profile-avatar-img thumbnail" alt="Profile Image">

                        </td>
                        <td class="valign-middle"><a href="javascript:;" title="">Esai Martin</a>
                            <p><a target="_blank"
                                    href="https://explore.marscoin.org/address/MGCxvRiRrScBWbCSX1QhtG1axMR5ku3XcK">MGCxvRiRrScBWbCSX1QhtG1axMR5ku3XcK</a>
                            </p>
                        </td>
                        <td class="valign-middle">Feb 12, 2022. 12:28</td>
                        <td class="file-info valign-middle">
                            <span class="label label-default demo-element public-status">General Public</span>
                        </td>
                    </tr> --}}


                </tbody>
            </table>

        </div>
    </div>

    <div class="col-md-3">
        <h5 class="content-title"><u>Martian Society Stats</u></h5>

        <div class="list-group">

            <a href="javascript:;" class="list-group-item">
                <h3 class="pull-right"><i class="fa fa-eye text-primary"></i></h3>
                <h4 class="list-group-item-heading">38,847</h4>
                <p class="list-group-item-text">Martians</p>
            </a>

            <a href="javascript:;" class="list-group-item">
                <h3 class="pull-right"><i class="fa fa-facebook-square  text-primary"></i></h3>
                <h4 class="list-group-item-heading">3,482</h4>
                <p class="list-group-item-text">Citizens</p>
            </a>

            <a href="javascript:;" class="list-group-item">
                <h3 class="pull-right"><i class="fa fa-twitter-square  text-primary"></i></h3>
                <h4 class="list-group-item-heading">5</h4>
                <p class="list-group-item-text">Open Proposals</p>
            </a>
        </div> <!-- /.list-group -->

        <br>

        <h5 class="content-title"><u>Activity</u></h5>

        <div class="well">


            <ul class="icons-list text-md">

                <li>
                    <i class="icon-li fa fa-location-arrow"></i>

                    <strong>Jennifer Rowlings</strong> joined.
                    <br>
                    <small>about 4 hours ago</small>
                </li>

                <li>
                    <i class="icon-li fa fa-location-arrow"></i>

                    <strong>Paul Anderson</strong> joined.
                    <br>
                    <small>about 23 hours ago</small>
                </li>

                <li>
                    <i class="icon-li fa fa-location-arrow"></i>

                    <strong>John Smith</strong> joined.
                    <br>
                    <small>2 days ago</small>
                </li>
            </ul>

        </div> <!-- /.well -->

        <div class="row">

            <div class="col-md-6 col-sm-6">
                <div class="thumbnail">
                    <div class="thumbnail-view">
                         <a href="/assets/citizen/mars_flag_q1.png" class="thumbnail-view-hover ui-lightbox"></a>
                            <img src="/assets/citizen/mars_flag_q1.png" style="width: 100%" alt="Gallery Image">
                    </div>
                </div>

            </div> <!-- /.col -->


            <div class="col-md-6 col-sm-6">


                <div class="thumbnail">
                    <div class="thumbnail-view">
                        <a href="/assets/citizen/mars_flag2.png" class="thumbnail-view-hover ui-lightbox"></a>
                        <img src="/assets/citizen/mars_flag2.png" style="width: 100%" alt="Gallery Image">
                    </div>
                </div>

            </div> <!-- /.col -->

        </div>

        <div class="row">

            <div class="col-md-6 col-sm-6">

                <div class="thumbnail">
                    <div class="thumbnail-view">
                        <a href="/assets/citizen/mars_flag5.jpg" class="thumbnail-view-hover ui-lightbox"></a>
                        <img src="/assets/citizen/mars_flag5.jpg" style="width: 100%" alt="Gallery Image">
                    </div>
                </div>

            </div> <!-- /.col -->
            <div class="col-md-6 col-sm-6">

                <div class="thumbnail">
                    <div class="thumbnail-view">
                        <a href="/assets/citizen/mars_flag5.png" class="thumbnail-view-hover ui-lightbox"></a>
                        <img src="/assets/citizen/mars_flag5.png" style="width: 100%" alt="Gallery Image">
                    </div>
                </div>

            </div> <!-- /.col -->


        </div>

    </div>

</div> <!-- /.col -->

<!--Modal Start -->

<?php foreach($everyPublic as $gp){?>

<div id="endorseModal_{{{$gp->id}}}" class="modal fade dynamic-vote-modal">

  <div class="modal-dialog">

      <div class="modal-content">

          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              <h3 class="modal-title">Endorse: {{$gp->fullname}}</h3>
          </div> <!-- /.modal-header -->

          <div class="modal-body">
              <div class="modal-body-box">
                  <p class="modal-category"> </p>
              </div>


              <div class="modal-body-box">
                  <h5> User Address </h5>
                  <p class="modal-description">{{$gp->address}}</p>
              </div>
            
              <div class="modal-body-box">
                  <h5>Cost of Endorsement: </h5>
                  <h3 class="modal-cost"></h3>
              </div>

              <div class="modal-message" style="display: none">
                  
                  <span id="modal-message-error" style="color:red; font-weight: 600"> </span>
                  <span id="modal-message-success" style="font-weight: 600"> <i class="fa fa-check-circle"></i> Successfully Endorsed <h3>{{$gp->fullname}}</h3></span>
              </div>
              <div class="modal-message" style="display: flex, align-items: center">
                    <a  rel="noreferrer noopener"  target="_blank"  class="transaction-hash-link" href="">
                        <h5 class="transaction-hash">
            
            
                        </h5>
                    </a>
                </div>

            </div> <!-- /.modal-body -->
          


          <div class="modal-footer">
          
              <button id="confirm-endorse-btn-{{{$gp->address}}}" type="submit" class="btn btn-primary submit-endorse">Confirm Endorsement</button>
              <img src="https://i.stack.imgur.com/FhHRx.gif" alt="enter image description here"
                  style="display: none" id="loading">
          </div> <!-- /.modal-footer -->

      </div> <!-- /.modal-content -->

  </div><!-- /.modal-dialog -->

</div>
<?php } ?>




<script src="/assets/wallet/js/libs/jquery-1.10.2.min.js"></script>
<script src="/assets/wallet/js/dist/my_bundle.js"></script>
<script>
    $(document).ready(function() {

        $('.dynamic-vote-modal').on('hidden.bs.modal', function () {
            location.reload();
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

      $(".endorse-btn").click(async (e)=>
      {
        let id = e.target.id
        console.log(id)

       if ("{{ $balance }}" < 5) {
            $("#confirm-endorse-btn").prop("disabled", true)
            $("#modal-message-error").text("Not enough MARS to submit proposal")
            $(".modal-message").show()

            console.log("unable to confirm...")
            
            const io = await sendMARS(1, "<?= $public_address ?>");

            console.log(io)
            //const fee = marsConvert(io.fee);
            const fee = marsConvert(io.fee)
            //console.log("THE FEE: ", fee);
            const mars_amount = 0.001
            const total_amount = fee + parseInt(mars_amount)
            $(".modal-cost").text(total_amount + " MARS")
            
       }
       else{
         await buildAndSign(id)
       }
       

      })




        // Construct and send tx...
        const buildAndSign = async (id) => {
            const message = "EN_" + id

            console.log("message:", message)

            const io = await sendMARS(1, "<?= $public_address ?>");

            //const fee = marsConvert(io.fee);
            const fee = marsConvert(io.fee)
            //console.log("THE FEE: ", fee);
            const mars_amount = 0.001
            const total_amount = fee + parseInt(mars_amount)

            $(".modal-cost").text(total_amount + " MARS")

            $(`#confirm-endorse-btn-${id}`).click(async (e) => {
                console.log("button clicked")
                $("#loading").show()
                $(`.submit-endorse`).hide()
                try {
                    const tx = await signMARS(message, mars_amount, io)
                    console.log(tx)
                    $("#loading").hide()
                    $(".modal-message").show()
                    $(".transaction-hash-link").attr("href",
                        "https://explore.marscoin.org/tx/" + tx.tx_hash)
                    $(".transaction-hash").text(tx.tx_hash)

                } catch (e) {
                    throw e;
                }
            })
        }



        // Transaction OP-Return Building Logic
        ////// Proposal creation Logic..........
        const sendMARS = async (mars_amount, receiver_address) => {
            //console.log("send mars running...")

            // obtain utxo i/o
            const sender_address = "<?= $public_address ?>".trim()

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
