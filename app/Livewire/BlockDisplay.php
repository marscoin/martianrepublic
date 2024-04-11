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
        try 
        {
            $lastBlockHashResponse = Http::retry(3, 100)->timeout(60)->get('https://explore1.marscoin.org/api/status?q=getLastBlockHash');
            // Process the successful response

            if ($lastBlockHashResponse->successful()) {
                $lastBlockHash = $lastBlockHashResponse->json()['lastblockhash'];

                try {
                    $blockDetailsResponse = Http::retry(3, 100)->timeout(60)->get('https://explore1.marscoin.org/api/block/$lastBlockHash');
                    // Process the successful response
    
                    if ($blockDetailsResponse->successful()) {
                        $this->blockNumber = $blockDetailsResponse->json()['height'];
                        $this->lastBlockMinedAt = Carbon::createFromTimestamp($blockDetailsResponse->json()['time']);
                        $this->updateTimeSinceLastBlock();
                        $this->dispatch('block-update');
        
                    } else {
                        $this->blockNumber = 'Error fetching block details';
                    }
    
                } catch (\Exception $e) {
                    // Log the error and proceed with a fallback value or message
                    $this->blockNumber = 'Error fetching block details';
                }

            } else {
                $this->blockNumber = 'Error fetching last block hash';
            }


        } catch (\Exception $e) {
            // Log the error and proceed with a fallback value or message
            $this->blockNumber = 'Maintenance...';
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