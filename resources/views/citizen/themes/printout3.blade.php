<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <title>Mars Basecamp Registration Printout</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@700&family=Orbitron:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald:400,300,700"> 
    <link href="https://fonts.googleapis.com/css2?family=Aldrich&family=Courier+Prime:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/wallet/css/font-awesome.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    <link rel="shortcut icon" href="/assets/favicon.ico">
<style>

body {
  width: 230mm;
  height: 100%;
  margin: 0 auto;
  padding: 0;
  font-size: 12pt;
  background: rgb(204,204,204); 
  color: white;
}
* {
  box-sizing: border-box;
  -moz-box-sizing: border-box;
}
.main-page {
  width: 210mm;
  min-height: 297mm;
  margin: 10mm auto;
  background: white;
  box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
}
.sub-page {
  padding: 1cm;
  height: 297mm;
}
@page {
  size: A4;
  margin: 0;
}
@media print {
  html, body {
    width: 210mm;
    height: 297mm;        
  }
  .main-page {
    margin: 0;
    border: initial;
    border-radius: initial;
    width: initial;
    min-height: initial;
    box-shadow: initial;
    background: initial;
    page-break-after: always;
  }
}



</style>
  </head>
  <body>
    <div class="main-page" >
      <div class="sub-page" >
        <h3 align='center'></h3>


<div style="background-image: url(/assets/citizen/Mars_Passport_7.png); background-size: 190mm; padding:50px;height: 333px; ">
        <div  style="float:left; ">
            <span style="font-family: 'Aldrich', sans-serif;font-size:70px; font-weight: 900; margin-top: 20px;"><?=substr($public_address, 0, 9)?></span>
            <div style="border-color: red; border-style: solid; padding: 10px; border-radius: 5px;">
                <strong style="font-family: 'Oswald', sans-serif;">Martian Congressional Republic<br>Public Identifier</strong><br>
                <span style="font-size: 30px !important; font-family: 'Oswald', sans-serif; font-weight: 900;"><?=$fullname?></span><br> 
                <span style="font-family: 'Aldrich', sans-serif;font-size: 18px;"><?=$public_address?></span><br>
                <span style="font-family: 'Aldrich', sans-serif;font-size: 12px;"><script>

                var today = new Date();
                var ss = today.getSeconds();
                var MM = today.getMinutes();
                var HH = today.getHours();
                var dd = String(today.getDate()).padStart(2, '0');
                var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                var yyyy = today.getFullYear();

                today =  yyyy + "-" + mm + '-' + dd + ' ' + HH + ":" + MM + ":" + ss;
                document.write(today);

                </script></span>			
            </div>
        </div>
            <img id="qrious" height="100" width="100" style="   margin-left: 82px; margin-top: 66px;">
        </div>    
        </div>
    </div>

<script type="text/javascript">
var qr = new QRious({
          element: document.getElementById('qrious'),
          value: '<?=$public_address?>',
          size: '100'
        });
</script>
     </body>
</html>
