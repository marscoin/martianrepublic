<?php

namespace App\Livewire;

use App\Models\Citizen;
use App\Models\Feed;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ResearchActivity extends Component
{
    public $activities;

    public $citcache;

    public function mount()
    {
        $this->loadActivities();
    }

    public function loadActivities()
    {
        // Assuming you want to load activities related to the logged-in user
        $this->activities = Feed::where('userid', Auth::id())
            ->whereIn('tag', ['LB']) // Include other tags as needed
            ->latest('mined')
            ->take(10)
            ->get();
        $this->citcache = Citizen::where('userid', Auth::id())->first();
    }

    public function render()
    {
        return view('livewire.research-activity');
    }
}
