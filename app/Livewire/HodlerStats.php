<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\{CivicWallet, HDWallet, Profile};
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
        $this->loadCoinCount();
    }

    public function loadCoinCount()
    {
        $user = Auth::user();
        if (!$user) return;

        $totalBalance = 0;
        $profile = Profile::where('userid', '=', $user->id)->first();

        // Civic wallet balance
        $civicWallet = CivicWallet::where('user_id', '=', $user->id)->first();
        $civicAddr = $civicWallet ? $civicWallet->public_addr : null;
        if ($civicAddr) {
            $totalBalance += AppHelper::getMarscoinBalance($civicAddr);
        }

        // HD wallet balance (avoid double-counting if same address as civic)
        if ($profile && $profile->wallet_open > 0) {
            $hdWallet = HDWallet::find($profile->wallet_open);
            if ($hdWallet && $hdWallet->public_addr !== $civicAddr) {
                $totalBalance += AppHelper::getMarscoinBalance($hdWallet->public_addr);
            }
        }

        $this->balance = $totalBalance;
        $this->coincount = AppHelper::getMarscoinTotalAmount();
        $this->dispatch('coinDataUpdated');
    }

    public function render()
    {
        return view('livewire.hodler-stats');
    }
}
