<!DOCTYPE html>
<html lang="en">
<head>
  <title>Martian Republic - Server Status</title>
  @include('partials.public-head')
</head>
<body class="mr-theme">

  @include('partials.public-nav')

  <section class="mr-page-header">
    <div class="container">
      <h1>System Status</h1>
      <p>Real-time health monitoring of all Martian Republic subsystems</p>
    </div>
  </section>

  <section class="mr-content">
    <div class="container">

      <div class="mr-status-grid">

        <!-- Web Server -->
        <div class="mr-status-card">
          <h3>Web Server</h3>
          <p class="mr-status-desc">Base Reference Implementation</p>
          <?php if($web_status == "success"){ ?>
          <span class="mr-status-badge online"><span class="dot"></span> Online</span>
          <?php }else { ?>
          <span class="mr-status-badge offline"><span class="dot"></span> Offline</span>
          <?php } ?>
        </div>

        <!-- Database Server -->
        <div class="mr-status-card">
          <h3>Database Server</h3>
          <p class="mr-status-desc">Local Database Cache</p>
          <?php if($mysql_status == "success"){ ?>
          <span class="mr-status-badge online"><span class="dot"></span> Online</span>
          <?php }else { ?>
          <span class="mr-status-badge offline"><span class="dot"></span> Offline</span>
          <?php } ?>
        </div>

        <!-- Marscoin Node -->
        <div class="mr-status-card">
          <h3>Marscoin Node</h3>
          <p class="mr-status-desc">Local Marscoin Node</p>
          <?php if($marscoind_status == "success"){ ?>
          <span class="mr-status-badge online"><span class="dot"></span> Online</span>
          <?php }else { ?>
          <span class="mr-status-badge offline"><span class="dot"></span> Offline</span>
          <?php } ?>
        </div>

        <!-- Block Explorer -->
        <div class="mr-status-card">
          <h3>Block Explorer</h3>
          <p class="mr-status-desc">Blockchain Explorer</p>
          <?php if($blockexplorer == "success"){ ?>
          <span class="mr-status-badge online"><span class="dot"></span> Online</span>
          <?php }else { ?>
          <span class="mr-status-badge offline"><span class="dot"></span> Offline</span>
          <?php } ?>
        </div>

        <!-- Pebas Node -->
        <div class="mr-status-card">
          <h3>Pebas Node</h3>
          <p class="mr-status-desc">Blockexplorer API Bridge</p>
          <?php if($pebas_status == "success"){ ?>
          <span class="mr-status-badge online"><span class="dot"></span> Online</span>
          <?php }else { ?>
          <span class="mr-status-badge offline"><span class="dot"></span> Offline</span>
          <?php } ?>
        </div>

        <!-- IPFS Node -->
        <div class="mr-status-card">
          <h3>IPFS Node</h3>
          <p class="mr-status-desc">IPFS Pinning Service</p>
          <?php if($ipfs_status == "success"){ ?>
          <span class="mr-status-badge online"><span class="dot"></span> Online</span>
          <?php }else { ?>
          <span class="mr-status-badge offline"><span class="dot"></span> Offline</span>
          <?php } ?>
        </div>

        <!-- BlockAnalyzer -->
        <div class="mr-status-card">
          <h3>BlockAnalyzer</h3>
          <p class="mr-status-desc">Tracks Blockchain Notarizations</p>
          <?php if($blockchain_tracker_status == "success"){ ?>
          <span class="mr-status-badge online"><span class="dot"></span> Online</span>
          <?php }else { ?>
          <span class="mr-status-badge offline"><span class="dot"></span> Offline</span>
          <?php } ?>
        </div>

        <!-- Ballot Server -->
        <div class="mr-status-card">
          <h3>Ballot Server</h3>
          <p class="mr-status-desc">Orchestrates Ballot Shuffles</p>
          <?php if($ballot_server_status == "success"){ ?>
          <span class="mr-status-badge online"><span class="dot"></span> Online</span>
          <?php }else { ?>
          <span class="mr-status-badge offline"><span class="dot"></span> Offline</span>
          <?php } ?>
        </div>

        <!-- Mobile Apps -->
        <div class="mr-status-card">
          <h3>Mobile Apps</h3>
          <p class="mr-status-desc">Companion app for all Martians</p>
          <a href="https://apps.apple.com/us/app/martianrepublic/id6480416861" class="mr-status-badge online"><span class="dot"></span> iOS App Store</a>
          <span class="mr-status-badge offline"><span class="dot"></span> Android Coming Soon</span>
        </div>

      </div>

      <!-- Marscoin Network Info -->
      <?php if($network && count($network) > 0){ ?>
      <div class="mr-network-info">
        <h3>Marscoin Network</h3>
        <div class="mr-network-row">
          <span class="mr-network-label">Block Height</span>
          <span class="mr-network-value">#<?=$network['blocks']?></span>
        </div>
        <div class="mr-network-row">
          <span class="mr-network-label">Connections</span>
          <span class="mr-network-value"><?=$network['connections']?></span>
        </div>
        <div class="mr-network-row">
          <span class="mr-network-label">Difficulty</span>
          <span class="mr-network-value"><?=$network['difficulty']?></span>
        </div>
        <div class="mr-network-row">
          <span class="mr-network-label">Node Version</span>
          <span class="mr-network-value"><?=$network['version']?></span>
        </div>
      </div>
      <?php } ?>

      <p style="text-align:center; margin-top:48px;">
        Running into bugs?
        <a target="_blank" href="https://github.com/marscoin/martianrepublic">Send us a bug report on GitHub <i class="fa fa-external-link"></i></a>
      </p>

    </div>
  </section>

  @include('partials.public-footer')

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</body>
</html>
