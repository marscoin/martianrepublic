<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Feed; 
use App\Models\Citizen;
use Illuminate\Support\Facades\DB;

class VoteTimeline extends Component
{
    public $activities;
    public $proposalId;

    public function mount($proposalId)
    {
        $this->proposalId = $proposalId ?? 0;
        $this->loadActivities();
    }

    public function loadActivities()
    {
        $this->activities = DB::table('proposals')
        ->join('feed', function ($join) {
            $join->on('proposals.txid', '=', 'feed.txid')
                 ->where('feed.tag', '=', 'PR');
        })
        ->join('citizen', 'proposals.user_id', '=', 'citizen.userid')
        ->where('proposals.id', $this->proposalId)
        ->select('proposals.*', 'feed.*', 'citizen.firstname', 'citizen.lastname', 'citizen.displayname', 'citizen.shortbio', 'citizen.avatar_link')
        ->take(10)
        ->get();
        
    }

    public function render()
    {
        return view('livewire.vote-timeline');
    }
}
    