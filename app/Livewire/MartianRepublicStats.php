<?php

namespace App\Livewire;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Feed;

class MartianRepublicStats extends Component
{
    public function render()
    {
        $martiansCount = DB::table('feed')
            ->where('tag', 'GP')
            ->count();

        $citizensCount = DB::table('feed')
            ->where('tag', 'CT')
            ->count();

        $openProposalsCount = DB::table('proposals')
        ->whereRaw('DATE_ADD(mined, INTERVAL duration DAY) > ?', [Carbon::now()])
        ->count();

        return view('livewire.martian-republic-stats', compact('martiansCount', 'citizensCount', 'openProposalsCount'));
    }
}


