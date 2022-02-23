<ul class="nav navbar-nav navbar-right">
  <li>
    <a target="_blank" href="http://marscoin.org/">About Marscoin</a>
  </li>
  <li>
    <a target="_blank" href="http://www.marscoin.org/community/">Discussion</a>
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
                            <a target="_blank" href="https://www.xt.com/trade/mars_usdt">
                                <i class="fa fa-area-chart"></i>
                                Xt.com
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="https://www.finexbox.com/market/pair/MARS-BTC.html">
                                <i class="fa fa-area-chart"></i>
                                FinexBox
                            </a>
                        </li>

                        <li>
                            <a target="_blank" href="https://ex.udonex.com/market/marsusdt">
                                <i class="fa fa-area-chart"></i>
                                Udonex
                            </a>
                        </li>



                    </ul>
                </li>


  <li class="dropdown navbar-profile">
    <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;">
      <img src="{{$gravtar_link}}" class="navbar-profile-avatar" alt="">
      <span class="navbar-profile-label">{{ Auth::user()->email }} &nbsp;</span>
      <i class="fa fa-caret-down"></i>
    </a>
    <ul class="dropdown-menu" role="menu">
<!--       <li>
        <a href="/wallet/profile">
          <i class="fa fa-user"></i>
          &nbsp;&nbsp;My Profile
        </a>
      </li>
      <li>
        <a href="/wallet/reports">
          <i class="fa fa-cogs"></i>
          &nbsp;&nbsp;Reports
        </a>
      </li>
      
      <li class="divider"></li> -->
      <li>
        <a href="/wallet/dashboard/hd-close">
          <i class="fa fa-window-close-o"></i>
          &nbsp;&nbsp;Disconnect Wallet
        </a>
      </li>
      <li>
        <a href="/logout">
          <i class="fa fa-sign-out"></i>
          &nbsp;&nbsp;Logout
        </a>
      </li>
    </ul>
  </li>

</ul>