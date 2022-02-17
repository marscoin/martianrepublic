<link rel="stylesheet" href="/assets/wallet/css/mainnav/mainnav.css">
<div class="mainnav">

    <div class="container">
        <a class="mainnav-toggle" data-toggle="collapse" data-target=".mainnav-collapse">
            <span class="sr-only">Toggle navigation</span>
            <i class="fa fa-bars"></i>
        </a>
        <nav class="collapse mainnav-collapse" role="navigation">

            <div class="mainnav-form pull-right" style="display: flex; justify-content: center; align-items: center;">


                @if ($wallet_open)
                <div class="wallet-is-open" >
                    <h5 class="nav-wallet-balance"
                        style="background-color: #2f4354; color: white; padding: 9px; border-radius: 8px; text-align: center">
                        <img src="/assets/wallet/img/marscoin-350x350.png" width="20" height="20" />
                        {{ $balance }} MARS
                    </h5>


                </div>

                @else
                <div class="wallet-is-not-open" >
                    <a href="/wallet/dashboard/hd" style="text-decoration:none; color: white" class="connect-wallet">

                        <h5 style="background-color: #2f4354; color: white; padding: 9px; border-radius: 8px; text-align: center"
                            class="nav-wallet-connect">
                            <img src="/assets/wallet/img/marscoin-350x350.png" width="20" height="20" />

                            Connect Wallet
                        </h5>
                    </a>

                </div>


                @endif
            </div>

            <ul class="mainnav-menu">
                @if ($active === 'dashboard')
                    <li class="dropdown active">
                    @else
                    <li class="dropdown">
                @endif
                <a href="/wallet/dashboard">
                    Dashboard
                </a>
                </li>
                @if ($active === 'wallet')
                    <li class="dropdown active">
                    @else
                    <li class="dropdown">
                @endif
                <a href="/wallet/dashboard/hd">
                    Wallet
                </a>
                </li>
                @if ($active === 'citizen')
                    <li class="dropdown active">
                    @else
                    <li class="dropdown">
                @endif
                <a href="/citizen/all">
                    Citizen
                </a>
                </li>

                @if ($active === 'congress')
                    <li class="dropdown active">
                    @else
                    <li class="dropdown">
                @endif
                <a href="/congress/voting">
                    Congress
                </a>
                </li>

                @if ($active === 'inventory')
                    <li class="dropdown active">
                    @else
                    <li class="dropdown">
                @endif
                <a href="/inventory/all">
                    Inventory
                </a>
                </li>

                @if ($active === 'logbook')
                    <li class="dropdown active">
                    @else
                    <li class="dropdown">
                @endif
                <a href="/logbook/all">
                    Logbook
                </a>
                </li>


            </ul>
        </nav>
    </div> <!-- /.container -->
</div> <!-- /.mainnav -->
<script src="/assets/wallet/js/libs/jquery-1.10.2.min.js"></script>

<script>
    // const nav_key = localStorage.getItem("key");
    // if (nav_key != null) {
    //     $(".wallet-is-open").show()
    //     $(".wallet-is-not-open").hide()

    // } else {
    //     $(".wallet-is-not-open").show()
    //     $(".wallet-is-open").hide()

    // }
</script>
