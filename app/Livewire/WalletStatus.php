<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CivicWallet; 
use App\Includes\AppHelper;
use Illuminate\Support\Facades\Auth;

class WalletStatus extends Component
{
    public $wallet_open = false;
    public $balance = 0;
    public $uid; 
    public function mount()
    {
        $civic_wallet = CivicWallet::where('user_id', '=', Auth::id())->first();
        $balance = AppHelper::getMarscoinBalance($civic_wallet->public_addr);

        if ($civic_wallet) {
            $this->wallet_open = true;
            $this->balance = $balance; 
        }
    }

    public function render()
    {
        return view('livewire.wallet-status');
    }
}