<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head>
  <title>Martian Republic</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,800,800italic">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald:400,300,700">
  <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@700&family=Orbitron:wght@500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/landing/css/font-awesome.min.css">
  <link rel="stylesheet" href="/assets/landing/css/bootstrap.min.css">
  <link href="/assets/landing/css/mvpready-landing.css" rel="stylesheet">
  <link href="/assets/landing/css/mvpready-flat.css" rel="stylesheet">
  <link href="/assets/landing/css/animate.css" rel="stylesheet">
  <link rel="shortcut icon" href="/assets/favicon.ico">
  <style>
    .mini-feature-title{
      font-size: 25px;
    }
    .shadow-lg {
        box-shadow: 0 1rem 3rem rgb(0 0 0 / 18%) !important;
    }
    .lead-small{
      font-size: 19px;
    }
    .divider-wider{
      margin-bottom: 110px;
      margin-top: 50px;
    }

@import url('https://fonts.googleapis.com/css?family=Dosis:300,400,500');

@-moz-keyframes rocket-movement { 100% {-moz-transform: translate(1200px,-600px);} }
@-webkit-keyframes rocket-movement {100% {-webkit-transform: translate(1200px,-600px); } }
@keyframes rocket-movement { 100% {transform: translate(1200px,-600px);} }
@-moz-keyframes spin-earth { 100% { -moz-transform: rotate(-360deg); transition: transform 20s;  } }
@-webkit-keyframes spin-earth { 100% { -webkit-transform: rotate(-360deg); transition: transform 20s;  } }
@keyframes spin-earth{ 100% { -webkit-transform: rotate(-360deg); transform:rotate(-360deg); transition: transform 20s; } }

@-moz-keyframes move-astronaut {
    100% { -moz-transform: translate(-160px, -160px);}
}
@-webkit-keyframes move-astronaut {
    100% { -webkit-transform: translate(-160px, -160px);}
}
@keyframes move-astronaut{
    100% { -webkit-transform: translate(-160px, -160px); transform:translate(-160px, -160px); }
}
@-moz-keyframes rotate-astronaut {
    100% { -moz-transform: rotate(-720deg);}
}
@-webkit-keyframes rotate-astronaut {
    100% { -webkit-transform: rotate(-720deg);}
}
@keyframes rotate-astronaut{
    100% { -webkit-transform: rotate(-720deg); transform:rotate(-720deg); }
}

@-moz-keyframes glow-star {
    40% { -moz-opacity: 0.3;}
    90%,100% { -moz-opacity: 1; -moz-transform: scale(1.2);}
}
@-webkit-keyframes glow-star {
    40% { -webkit-opacity: 0.3;}
    90%,100% { -webkit-opacity: 1; -webkit-transform: scale(1.2);}
}
@keyframes glow-star{
    40% { -webkit-opacity: 0.3; opacity: 0.3;  }
    90%,100% { -webkit-opacity: 1; opacity: 1; -webkit-transform: scale(1.2); transform: scale(1.2); border-radius: 999999px;}
}

.spin-earth-on-hover{
    
    transition: ease 200s !important;
    transform: rotate(-3600deg) !important;
}

html, body{
    margin: 0;
    width: 100%;
    height: 100%;
    font-family: 'Dosis', sans-serif;
    font-weight: 300;
    -webkit-user-select: none; /* Safari 3.1+ */
    -moz-user-select: none; /* Firefox 2+ */
    -ms-user-select: none; /* IE 10+ */
    user-select: none; /* Standard syntax */
}

.bg-purple{
    background: url(/assets/landing/img/space.png);
    background-repeat: repeat-x;
    background-size: cover;
    background-position: left top;
    height: 100%;
    overflow: hidden;
    
}

.custom-navbar{
    padding-top: 15px;
}

.brand-logo{
    margin-left: 25px;
    margin-top: 5px;
    display: inline-block;
}

.navbar-links{
    display: inline-block;
    float: right;
    margin-right: 15px;
    text-transform: uppercase;
    
    
}

ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
/*    overflow: hidden;*/
    display: flex; 
    align-items: center; 
}

li {
    float: left;
    padding: 0px 15px;
}

li a {
    display: block;
    color: white;
    text-align: center;
    text-decoration: none;
    letter-spacing : 2px;
    font-size: 12px;
    
    -webkit-transition: all 0.3s ease-in;
    -moz-transition: all 0.3s ease-in;
    -ms-transition: all 0.3s ease-in;
    -o-transition: all 0.3s ease-in;
    transition: all 0.3s ease-in;
}

li a:hover {
    color: #ffcb39;
}

.btn-request{
    padding: 10px 25px;
    border: 1px solid #FFCB39;
    border-radius: 100px;
    font-weight: 400;
}

.btn-request:hover{
    background-color: #FFCB39;
    color: #fff;
    transform: scale(1.05);
    box-shadow: 0px 20px 20px rgba(0,0,0,0.1);
}

.btn-go-home{
    position: relative;
    z-index: 200;
    margin: 15px auto;
    width: 100px;
    padding: 10px 15px;
    border: 1px solid #FFCB39;
    border-radius: 100px;
    font-weight: 400;
    display: block;
    color: white;
    text-align: center;
    text-decoration: none;
    letter-spacing : 2px;
    font-size: 11px;
    
    -webkit-transition: all 0.3s ease-in;
    -moz-transition: all 0.3s ease-in;
    -ms-transition: all 0.3s ease-in;
    -o-transition: all 0.3s ease-in;
    transition: all 0.3s ease-in;
}

.btn-go-home:hover{
    background-color: #FFCB39;
    color: #fff;
    transform: scale(1.05);
    box-shadow: 0px 20px 20px rgba(0,0,0,0.1);
}

.central-body{
/*    width: 100%;*/
    padding: 17% 5% 10% 5%;
    text-align: center;
}

.objects img{
    z-index: 90;
    pointer-events: none;
}

.object_rocket{
    z-index: 95;
    position: absolute;
    transform: translateX(-50px);
    top: 75%;
    pointer-events: none;
    animation: rocket-movement 200s linear infinite both running;
}

.object_earth{
    position: absolute;
    top: 20%;
    left: 15%;
    z-index: 90;
/*    animation: spin-earth 100s infinite linear both;*/
}

.object_moon{
    position: absolute;
    top: 12%;
    left: 25%;
/*
    transform: rotate(0deg);
    transition: transform ease-in 99999999999s;
*/
}

.earth-moon{
    
}

.object_astronaut{
    animation: rotate-astronaut 200s infinite linear both alternate;
}

.box_astronaut{
    z-index: 110 !important;
    position: absolute;
    top: 60%;
    right: 20%;
    will-change: transform;
    animation: move-astronaut 50s infinite linear both alternate;
}

.image-404{
    position: relative;
    z-index: 100;
    pointer-events: none;
}

.stars{
    background: url(http://salehriaz.com/404Page/img/overlay_stars.svg);
    background-repeat: repeat;
    background-size: contain;
    background-position: left top;
}

.glowing_stars .star{
    position: absolute;
    border-radius: 100%;
    background-color: #fff;
    width: 3px;
    height: 3px;
    opacity: 0.3;
    will-change: opacity;
}

.glowing_stars .star:nth-child(1){
    top: 80%;
    left: 25%;
    animation: glow-star 2s infinite ease-in-out alternate 1s;
}
.glowing_stars .star:nth-child(2){
    top: 20%;
    left: 40%;
    animation: glow-star 2s infinite ease-in-out alternate 3s;
}
.glowing_stars .star:nth-child(3){
    top: 25%;
    left: 25%;
    animation: glow-star 2s infinite ease-in-out alternate 5s;
}
.glowing_stars .star:nth-child(4){
    top: 75%;
    left: 80%;
    animation: glow-star 2s infinite ease-in-out alternate 7s;
}
.glowing_stars .star:nth-child(5){
    top: 90%;
    left: 50%;
    animation: glow-star 2s infinite ease-in-out alternate 9s;
}

@media only screen and (max-width: 600px){
    .navbar-links{
        display: none;
    }
    
    .custom-navbar{
        text-align: center;
    }
    
    .brand-logo img{
        width: 120px;
    }
    
    .box_astronaut{
        top: 70%;
    }
    
    .central-body{
        padding-top: 25%;
    }
}
  </style>
</head>
<body class="bg-purple">
<div id="wrapper">
  <header class="navbar navbar-inverse" role="banner">

    <div class="container">

      <div class="navbar-header">
        <a href="/" class="navbar-brand navbar-brand-img"  style="font-family: 'Orbitron', sans-serif;">
          <img style="width: 67px;" src="/assets/landing/img/logomarscoinwallet.png" alt="MVP Ready">
        Martian Republic
        </a>

        <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only">Toggle navigation</span>
          <i class="fa fa-bars"></i>
        </button>
      </div> <!-- /.navbar-header -->


      <nav class="collapse navbar-collapse" role="navigation">

        <ul class="nav navbar-nav navbar-right mainnav-menu">

          <li class="">
            <a target="_blank" href="https://marscoin.gitbook.io/marscoin-documentation/">Documentation</a>
          </li>

          <li>
            <a href="/signup">Signup</a>
          </li>

          <li>
            <a href="/login">Login</a>
          </li>

        </ul>

        <ul class="nav navbar-nav navbar-social navbar-left">

          <li>
            <a target="_blank" href="http://facebook.com/marscoin" class="ui-tooltip" title="Facebook" data-placement="bottom">
              <i class="fa fa-facebook"></i>
              <span class="navbar-social-label">Facebook</span>
            </a>
          </li>

          <li>
            <a target="_blank" href="http://twitter.com/marscoinorg" class="ui-tooltip" title="Twitter" data-placement="bottom">
              <i class="fa fa-twitter"></i>
              <span class="navbar-social-label">Twitter</span>
            </a>
          </li>

          <li>
            <a target="_blank" href="https://discord.gg/6vVKH6QdYb" class="ui-tooltip" title="Discord" data-placement="bottom">
              <i class="fa fa-github-alt"></i>
              <span class="navbar-social-label">Discord</span>
            </a>
          </li>

          <li>
            <a target="_blank" href="https://reddit.com/r/marscoin" class="ui-tooltip" title="Reddit" data-placement="bottom">
              <i class="fa fa-reddit"></i>
              <span class="navbar-social-label">Reddit</span>
            </a>
          </li>

        </ul>
      </nav>
    </div> 
  </header>
        <div class="stars">
            <div class="central-body">
                <img class="image-404" src="/assets/landing/img/404.svg" width="300px">
                <a href="/" class="btn-go-home" target="_blank">GO BACK HOME</a>
            </div>
            <div class="objects">
                <img class="object_rocket" src="/assets/landing/img/rocket.svg" width="40px">
                <div class="box_astronaut">
                    <img class="object_astronaut" src="/assets/landing/img/astronaut.svg" width="140px">
                </div>
            </div>
            <div class="glowing_stars">
                <div class="star"></div>
                <div class="star"></div>
                <div class="star"></div>
                <div class="star"></div>
                <div class="star"></div>

            </div>

        </div>

    </body>