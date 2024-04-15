<ul class="nav navbar-nav navbar-right">
  <li>
    <a target="_blank" href="http://marscoin.org/">About Marscoin</a>
  </li>
  <li>
    <a target="_blank" href="http://martianrepublic.org/forum/c/1-public-square">Discussion</a>
  </li>
  <li>
    <a target="_blank" href="https://marscoin.gitbook.io/marscoin-documentation/">Documentation</a>
  </li>
  <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;">
             Fund <i class="mainnav-caret"></i> 
             <i class="fa fa-caret-down"></i>
            </a>

                    <ul class="dropdown-menu" role="menu">
                    <li>
                            <a target="_blank" href="https://www.lbank.info/exchange/mars/usdt">
                                <i class="fa fa-area-chart"></i>
                                LBank
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="https://dex-trade.com/spot/trading/MARSUSDT">
                                <i class="fa fa-area-chart"></i>
                                Dex-Trade
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="https://www.finexbox.com/market/pair/MARS-BTC.html">
                                <i class="fa fa-area-chart"></i>
                                FinexBox
                            </a>
                        </li>

                    </ul>
                </li>


  <li class="dropdown navbar-profile">
    <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;">
      <img src="https://unavatar.io/{{ Auth::user()->email }}?fallback=https://martianrepublic.org/assets/citizen/generic_profile.jpg" class="navbar-profile-avatar" alt="">
      <span class="navbar-profile-label">{{ Auth::user()->email }} &nbsp;</span>
      <i class="fa fa-caret-down"></i>
    </a>
    <ul class="dropdown-menu" role="menu">
      <li>
        <a href="/wallet/dashboard/hd-close">
          <i class="fa fa-window-close-o"></i>
          &nbsp;&nbsp;Lock Wallet
        </a>
      </li>
      <li>
        <a href="/logout" onclick="flushLocalStorage();">
          <i class="fa fa-sign-out"></i>
          &nbsp;&nbsp;Logout
        </a>
      </li>
    </ul>
  </li>

</ul>

<script type="text/javascript">
  function flushLocalStorage () {


      localStorage.clear();
      localStorage.removeItem('key');


      // fallback double check if key exists...
      if("key" in localStorage)
      {
          localStorage.clear()
          localStorage.removeItem('key');
      }

      return
  }
</script>