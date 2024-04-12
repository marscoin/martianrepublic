<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Profile; 
use App\Models\CivicWallet; 
use App\Models\HDWallet; 
use App\Includes\AppHelper;
use Illuminate\Support\Facades\Auth;

class WalletStatus extends Component
{
    public $wallet_open = false;
    public $balance = 0;
    public $uid;
    public $loading = true;

    public function loadWalletData()
    {
        $user = Auth::user();
        $profile = Profile::where('userid', '=', $user->id)->first();

        if ($profile && $profile->wallet_open > 0) {
            $wallet = HDWallet::where('user_id', '=', $user->id)->first();
        } elseif ($profile && $profile->civic_wallet_open > 0) {
            $wallet = CivicWallet::where('user_id', '=', $user->id)->first();
        } else {
            $wallet = null;
        }

        if ($wallet) {
            $this->wallet_open = true;
            $this->balance = AppHelper::getMarscoinBalance($wallet->public_addr);
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