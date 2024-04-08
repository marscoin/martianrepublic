<?php

namespace App\Livewire;
use Carbon\Carbon;
use Livewire\Component;

class BlockchainActivityFeed extends Component
{
    public $activities = []; 
    public $onloadDisplayCount = 8; // default value, can be dynamic

    public function loadRecentActivities()
    {
        $this->activities = \DB::table('feed')
            ->join('citizen', 'feed.userid', '=', 'citizen.userid')
            ->select('feed.*', 'citizen.displayname', 'citizen.firstname', 'citizen.lastname')
            ->orderByDesc('feed.mined')
            ->take($this->onloadDisplayCount)
            ->get()
            ->map(function ($activity) {
                $activity->description = $this->formatActivityDescription($activity);
                $activity->mined = Carbon::parse($activity->mined);
                return $activity;
            });
    }

    private function formatActivityDescription($activity)
    {
        $description = '';
        $embeddedLink = !empty($activity->embedded_link) ? $activity->embedded_link : '#';
    
        switch ($activity->tag) {
            case 'GP':
                $description = "joined the general Martian ";
                if (!empty($activity->embedded_link)) {
                    $description .= " <a target='_blank' href='{$embeddedLink}'>public</a>.";
                }
                break;
            case 'CT':
                $description = "was ";
                if (!empty($activity->embedded_link)) {
                    $description .= " <a target='_blank' href='{$embeddedLink}'>granted citizenship</a>.";
                }
                break;
            case 'ED':
                $description = "endorsed a new ";
                if (!empty($activity->embedded_link)) {
                    $description .= " <a target='_blank' href='{$embeddedLink}'>citizenship application</a>.";
                }
                break;
            case 'PR':
                $description = "launched a new voting proposal.";
                if (!empty($activity->embedded_link)) {
                    $description .= " <a target='_blank' href='{$embeddedLink}'>View Proposal</a>.";
                }
                break;
            case 'SP':
                $description = "signed a post.";
                if (!empty($activity->embedded_link)) {
                    $description .= " <a target='_blank' href='{$embeddedLink}'>Read Post</a>.";
                }
                break;
            default:
                $description = "performed an unclassified activity.";
                break;
        }
    
        return $description;
    }

    public function mount()
    {
        $this->loadRecentActivities();
    }

    public function render()
    {
        return view('livewire.blockchain-activity-feed');
    }
}
