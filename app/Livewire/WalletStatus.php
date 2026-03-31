<?php

namespace App\Livewire;

use App\Includes\AppHelper;
use App\Models\CivicWallet;
use App\Models\HDWallet;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class WalletStatus extends Component
{
    public $uid;

    public $balance = 0;

    public $loading = true;

    public $wallet_open = false;

    public $previous_balance = 0;

    public function loadWalletData()
    {
        $user = Auth::user();
        if (! $user) {
            $this->loading = false;

            return;
        }
        $profile = Profile::where('userid', '=', $user->id)->first();

        if (! $profile || ($profile->wallet_open == 0 && $profile->civic_wallet_open == 0)) {
            $this->wallet_open = false;
            $this->balance = 0;
            $this->loading = false;

            return;
        }

        // Aggregate balance across all user wallets
        $totalBalance = 0;
        $hasWallet = false;

        // Check civic wallet
        if ($profile->civic_wallet_open > 0) {
            $civicWallet = CivicWallet::where('user_id', '=', $user->id)->first();
            if ($civicWallet) {
                $hasWallet = true;
                $totalBalance += AppHelper::getMarscoinBalance($civicWallet->public_addr);
            }
        }

        // Check HD wallet (if different from civic)
        if ($profile->wallet_open > 0) {
            $hdWallet = HDWallet::find($profile->wallet_open);
            if ($hdWallet) {
                $hasWallet = true;
                // Avoid double-counting if HD and civic share the same address
                $civicAddr = CivicWallet::where('user_id', $user->id)->value('public_addr');
                if ($hdWallet->public_addr !== $civicAddr) {
                    $totalBalance += AppHelper::getMarscoinBalance($hdWallet->public_addr);
                }
            }
        }

        if ($hasWallet) {
            $this->wallet_open = true;
            if ($this->balance != $totalBalance) {
                $this->previous_balance = $this->balance;
                $this->balance = $totalBalance;
                $this->dispatch('balanceUpdated');
            }
        } else {
            $this->wallet_open = false;
            $this->balance = 0;
        }

        $this->loading = false;
    }

    public function mount()
    {
        $this->loading = true;
    }

    public function render()
    {
        return view('livewire.wallet-status');
    }
}
