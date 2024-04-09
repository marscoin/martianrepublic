<?php
namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Http;

class BlockDisplay extends Component
{
    public $blockNumber = 'Loading...';
    public $timeSinceLastBlock = "n/a";
    public $lastBlockMinedAt;

    public function mount()
    {
        $this->fetchBlockNumber();
    }

    public function fetchBlockNumber()
    {
        $lastBlockHashResponse = Http::get('https://explore1.marscoin.org/api/status?q=getLastBlockHash');

        if ($lastBlockHashResponse->successful()) {
            $lastBlockHash = $lastBlockHashResponse->json()['lastblockhash'];

            $blockDetailsResponse = Http::get("https://explore1.marscoin.org/api/block/$lastBlockHash");

            if ($blockDetailsResponse->successful()) {
                $this->blockNumber = $blockDetailsResponse->json()['height'];
                $this->lastBlockMinedAt = Carbon::createFromTimestamp($blockDetailsResponse->json()['time']);
                $this->updateTimeSinceLastBlock();
                $this->dispatch('block-update');

            } else {
                $this->blockNumber = 'Error fetching block details';
            }
        } else {
            $this->blockNumber = 'Error fetching last block hash';
        }
    }

    public function updateTimeSinceLastBlock()
    {
        $this->timeSinceLastBlock = Carbon::createFromTimestamp($this->lastBlockMinedAt)->diffForHumans(null, true);
    }

    public function render()
    {
        return view('livewire.block-display');
    }
}