<?php

namespace App\Livewire;
use App\Models\CivicWallet;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Feed;

class CivicStatusFeed extends Component
{
    public $activities;

    public function mount()
    {
        $activities = DB::table('feed')
            ->leftJoin('citizen', 'feed.userid', '=', 'citizen.userid')
            ->whereIn('tag', ['GP', 'ED', 'CT'])
            ->orderByDesc('mined')
            ->take(3)
            ->get([
                'feed.*', 
                DB::raw("CONCAT(citizen.firstname, ' ', citizen.lastname) as citizenName")
            ]);

        $this->activities = $activities->map(function ($activity) {
            $citizenName = $activity->citizenName ?: "An anonymous user";
    
            switch ($activity->tag) {
                case 'ED':
                    $endorsed = DB::table('citizen')
                        ->where('public_address', $activity->message)
                        ->select(DB::raw("CONCAT(firstname, ' ', lastname) as fullname"))
                        ->first();
                    $endorsedName = $endorsed ? $endorsed->fullname : "someone";
                    $actionWord = "<a href='{$activity->embedded_link}'>endorsed</a>";
                    $activity->displayMessage = "{$citizenName} {$actionWord} {$endorsedName}";
                    break;
                case 'CT':
                    $actionWord = "<a href='{$activity->embedded_link}'>became a citizen</a>";
                    $activity->displayMessage = "{$citizenName} {$actionWord}";
                    break;
                case 'GP':
                    $actionWord = "<a href='{$activity->embedded_link}'>joined the Republic</a>";
                    $activity->displayMessage = "{$citizenName} {$actionWord}";
                    break;
            }
    
            return $activity;
        });
    }
    

    public function render()
    {
        return view('livewire.civic-status-feed');
    }
}
