<div wire:poll.60s="loadWalletData" wire:init="loadWalletData">
    @if ($loading)
    <div class="wallet-is-open" >
            <h5 class="nav-wallet-balance"
                style="background-color: #2f4354; color: white; padding: 9px; border-radius: 8px; text-align: center">
                <img src="/assets/wallet/img/marscoin-350x350.png" width="20" height="20" />
                <i class="fa fa-spinner fa-spin fa-fw"></i> MARS
            </h5>
        </div>
    @else
        @if ($wallet_open)
        <div class="wallet-is-open" >
            <h5 class="nav-wallet-balance"
                style="background-color: #2f4354; color: white; padding: 9px; border-radius: 8px; text-align: center">
                <img src="/assets/wallet/img/marscoin-350x350.png" width="20" height="20" />
                {{ $balance}} MARS
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
    @endif
</div>