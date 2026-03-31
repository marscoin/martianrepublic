<?php

namespace App\Livewire;

use App\Models\Citizen;
use App\Models\Feed;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CivicActivityFeed extends Component
{
    public $activities;

    public $citcache;

    public $userId;

    public int $perPage = 8;

    public int $page = 1;

    public bool $hasMore = true;

    public function mount($userId = null)
    {
        $this->userId = $userId ?? Auth::id();
        $this->loadActivities();
    }

    public function loadMore()
    {
        $this->page++;
        $this->loadActivities();
    }

    public function loadActivities()
    {
        $this->citcache = Citizen::where('userid', $this->userId)->first();
        if (! $this->citcache) {
            $this->activities = collect();
            $this->hasMore = false;

            return;
        }
        $publicAddress = $this->citcache->public_address;
        $total = $this->page * $this->perPage;
        $all = Feed::where(function ($query) use ($publicAddress) {
            $query->where('userid', $this->userId)
                ->orWhere('message', 'like', '%'.$publicAddress.'%');
        })
            ->whereIn('tag', ['ED', 'SP', 'GP', 'CT', 'LB'])
            ->orderBy('mined', 'desc')
            ->orderBy('id', 'desc')
            ->take($total + 1)
            ->get();

        $this->hasMore = $all->count() > $total;
        $this->activities = $all->take($total);
    }

    public function render()
    {
        return view('livewire.civic-activity-feed');
    }
}
