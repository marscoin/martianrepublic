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
    <link rel="stylesheet" href="/assets/wallet/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-admin.css">
    <link rel="stylesheet" href="/assets/wallet/css/mvpready-flat.css">
    <link rel="stylesheet" href="/assets/wallet/css/voting/voting.css">
    <link rel="stylesheet" href="/assets/wallet/css/simplemde.min.css">
    <link rel="shortcut icon" href="/assets/favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" integrity="sha512-1cK78a1o+ht2JcaW6g8OXYwqpev9+6GqOkz9xmBN9iUUhIndKtxwILGWYOSibOKjLsEdjyjZvYDq/cZwNeak0w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        span.qrcodeicon span {
            position: absolute;
            display: block;
            top: 7px;
            right: 21px;
            width: 18px;
            height: 18px;
            background: url('/assets/wallet/img/qrcode.png');
            cursor: pointer;
            z-index: 1;
        }


        .price-box {
    margin: 0 auto;
	background: #E9E9E9;
	border-radius: 10px;
	padding: 40px 15px;
	width: 500px;
}

.ui-widget-content {
	border: 1px solid #bdc3c7;
	background: #e1e1e1;
	color: #222222;
	margin-top: 4px;
}

.ui-slider .ui-slider-handle {
	position: absolute;
	z-index: 2;
	width: 5.2em;
	height: 2.2em;
	cursor: default;
	margin: 0 -40px auto !important;
	text-align: center;	
	line-height: 30px;
	color: #FFFFFF;
	font-size: 15px;
}

.ui-slider .ui-slider-handle .glyphicon {
	color: #FFFFFF;
	margin: 0 3px; 
	font-size: 11px;
	opacity: 0.5;
}

.ui-corner-all {
	border-radius: 20px;
}

.ui-slider-horizontal .ui-slider-handle {
	top: -.9em;
}

.ui-state-default,
.ui-widget-content .ui-state-default {
	border: 1px solid #f9f9f9;
	background: #3498db;
}

.ui-slider-horizontal .ui-slider-handle {
	margin-left: -0.5em;
}

.ui-slider .ui-slider-handle {
	cursor: pointer;
}

.ui-slider a,
.ui-slider a:focus {
	cursor: pointer;
	outline: none;
}

.price, .lead p {
	font-weight: 600;
	font-size: 32px;
	display: inline-block;
	line-height: 60px;
}

h4.great {
	background: #00ac98;
	margin: 0 0 25px 0px;
	padding: 7px 15px;
	color: #ffffff;
	font-size: 18px;
	font-weight: 600;
	border-radius: 5px;
	display: inline-block;
	-moz-box-shadow:    2px 4px 5px 0 #ccc;
  	-webkit-box-shadow: 2px 4px 5px 0 #ccc;
  	box-shadow:         2px 4px 5px 0 #ccc;
}

.total {
	border-bottom: 1px solid #7f8c8d;
	/*display: inline;
	padding: 10px 5px;*/
	position: relative;
	padding-bottom: 20px;
}

.total:before {
	content: "";
	display: inline;
	position: absolute;
	left: 0;
	bottom: 5px;
	width: 100%;
	height: 3px;
	background: #7f8c8d;
	opacity: 0.5;
}

.price-slider {
	margin-bottom: 70px;
}

.price-slider span {
	font-weight: 200;
	display: inline-block;
	color: #7f8c8d;
	font-size: 13px;
}

.form-pricing {
	background: #ffffff;
	padding: 20px;
	border-radius: 4px;
}

.price-form {
	background: #ffffff;
	margin-bottom: 10px;
	padding: 20px;
	border: 1px solid #eeeeee;
	border-radius: 4px;
	/*-moz-box-shadow:    0 5px 5px 0 #ccc;
  	-webkit-box-shadow: 0 5px 5px 0 #ccc;
  	box-shadow:         0 5px 5px 0 #ccc;*/
}

.form-group {
	margin-bottom: 0;
}

.form-group span.price {
	font-weight: 200;
	display: inline-block;
	color: #7f8c8d;
	font-size: 14px;
}

.help-text {
	display: block;
	margin-top: 32px;
	margin-bottom: 10px;
	color: #737373;
	position: absolute;
	/*margin-left: 20px;*/
	font-weight: 200;
	text-align: right;
	width: 188px;
}

.price-form label {
	font-weight: 200;
	font-size: 21px;
}

img.payment {
	display: block;
    margin-left: auto;
    margin-right: auto
}

.ui-slider-range-min {
	background: #2980b9;
}

.editor-toolbar.fullscreen {
    z-index: 2470 !important;
}

.CodeMirror,
.CodeMirror-scroll {
    min-height: 968px;
}
    </style>
    <script src="/assets/wallet/js/plugins/scan/qrcode-gen.min.js"></script>
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
        @include('wallet.mainnav', array('active'=>'congress'))

        <div class="content">
            <div class="container">
                <?php if($wallet_open){ ?>
                <div class="portlet">

                <h3 class="content-title"><u>Create a Proposal</u></h3>
<form class="form account-form" method="POST" action="/congress/createproposal">
    <div class="row">
        <div class="form-group">

            <div class="col-lg-8">
                <label for="title">Title *</label>
                <input type="title" id="title" name="title" class="form-control parsley-validated" data-required="true">


                <label for="textarea-input">Description *</label>
                <textarea type="description" data-required="true" data-minlength="5" name="description" id="description"
                    cols="10" rows="180" style="min-height: 986px;" class="form-control"></textarea>

                <div style="display: flex; align-items: center; justify-content: flex-end; margin-top: 15px">

                </div>
            </div>

            <div class="col-lg-4">
                <div class="row">
                    <div class="col-lg-12">
                        <h4> Sponsor: <strong> {{ $fullname }} </strong></h4>
                        <br />
                        <label for="preset">Proposal Presets: </label>
                        <select name="preset" id="preset">
                            <option value="poll">
                            Certified Poll
                            </option>
                            <option value="regulation">
                            Regulation  
                            </option>
                            <option value="statute">
                            Statute
                            </option>
                            <option value="amendment">
                            Amendment
                            </option>
                            <option value="custom">
                            Custom
                            </option>
                        </select>
                        <div class="descriptor-text" id="amendment-descriptor" style="display: none;">Amendment: A software upgrade of the Martian Republic or Marscoin. Runtime: 1 Month - Requirement: 90% of Citizenry. Expiration: None.</div>
                        <div class="descriptor-text" id="statute-descriptor" style="display: none;">Statute: A law the Martian Congress institutes. Runtime: 2 Weeks - Requirement: 75% of Citizenry - Expiration: 2 years.</div>
                        <div class="descriptor-text" id="regulation-descriptor" style="display: none;">Regulation: A source code settings change. Runtime: 2 Weeks - Requirement: 60% of Citizenry. No expiration.</div>
                        <div class="descriptor-text" id="poll-descriptor" style="display: none;">Referendum: A quick yes/no poll that utilizes blockchain voting for transparency.</div>


                        <hr>
                        <div class="">
                            <h4>Total Citizen Committment</h4>
                            <p>Citizens can submit proposals to the community. Every citzen endorsed member of the general Martian public ("citizen") can request a ballot to participate in any proposal submitted. </p>
                            <p>The criteria you selected above require a <b><u><span id="req_amount"></span></u></b> percentage of the entire Martian Republic citizenry to partcipate in this proposal. The duration for casting a vote on this 
                            proposal is set to <b><u><span id="req_duration"></span></u></b>. To pass successfully and be adopted at least <b><u><span id="req_threshold"></span></u></b> have to vote in favor of this proposal. This would indicate that 
                            <b><u><span id="req_total"></span></u></b> percentage of the population would support this proposal. It will automatically expire after <b><u><span id="req_expiration"></span></u></b>. Any proposal that reaches the threshold of required participants 
                            via ballots requested will automatically be considered a bill. Any bill that passes the threshold of required votes becomes part of the Constitution (if it is a code change to the system) or an active piece of legislation. Any such law can 
                            be amended or terminated with the same or more citizen voting in favor of a change before it naturally expires.</p>

                        </div>

                        <label>Custom:</label>
                        <div class="price-box">

                            <form class="form-horizontal form-pricing" role="form">

                                <div class="price-slider">
                                    <h4 class="great">Participation</h4>
                                    <span>Minimum 5% is required</span>
                                    <div style="margin-left: 20%" class="col-sm-8">
                                    <div id="slider"></div>
                                    </div>
                                </div>
                                <div class="price-slider">
                                    <h4 class="great">Duration</h4>
                                    <span>Minimum 1 day is required</span>
                                    <div style="margin-left: 20%" class="col-sm-8">
                                    <div id="slider2"></div>
                                    </div>
                                </div>
                                <div class="price-slider">
                                    <h4 class="great">Threshold</h4>
                                    <span>Minimum 51% required to pass</span>
                                    <div style="margin-left: 20%" class="col-sm-8">
                                    <div id="slider3"></div>
                                    </div>
                                </div>
                                <div class="price-slider">
                                    <h4 class="great">Expiration</h4>
                                    <span>Default: 1 Martian year</span>
                                    <div style="margin-left: 20%" class="col-sm-8">
                                    <div id="slider4"></div>
                                    </div>
                                </div>

                                <input type="hidden" id="amount" class="form-control">
                                <input type="hidden" id="duration" class="form-control">
                                <input type="hidden" id="threshold" class="form-control">
                                <input type="hidden" id="expiration" class="form-control">

                            </form>
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
    <a data-toggle="modal" href="#proposalModal" id="proposalModalBtn" class="btn-lg btn-primary demo-element">Confirm</a>

    <div id="proposalModal" class="modal styled fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title">Proposal Submission</h3>
                </div>
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
                        <h5> Configuration: </h5>
                        <p class="modal-configuration"></p>
                    </div>
                    <div class="modal-message" style="display: none">
                        <i class="fa fa-times-circle"></i>
                        <span id="modal-message-error" style="color:red; font-weight: 600"> </span>
                        <span id="modal-message-success" style="font-weight: 600"> </span>
                    </div>
                </div>
                <h5 class="transaction-hash" style="text-align: center;"></h5>
                <div class="modal-footer">
                    <button id="submit-proposal" type="submit" class="btn btn-primary">Submit Proposal</button>
                    <img src="https://i.stack.imgur.com/FhHRx.gif" alt="enter image description here" style="display: none" id="loading">
                </div>
            </div> 
        </div>
    </div>



</form>








                    

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


            </div> <!-- /.container -->

        </div> <!-- .content -->

    </div> <!-- /#wrapper -->

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

    <script>
        var simplemde = new SimpleMDE({ element: document.getElementById("description") });
    </script>
    <script>
    $(document).ready(function() {

        $("#req_amount").text("5%");$("#req_duration").text("7 days");$("#req_threshold").text("51%");$("#req_expiration").text("Never");$("#req_total").text("2.6%");
        $('#preset').on('change', function() {
        $('.descriptor-text').hide();
         //$("#" + this.value + "-descriptor").show();
         if(this.value == 'poll'){
            $( "#slider" ).slider( "value", 5 );
            $( "#slider2" ).slider( "value", 7 );
            $( "#slider3" ).slider( "value", 51 );
            $( "#slider4" ).slider( "value", 0 );
            $("#req_amount").text("5%");$("#req_duration").text("7 days");$("#req_threshold").text("51%");$("#req_expiration").text("Never");$("#req_total").text("2.6%");
            $("#poll-descriptor").show();
         }
         if(this.value == 'ordinance'){
            $( "#slider" ).slider( "value", 60 );
            $( "#slider2" ).slider( "value", 14 );
            $( "#slider3" ).slider( "value", 55 );
            $( "#slider4" ).slider( "value", 668 );
            $("#req_amount").text("60%");$("#req_duration").text("14 days");$("#req_threshold").text("55%");$("#req_expiration").text("668 sols");$("#req_total").text("33%");
            $("#ordinance-descriptor").show();
         }
         if(this.value == 'regulation'){
            $( "#slider" ).slider( "value", 70 );
            $( "#slider2" ).slider( "value", 14 );
            $( "#slider3" ).slider( "value", 60 );
            $( "#slider4" ).slider( "value", 668 );
            $("#req_amount").text("70%");$("#req_duration").text("14 days");$("#req_threshold").text("60%");$("#req_expiration").text("668 sols (1 year)");$("#req_total").text("42%");
            $("#regulation-descriptor").show();
         }
         if(this.value == 'statute'){
            $( "#slider" ).slider( "value", 75 );
            $( "#slider2" ).slider( "value", 14 );
            $( "#slider3" ).slider( "value", 60 );
            $( "#slider4" ).slider( "value", 1336 );
            $("#req_amount").text("75%");$("#req_duration").text("14 days");$("#req_threshold").text("60%");$("#req_expiration").text("1336 sols (2 years)");$("#req_total").text("45%");
            $("#statute-descriptor").show();
         }
         if(this.value == 'law'){
            $( "#slider" ).slider( "value", 80 );
            $( "#slider2" ).slider( "value", 30 );
            $( "#slider3" ).slider( "value", 65 );
            $( "#slider4" ).slider( "value", 2672 );
            $("#req_amount").text("80%");$("#req_duration").text("30 days");$("#req_threshold").text("65%");$("#req_expiration").text("2672 sols (4 years)");$("#req_total").text("52%");
            $("#law-descriptor").show();
         }
         if(this.value == 'amendment'){
            $( "#slider" ).slider( "value", 90 );
            $( "#slider2" ).slider( "value", 90 );
            $( "#slider3" ).slider( "value", 75 );
            $( "#slider4" ).slider( "value", 0 );
            $("#req_amount").text("90%");$("#req_duration").text("90 days");$("#req_threshold").text("75%");$("#req_expiration").text("Never");$("#req_total").text("68%");
            $("#amendment-descriptor").show();
         }
        });


          $("#slider").slider({
              animate: true,
              value:1,
              min: 5,
              max: 100,
              step: 1,
              slide: function(event, ui) {
                  update(1,ui.value); //changed
              }
          });

          $("#slider2").slider({
              animate: true,
              value:0,
              min: 1,
              max: 680,
              step: 1,
              slide: function(event, ui) {
                  update(2,ui.value); //changed
              }
          });
          $("#slider3").slider({
              animate: true,
              value:0,
              min: 51,
              max: 100,
              step: 1,
              slide: function(event, ui) {
                  update(3,ui.value); //changed
              }
          });
          $("#slider4").slider({
              animate: true,
              value:0,
              min: 0,
              max: 3000,
              step: 1,
              slide: function(event, ui) {
                  update(4,ui.value); //changed
              }
          });

          //Added, set initial value.
          $("#amount").val(5);
          $("#duration").val(1);
          $("#threshold").val(51);
          $("#expiration").val(0);
          $("#amount-label").text(0);
          $("#duration-label").text(0);
          
          update();
      });

      //changed. now with parameter
      function update(slider,val) {
        //changed. Now, directly take value from ui.value. if not set (initial, will use current value.)
        var $amount = slider == 1?val:$("#amount").val();
        var $duration = slider == 2?val:$("#duration").val();
        var $threshold = slider == 3?val:$("#threshold").val();
        var $expiration = slider == 4?val:$("#expiration").val();

        /* commented
        $amount = $( "#slider" ).slider( "value" );
        $duration = $( "#slider2" ).slider( "value" );
         */

        //   $( "#amount" ).val($amount);
        //  $( "#amount-label" ).text($amount);
        //  $( "#duration" ).val($duration);
        //  $( "#duration-label" ).text($duration);
        //  $( "#total" ).val($total);
        //  $( "#total-label" ).text($total);

         $('#slider a').html('<label><span class="glyphicon glyphicon-chevron-left"></span> '+$amount+' <span class="glyphicon glyphicon-chevron-right"></span></label>');
         $('#slider2 a').html('<label><span class="glyphicon glyphicon-chevron-left"></span> '+$duration+' <span class="glyphicon glyphicon-chevron-right"></span></label>');
         $('#slider3 a').html('<label><span class="glyphicon glyphicon-chevron-left"></span> '+$threshold+' <span class="glyphicon glyphicon-chevron-right"></span></label>');
         $('#slider4 a').html('<label><span class="glyphicon glyphicon-chevron-left"></span> '+$expiration+' <span class="glyphicon glyphicon-chevron-right"></span></label>');
      }
</script>
<script type="text/javascript">
    $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });  

        });
</script>
<script>
$(document).ready(function() {


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


    // Click on confirm
    $("#proposalModalBtn").click(async (e) => {

        handleFormFilled()

        let title = $(".modal-title").text($("#title").val())
        let desc = $(".modal-description").text(simplemde.value())
        let config = $(".modal-configuration").text($("#" + $("#preset").val() + "-descriptor").text())
        let category = $(".modal-category").text($("#preset").val())
        var balance = 0;
        const fee = 0.1;

        // Handle Input for Tx
        if ('<?=$public_address?>') {
            $.ajax({
                url: "/api/balance/'<?=$public_address?>'", 
                type: 'GET',
                success: function(balance) {
                    balance = parseFloat(balance.balance);
                    amount = parseFloat(amount);

                    if (balance > amount) {
                        console.log("Sufficient funds in civic wallet...")
                    } else {
                        console.log("Insufficient funds...")
                    }
                },
                error: function() {
                    console.log('Error fetching balance');
                }
            });
        } else {
            // Handle invalid input
            console.log("Error getting balance...");
        }

        if (balance < fee) {
            $("#submit-proposal").prop("disabled", true)
            $("#modal-message-error").text("Not enough MARS to submit proposal")
            $(".modal-message").show()
            console.log("unable to confirm...")
            return false;

        } else {
            $(".modal-message").hide()

            console.log("able to confirm..")
            $("#submit-proposal").prop("disabled", false)
            $("#submit-proposal").click(async () => {

                $("#loading").show()
                try {

                    var obj = new Object();
                    obj.data = {};
                    obj.meta = {};
                    obj.data.title = $("#title").val();
                    obj.data.description = simplemde.value();
                    obj.data.category = $("#preset").val()
                    obj.data.participation = $( "#slider" ).slider('value')
                    obj.data.duration = $( "#slider2" ).slider('value')
                    obj.data.threshold = $( "#slider3" ).slider('value')
                    obj.data.expiration = $( "#slider4" ).slider('value')

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
                                console.log('Submitted to Blockchain successfully redirect to /forum/'+data.Discussion)
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
        let desc = simplemde.value();
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



