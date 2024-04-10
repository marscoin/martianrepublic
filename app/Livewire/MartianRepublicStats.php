<?php

namespace App\Livewire;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Feed;
use App\Models\Profile;

class MartianRepublicStats extends Component
{
    public function render()
    {
        $martiansCount = DB::table('feed')
            ->where('tag', 'GP')
            ->count();

        $applicantsCount = Profile::where('has_application', 1)
        ->where(function($query) {
            $query->whereNull('citizen')
                  ->orWhere('citizen', '<>', 1);
        })
        ->where(function($query) {
            $query->whereNull('general_public')
                  ->orWhere('general_public', '<>', 1);
        })
        ->count();

        $newarrivalsCount = DB::table('profile')
        ->count();

        $citizensCount = DB::table('feed')
            ->where('tag', 'CT')
            ->count();

        $openProposalsCount = DB::table('proposals')
        ->whereRaw('DATE_ADD(mined, INTERVAL duration DAY) > ?', [Carbon::now()])
        ->count();

        return view('livewire.martian-republic-stats', compact('martiansCount', 'citizensCount', 'openProposalsCount', 'applicantsCount', 'newarrivalsCount'));
    }
}


