<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Feed; 
use App\Models\Citizen;
use Illuminate\Support\Facades\Auth;


class CivicActivityFeed extends Component
{
    public $activities;
    public $citcache;
    public $userId;

    public function mount($userId = null)
    {
        $this->userId = $userId ?? Auth::id();
        $this->loadActivities();
    }

    public function loadActivities()
    {
        // Assuming you want to load activities related to the logged-in user
        $this->citcache = Citizen::where('userid', $this->userId)->first();
        $publicAddress = $this->citcache->public_address;
        $this->activities = Feed::where(function ($query) use ($publicAddress) {
            $query->where('userid', $this->userId)
                  ->orWhere('message', 'like', '%' . $publicAddress . '%');
        })
        ->whereIn('tag', ['ED', 'SP', 'GP', 'CT', 'LB']) 
        ->orderBy('mined', 'desc') 
        ->orderBy('id', 'desc') 
        ->take(10)
        ->get();
        
    }

    public function render()
    {
        return view('livewire.civic-activity-feed');
    }
}
