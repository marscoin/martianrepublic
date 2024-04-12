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
        $this->activities = Feed::where('userid', $this->userId)
            ->whereIn('tag', ['ED', 'SP', 'GP', 'CT', 'LB']) // Include other tags as needed
            ->latest('mined')
            ->take(10)
            ->get();
        $this->citcache = Citizen::where('userid', $this->userId)->first();
    }

    public function render()
    {
        return view('livewire.civic-activity-feed');
    }
}
