<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\{CivicWallet, Proposals};
use Illuminate\Support\Facades\Auth;
use App\Includes\AppHelper;

class DashboardStats extends Component
{
    public $public_addr, $balance, $received, $sent, $forum_count, $proposal_count, $citizen_status, $wallet_open;
    public $isLoaded = false;

    public function mount()
    {
        $uid = Auth::user()->id;
        $civic_wallet = CivicWallet::where('user_id', '=', $uid)->first();
        $this->public_addr =  $civic_wallet ? $civic_wallet->public_address : "n/a";
        $this->received = 0;
        $this->sent = 0;
        $this->forum_count = AppHelper::checkForRecentPosts();
        $this->proposal_count = Proposals::countOpenProposals();
        $this->citizen_status = AppHelper::getCitizenStatus($uid)->type;
        $this->balance = 0;
    }

    public function loadData()
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        $civicWallet = CivicWallet::where('user_id', '=', $user->id)->first();

        $this->public_addr = $civicWallet ? $civicWallet->public_addr : '';
        $this->received = $civicWallet ? AppHelper::getMarscoinTotalReceived($this->public_addr) : 0;
        $this->sent = $civicWallet ? AppHelper::getMarscoinTotalSent($this->public_addr) : 0;
        $this->balance = $civicWallet ? $this->received - $this->sent : 0; 

        $this->forum_count = AppHelper::checkForRecentPosts();
        $this->proposal_count = Proposals::countOpenProposals();
        $this->citizen_status = AppHelper::getCitizenStatus($user->id)->type;
        $this->isLoaded = true;
    }

    public function render()
    {
        return view('livewire.dashboard-stats');
    }
}
