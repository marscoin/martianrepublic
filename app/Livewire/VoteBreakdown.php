<?php

namespace App\Livewire;

namespace App\Livewire;
use Livewire\Component;
use App\Models\Vote;
use Illuminate\Support\Facades\Log;

class VoteBreakdown extends Component
{
    public $yayPercent;
    public $nayPercent;
    public $proposalId;

    public function mount($proposalId)
    {
        $this->proposalId = $proposalId;
    }

    public function loadVotes()
    {
        $this->calculateVotes();
        $this->dispatch('votesLoaded');
    }

    public function calculateVotes()
    {
        $yays = Vote::where('proposal_id', $this->proposalId)
                    ->where('vote', 'Y')
                    ->count();
        
        $nays = Vote::where('proposal_id', $this->proposalId)
                    ->where('vote', 'N')
                    ->count();

        $totalVotes = $yays + $nays;
        $this->yayPercent = $totalVotes > 0 ? round(($yays / $totalVotes) * 100, 2) : 0;
        $this->nayPercent = $totalVotes > 0 ? round(($nays / $totalVotes) * 100, 2) : 0;
        Log::info("Percentage: " . $this->yayPercent);
    }

    public function render()
    {
        return view('livewire.vote-breakdown');
    }
}
