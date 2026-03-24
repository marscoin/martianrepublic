<link rel="stylesheet" href="/assets/wallet/css/dashboard/dashboard.css?v=3.0">
<link rel="stylesheet" href="/assets/wallet/css/mainnav/mainnav.css?v=2.1">
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

                @if ($active === 'map')
                    <li class="dropdown active">
                    @else
                    <li class="dropdown">
                @endif
                <a href="/map/all">
                    Map
                </a>
                </li>


            </ul>
        </nav>
    </div> <!-- /.container -->
</div> <!-- /.mainnav -->
<script src="/assets/wallet/js/libs/jquery-1.10.2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    toastr.options = { "positionClass": "toast-top-right", "timeOut": "4000", "closeButton": true };
    @if(session('message'))
        toastr.success("{!! addslashes(session('message')) !!}");
    @endif
    @if(session('error'))
        toastr.error("{!! addslashes(session('error')) !!}");
    @endif
    @if(session('warning'))
        toastr.warning("{!! addslashes(session('warning')) !!}");
    @endif

    // Wallet Key Manager - encrypts mnemonic in localStorage with session token
    window.WalletKey = (function() {
        const SESSION_TOKEN = "{{ csrf_token() }}";
        const STORAGE_KEY = "key";
        const STORAGE_KEY_ENC = "wk_enc";

        function xorEncrypt(text, key) {
            let result = '';
            for (let i = 0; i < text.length; i++) {
                result += String.fromCharCode(text.charCodeAt(i) ^ key.charCodeAt(i % key.length));
            }
            return btoa(result);
        }

        function xorDecrypt(encoded, key) {
            try {
                const text = atob(encoded);
                let result = '';
                for (let i = 0; i < text.length; i++) {
                    result += String.fromCharCode(text.charCodeAt(i) ^ key.charCodeAt(i % key.length));
                }
                return result;
            } catch(e) {
                return null;
            }
        }

        return {
            set: function(mnemonic) {
                // Store encrypted version
                localStorage.setItem(STORAGE_KEY_ENC, xorEncrypt(mnemonic, SESSION_TOKEN));
                // Keep plaintext for backward compat during transition
                localStorage.setItem(STORAGE_KEY, mnemonic);
            },
            get: function() {
                // Try encrypted first
                const enc = localStorage.getItem(STORAGE_KEY_ENC);
                if (enc) {
                    const decrypted = xorDecrypt(enc, SESSION_TOKEN);
                    if (decrypted && decrypted.split(' ').length >= 12) {
                        return decrypted;
                    }
                }
                // Fall back to plaintext (legacy)
                return localStorage.getItem(STORAGE_KEY);
            },
            clear: function() {
                localStorage.removeItem(STORAGE_KEY);
                localStorage.removeItem(STORAGE_KEY_ENC);
            },
            has: function() {
                return !!(localStorage.getItem(STORAGE_KEY_ENC) || localStorage.getItem(STORAGE_KEY));
            }
        };
    })();
</script>
