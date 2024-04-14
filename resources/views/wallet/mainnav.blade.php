<link rel="stylesheet" href="/assets/wallet/css/mainnav/mainnav.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<div class="mainnav">

    <div class="container">
        <a class="mainnav-toggle" data-toggle="collapse" data-target=".mainnav-collapse">
            <span class="sr-only">Toggle navigation</span>
            <i class="fa fa-bars"></i>
        </a>
        <nav class="collapse mainnav-collapse" role="navigation">

            <div class="mainnav-form pull-right" style="display: flex; justify-content: center; align-items: center;">

            @livewire('wallet-status')
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

                @if ($active === 'forum')
                    <li class="dropdown active">
                    @else
                    <li class="dropdown">
                @endif
                <a href="/forum">
                    Forum
                </a>
                </li>

                <!-- @todo -->
                <!-- @if ($active === 'inventory')
                    <li class="dropdown active">
                    @else
                    <li class="dropdown">
                @endif
                <a href="/inventory/all">
                    Inventory
                </a>
                </li> -->

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
