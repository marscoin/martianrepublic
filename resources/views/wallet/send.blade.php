<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head>
  <title>Marscoin Wallet</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <!-- Google Font: Open Sans -->
  <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
  <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Oswald:400,300,700">
  <!-- Font Awesome CSS -->
  <link rel="stylesheet" href="/assets/wallet/css/font-awesome.min.css">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="/assets/wallet/css/bootstrap.min.css">
  <!-- App CSS -->
  <link rel="stylesheet" href="/assets/wallet/css/mvpready-admin.css">
  <link rel="stylesheet" href="/assets/wallet/css/mvpready-flat.css">
  <!-- <link href="/assets/wallet/css/custom.css" rel="stylesheet">-->
  <!-- Favicon -->
  <link rel="shortcut icon" href="/favicon.ico">
  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
  <![endif]-->
<script src="/assets/wallet/js/plugins/scan/qrcode.min.js"></script>

</head>
<body class=" ">
<div id="wrapper">
  <header class="navbar navbar-inverse" role="banner">
    <div class="container">
      <div class="navbar-header">
        <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only">Toggle navigation</span>
          <i class="fa fa-cog"></i>
        </button>
        <a href="/wallet/dashboard" class="navbar-brand navbar-brand-img">
          <img style="width: 67px;" src="/assets/landing/img/logomarscoinwallet.png" alt="MVP Ready">
        Marscoin Wallet
        </a>
      </div> <!-- /.navbar-header -->
      <nav class="collapse navbar-collapse" role="navigation">
         @include('wallet.navbarleft')
         @include('wallet.navbarright')
      </nav>
    </div> <!-- /.container -->
  </header>
   @include('wallet.mainnav', array('active'=>'send'))
  <div class="content">
    <div class="container">
      <div class="layout layout-main-right layout-stack-sm">
        <div class="col-md-3 col-sm-4 layout-sidebar">
          <div class="nav-layout-sidebar-skip">
            <strong>Tab Navigation</strong> / <a href="#settings-content">Skip to Content</a>	
          </div>
          <ul id="myTab" class="nav nav-layout-sidebar nav-stacked">
              <li class="active">
              <a href="#profile-tab" data-toggle="tab">
              <i class="fa   fa-bolt "></i> 
              &nbsp;&nbsp;Quick Send
              </a>
            </li>
            <li>
              <a href="#password-tab" data-toggle="tab disabled">
              <i class="fa fa-send"></i> 
              &nbsp;&nbsp;Custom
              </a>
            </li>
            <li>
              <a href="#messaging" data-toggle="tab disabled">
              <i class="fa  fa-envelope-o"></i> 
              &nbsp;&nbsp;Send via Email
              </a>
            </li>
            <li>
              <a href="#payments" data-toggle="tab disabled">
              <i class="fa fa-mobile-phone"></i> 
              &nbsp;&nbsp;Send via SMS
              </a>
            </li>
            <li>
              <a href="#reports" data-toggle="tab disabled">
              <i class="fa  fa-book"></i> 
              &nbsp;&nbsp;Address Book
              </a>
            </li>
          </ul>
        </div> <!-- /.col -->
        <div class="col-md-9 col-sm-8 layout-main">
          <div id="settings-content" class="tab-content stacked-content">
            <div class="tab-pane fade in active" id="profile-tab">
              <h3 class="content-title"><u>Quick Send</u></h3>
              <p>Use the form below to send payment to a Marscoin address.</p>
              <p></p>
              <?php if($latest && count($latest) > 0){ ?>
              <p>Current MARS/USD rate: $<?=round($latest['data'][154]['quote']['USD']['price'], 4)?> (<i>Source:</i> coinmarketcap) for 1 MARS</p>
              <?php } ?>

              <br><br>
              <form action="#" class="form-horizontal">
                <div class="form-group">
                  <label class="col-md-2">To:</label>
                  <div class="col-md-6">
                    <input type="text" id="quick-send-mrs-sendto-address" name="mrs-sendto-address" class="form-control"  placeholder="Marscoin address ex.: MUGip2ahPkdafGXVpYbdBPRbbwCzrKztGC" />
                  </div> <!-- /.col -->
                  <div class="col-md-2">
                    <a href="#" onclick="scan();" class="btn btn-primary">Scan <i class="fa fa-qrcode "></i></a>
                  </div>
                </div> <!-- /.form-group -->
                <div class="form-group">
                  <label class="col-md-2">Amount:</label>
                  <div class="col-md-4">
                  	<div class="input-group">
                   <span class="input-group-addon">MARS</span>
                    	<input type="text" id="quick-send-mrs-amount" name="mrs-amount"  placeholder="0.0" autocomplete="off" class="form-control" />
                    	</div>
                  </div> <!-- /.col -->
                  <label class="col-md-1">=</label>
                  <div class="col-md-3">
                  	<div class="input-group">
                  	<span class="input-group-addon">$</span>
                    <input type="text" id="quick-send-usd-amount" name="usd-amount"  placeholder="0.0" autocomplete="off" class="form-control" />
                </div>
                  </div> <!-- /.col -->
                </div> <!-- /.form-group -->
                <div class="form-group">
                  <div class="col-md-8 col-md-push-2">
                    <a href="javascript:void(0);" id="quick-send" class="btn btn-success">Send Payment</a>
                  </div> <!-- /.col -->
                </div> <!-- /.form-group -->
              </form>
              <div class="alert alert-success" id="quick-send-response" style="display: none;">
                <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
                Your payment was successfully sent. The transaction id is:<br>
                <strong><span id="quick-send-response-tx-id"></span></strong> 
                <div id="quick-send-response-tx-link"></div>
              </div>
            </div> <!-- /.tab-pane -->
            <div class="tab-pane fade" id="password-tab">
              <h3 class="content-title"><u>Change Password</u></h3>
              <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes.</p>
              <br><br>
              <form action="./page-settings.html" class="form-horizontal">
                <div class="form-group">
                  <label class="col-md-3">Old Password</label>
                  <div class="col-md-7">
                    <input type="password" name="old-password" class="form-control" />
                  </div> <!-- /.col -->
                </div> <!-- /.form-group -->
                <hr>
                <div class="form-group">
                  <label class="col-md-3">New Password</label>
                  <div class="col-md-7">
                    <input type="password" name="new-password-1" class="form-control" />
                  </div> <!-- /.col -->
                </div> <!-- /.form-group -->
                <div class="form-group">
                  <label class="col-md-3">New Password Confirm</label>
                  <div class="col-md-7">
                    <input type="password" name="new-password-2" class="form-control" />
                  </div> <!-- /.col -->
                </div> <!-- /.form-group -->
                <div class="form-group">
                  <div class="col-md-7 col-md-push-3">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    &nbsp;
                    <button type="reset" class="btn btn-default">Cancel</button>
                  </div> <!-- /.col -->
                </div> <!-- /.form-group -->
              </form>
            </div> <!-- /.tab-pane -->
            <div class="tab-pane fade" id="messaging">
              <h3 class="content-title"><u>Notification Settings</u></h3>
              <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes.</p>
              <br><br>
              <form action="./page-settings.html" class="form form-horizontal">
                <div class="form-group">
                  <label class="col-md-3">Toggle Notifications</label>
                  <div class="col-md-7">
                    <div class="checkbox">
                      <label>
                      <input value="" type="checkbox" checked>
                      Send me security emails
                      </label>
                    </div> <!-- /.checkbox -->
                    <div class="checkbox">
                      <label>
                      <input value="" type="checkbox" checked>
                      Send system emails
                      </label>
                    </div> <!-- /.checkbox -->
                    <div class="checkbox">
                      <label>
                      <input value="" type="checkbox">
                      Lorem ipsum dolor sit amet
                      </label>
                    </div> <!-- /.checkbox -->
                    <div class="checkbox">
                      <label>
                      <input value="" type="checkbox">
                      Laborum, quam iure quibusdam
                      </label>
                    </div> <!-- /.checkbox -->
                  </div> <!-- /.col -->
                </div> <!-- /.form-group -->
                <div class="form-group">
                  <label class="col-md-3">Email for Notifications</label>
                  <div class="col-md-7">
                    <select name="email_notifications" class="form-control">
                      <option value="1">john@mvpready.com</option>
                      <option value="2">mary@mvpready.com</option>
                      <option value="3">chris@mvpready.com</option>
                    </select>
                  </div> <!-- /.col -->
                </div> <!-- /.form-group -->
                <div class="form-group">
                  <div class="col-md-7 col-md-push-3">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    &nbsp;
                    <button type="reset" class="btn btn-default">Cancel</button>
                  </div> <!-- /.col -->
                </div> <!-- /.form-group -->
              </form>
            </div> <!-- /.tab-pane -->
            <div class="tab-pane fade" id="payments">
              <h3 class="content-title"><u>Payments Settings</u></h3>
              <p>Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone skateboard locavore carles etsy salvia banksy hoodie helvetica. DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork. Williamsburg banh mi whatever gluten-free, carles pitchfork biodiesel fixie etsy retro mlkshk vice blog. Scenester cred you probably haven't heard of them, vinyl craft beer blog stumptown. Pitchfork sustainable tofu synth chambray yr.</p>
              <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium.</p>
            </div> <!-- /.tab-pane -->
            <div class="tab-pane fade" id="reports">
              <h3 class="content-title"><u>Reports Settings</u></h3>
              <p>Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone skateboard locavore carles etsy salvia banksy hoodie helvetica. DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork. Williamsburg banh mi whatever gluten-free, carles pitchfork biodiesel fixie etsy retro mlkshk vice blog. Scenester cred you probably haven't heard of them, vinyl craft beer blog stumptown. Pitchfork sustainable tofu synth chambray yr.</p>
              <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium.</p>
            </div> <!-- /.tab-pane -->
          </div> <!-- /.tab-content -->
        </div> <!-- /.col -->
      </div> <!-- /.row -->
    </div> <!-- /.container -->
  </div> <!-- .content -->
</div> <!-- /#wrapper -->
<footer class="footer">
  <div class="container">
    <p class="pull-left">Copyright &copy; 2014-<?=date('Y')?> The Marscoin Foundation, Inc.</p>
  </div>

<div class="modal scan-popup" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
           <div id="app">
            <div class="sidebar">
            </div>
           <canvas hidden="" id="qr-canvas"></canvas>

          </div>
    </div>
  </div>
</div>
</footer>
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Core JS -->
<script src="/assets/wallet/js/libs/jquery-1.10.2.min.js"></script>
<script src="/assets/wallet/js/libs/bootstrap.min.js"></script>


<script>
$( "#quick-send" ).click(function() {

  $.post( "/api/sendFrom/{{ Auth::user()->email }}", { to_address: $("#quick-send-mrs-sendto-address").val() , amount: $("#quick-send-mrs-amount").val() },  function( data ) {
    data = data.replace(/(\r\n|\n|\r)/gm, "");
    data = data.replace('"', "");
    $( "#quick-send-response-tx-id" ).html(data);
    $( "#quick-send-response-tx-link" ).html('<a style="color: white;" target="_blank" href="http://explore.marscoin.org/tx/'+data+'">(See on Blockchain)</a>' );
    $( "#quick-send-response" ).show();
  });

});

<?php if($latest && count($latest) > 0){ ?>
$( "#quick-send-mrs-amount" ).keyup(function() {
  if($( "#quick-send-mrs-amount" ).val() ){
    $("#quick-send-usd-amount").val(parseFloat($( "#quick-send-mrs-amount" ).val() * <?=round($latest['data'][154]['quote']['USD']['price'], 4)?>).toFixed(2));
  }
});
<?php }else{?>
$( "#quick-send-mrs-amount" ).keyup(function() {
  if($( "#quick-send-mrs-amount" ).val() ){
    $("#quick-send-usd-amount").val(parseFloat($( "#quick-send-mrs-amount" ).val() * 0.1 ).toFixed(2));
  }
});
<?php }?>

function scan(){
  $('.scan-popup').modal('show');
}



     

const video = document.createElement("video");
const canvasElement = document.getElementById("qr-canvas");
const canvas = canvasElement.getContext("2d");
const outputData = document.getElementById("outputData");
const btnScanQR = document.getElementById("btn-scan-qr");

let scanning = false;

$('.scan-popup').on('shown.bs.modal', function (e) {
  
  console.log("Here");
  navigator.mediaDevices
    .getUserMedia({ video: { facingMode: "environment" } })
    .then(function(stream) {
      scanning = true;
      canvasElement.hidden = false;
      video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
      video.srcObject = stream;
      video.play();
      tick();
      scan2();
    });

});




qrcode.callback = res => {
  if (res) {
    $("#quick-send-mrs-sendto-address").val(res);
    scanning = false;
    $('.scan-popup').modal('hide');

    video.srcObject.getTracks().forEach(track => {
      track.stop();
    });

 
    canvasElement.hidden = true;
    btnScanQR.hidden = false;
    $('.scan-popup').modal('hide');

  }
};


function tick() {
  canvasElement.height = video.videoHeight;
  canvasElement.width = video.videoWidth;
  canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);

  scanning && requestAnimationFrame(tick);
}

function scan2() {
  try {
    qrcode.decode();
  } catch (e) {
    setTimeout(scan2, 300);
  }
}

    </script>

</body>
</html>