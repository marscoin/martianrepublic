<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\{CivicWallet, Proposals, Profile, HDWallet};
use Illuminate\Support\Facades\Auth;
use App\Includes\AppHelper;

class DashboardStats extends Component
{
    public $public_addr, $balance, $received, $sent, $forum_count, $proposal_count, $citizen_status, $wallet_open;
    public $isLoaded = false;

    public function mount()
    {
        $uid = Auth::user()->id;
        $profile = Profile::where('userid', '=', $uid)->first();
        $openwallet = null;
        if($profile && $profile->wallet_open > 0)
        {
            $openwallet = HDWallet::where('id', '=', $profile->wallet_open)->first();
            $this->public_addr = $openwallet ? $openwallet->public_address : 'n/a';
        }
        else if ($profile && $profile->civic_wallet_open > 0)
        {
            $openwallet = CivicWallet::where('id', '=', $profile->civic_wallet_open)->first();
            $this->public_addr = $openwallet ? $openwallet->public_address : 'n/a';
        }
        else{
            $this->public_addr =  "n/a";
        }
        $this->received = 0;
        $this->sent = 0;
        $this->forum_count = AppHelper::checkForRecentPosts();
        $this->proposal_count = Proposals::countOpenProposals();
        $citizenStatus = AppHelper::getCitizenStatus($uid);
        $this->citizen_status = $citizenStatus ? $citizenStatus->type : 'unknown';
        $this->balance = 0;
    }

    public function loadData()
    {
        if (!Auth::check()) {
            return redirect('/login');
        }
        $user = Auth::user();
        $uid = $user->id;
        $profile = Profile::where('userid', '=', $uid)->first();
        $openwallet = null;
        if($profile->wallet_open > 0)
        {
            $openwallet = HDWallet::where('id', '=', $profile->wallet_open)->first();
            $this->public_addr = $openwallet ? $openwallet->public_addr : '';
        }
        else if ($profile->civic_wallet_open > 0)
        {
            $openwallet = CivicWallet::where('id', '=', $profile->civic_wallet_open)->first();
            $this->public_addr = $openwallet ? $openwallet->public_addr : ''; 
        }
        $this->received = $openwallet ? AppHelper::getMarscoinTotalReceived($this->public_addr) : 0;
        $this->sent = $openwallet ? AppHelper::getMarscoinTotalSent($this->public_addr) : 0;
        $this->balance = $openwallet ? $this->received - $this->sent : 0;

        $this->forum_count = AppHelper::checkForRecentPosts();
        $this->proposal_count = Proposals::countOpenProposals();
        $citizenStatus = AppHelper::getCitizenStatus($user->id);
        $this->citizen_status = $citizenStatus ? $citizenStatus->type : 'unknown';
        $this->isLoaded = true;
    }

    public function render()
    {
        return view('livewire.dashboard-stats');
    }
}
