
<html>
  <head>
    <link rel="icon" type="image/png" href="favicon.png">
    <script src="https://rawgit.com/sitepoint-editors/jsqrcode/master/src/qr_packed.js"></script>
    <style type="text/css">
      html {
  height: 100%;
}

body {
  font-family: sans-serif;
  padding: 0 10px;
  height: 100%;
  background: black;
  margin: 0;
}

h1 {
  color: white;
  margin: 0;
  padding: 15px;
}

#container {
  text-align: center;
  margin: 0;
}

#qr-canvas {
  margin: auto;
  width: calc(100% - 20px);
  max-width: 400px;
}

#btn-scan-qr {
  cursor: pointer;
}

#btn-scan-qr img {
  height: 10em;
  padding: 15px;
  margin: 15px;
  background: white;
}

#qr-result {
  font-size: 1.2em;
  margin: 20px auto;
  padding: 20px;
  max-width: 700px;
  background-color: white;
}

    </style>
  </head>

  <body>
    <div id="container">
      <h1>QR Code Scanner</h1>

      <a id="btn-scan-qr">
        <img src="https://dab1nmslvvntp.cloudfront.net/wp-content/uploads/2017/07/1499401426qr_icon.svg">
      <a/>

      <canvas hidden="" id="qr-canvas"></canvas>

      <div id="qr-result" hidden="">
        <b>Data:</b> <span id="outputData"></span>
      </div>
    </div>




<script type="text/javascript" >
// const qrcode = window.qrcode;

const video = document.createElement("video");
const canvasElement = document.getElementById("qr-canvas");
const canvas = canvasElement.getContext("2d");

const qrResult = document.getElementById("qr-result");
const outputData = document.getElementById("outputData");
const btnScanQR = document.getElementById("btn-scan-qr");

let scanning = false;

qrcode.callback = res => {
  if (res) {
    outputData.innerText = res;
    scanning = false;

    video.srcObject.getTracks().forEach(track => {
      track.stop();
    });

    qrResult.hidden = false;
    canvasElement.hidden = true;
    btnScanQR.hidden = false;
  }
};

btnScanQR.onclick = () => {
  navigator.mediaDevices
    .getUserMedia({ video: { facingMode: "environment" } })
    .then(function(stream) {
      scanning = true;
      qrResult.hidden = true;
      btnScanQR.hidden = true;
      canvasElement.hidden = false;
      video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
      video.srcObject = stream;
      video.play();
      tick();
      scan();
    });
};

function tick() {
  canvasElement.height = video.videoHeight;
  canvasElement.width = video.videoWidth;
  canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);

  scanning && requestAnimationFrame(tick);
}

function scan() {
  try {
    qrcode.decode();
  } catch (e) {
    setTimeout(scan, 300);
  }
}

    </script>
  </body>
</html>
