<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\{CivicWallet};
use Illuminate\Support\Facades\Auth;
use App\Includes\AppHelper;

class HodlerStats extends Component
{

    public $coincount;
    public $balance;

    public function mount()
    {
        $this->balance = 0;
        $this->coincount = 0;
    }

    public function loadCoinCount()
    {
        $uid = Auth::user()->id;
        $civic_wallet = CivicWallet::where('user_id', '=', $uid)->first();
        $this->balance = AppHelper::getMarscoinBalance($civic_wallet->public_addr);
        $this->coincount = AppHelper::getMarscoinTotalAmount();
        $this->dispatch('coinDataUpdated');
    }

    public function render()
    {
        return view('livewire.hodler-stats');
    }
}
