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
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald:400,300,700">
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
 </style>
 <script src="/assets/wallet/js/plugins/scan/qrcode-gen.min.js"></script>
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
   @include('wallet.mainnav', array('active'=>'receive'))

  <div class="content">

    <div class="container">

      <div class="portlet">

        <h3 class="portlet-title">
          <u>Receive</u>
        </h3>

        <div class="portlet-body">
          <p>Use any of these addresses to receive Marscoin.</p>

        <div class="panel-group accordion-panel" id="accordion-paneled">


@foreach ($addresses as $address)
    
    <div class="panel panel-default">

            <div class="panel-heading">
              <h4 class="panel-title">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-paneled" href="#collapse{{$address}}">
             {{$address}} 
              </a>
              </h4>
            </div> <!-- /.panel-heading -->

            <div id="collapse{{$address}}" class="panel-collapse collapse in">
              <div class="panel-body">
                 <div class="col-md-3">
                      <div id="qrcode_{{$address}}"></div>
                        <script type="text/javascript">
                        var qrcode = new QRCode(document.getElementById("qrcode_{{$address}}"), {
                          text: "{{$address}}",
                          width: 128,
                          height: 128,
                          colorDark : "#000000",
                          colorLight : "#ffffff",
                          correctLevel : QRCode.CorrectLevel.H
                        });
                        </script>
                            
                    </div>
                    <div class="col-md-9">
                      <h4>{{$address}} </h4> 
                      <textarea class="form-control addrnotes" rel="note_{{$address}}" rows="2" placeholder="Notes"></textarea>
                      <p class="note_{{$address}}_span"></p>
                    </div>
              </div> <!-- /.panel-body -->
            </div> <!-- /.panel-collapse -->

          </div> <!-- /.panel -->

@endforeach

          
        </div> <!-- /.accordion -->

        <a  href="#" id="new_address" class="btn btn-primary demo-element pull-right">Add new address</a>
        </div> <!-- /.portlet-body -->

      </div> <!-- /.portlet -->

    </div> <!-- /.container -->

  </div> <!-- .content -->

</div> <!-- /#wrapper -->

<footer class="footer">
  <div class="container">
    <p class="pull-left">Copyright &copy; 2014-<?=date('Y')?> The Marscoin Foundation, Inc.</p>
    <p class="pull-right">Marscoin Wallet v.1.5.120</p>
  </div>
</footer>
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Core JS -->
<script src="/assets/wallet/js/libs/jquery-1.10.2.min.js"></script>
<script src="/assets/wallet/js/libs/bootstrap.min.js"></script>
<!--[if lt IE 9]>
<script src="/assets/wallet/js/libs/excanvas.compiled.js"></script>
<![endif]-->
<!-- Plugin JS -->

<script src="/assets/wallet/js/plugins/dataTables/jquery.dataTables.js"></script>
<script src="/assets/wallet/js/plugins/dataTables/dataTables.bootstrap.js"></script>

<!-- App JS -->
<script src="/assets/wallet/js/mvpready-core.js"></script>
<script src="/assets/wallet/js/mvpready-admin.js"></script>

<!-- Plugin JS -->
<script src="/assets/wallet/js/demos/table_demo.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
  $("#new_address").click(function(event){
    event.preventDefault();
      $.post("/api/newAddress/{{ Auth::user()->email }}",function(data){
          location.reload();
    });
  });

  $( ".addrnotes" ).blur(function() 
  {
    var text = $(this).val();
    var index = $(this).attr("rel");
    // $.post("/api/newnote/{{ Auth::user()->email }}", { address: index , note: text },function(data){
    //      $("#"+index+"_span").text("Saved...")
    //   });
    
  });

});

</script>
</body>
</html>