<?php

namespace App\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;
use Livewire\Component;

class BlockDisplay extends Component
{
    public $blockNumber = 'Loading...';

    public $timeSinceLastBlock = 'n/a';

    public $lastBlockMinedAt;

    public function mount()
    {
        $this->fetchBlockNumber();
    }

    public function fetchBlockNumber()
    {
        $cli = config('blockchain.rpc.cli_path');
        $dataDir = config('blockchain.rpc.data_dir');

        try {
            $result = Process::timeout(10)->run([$cli, '-datadir='.$dataDir, 'getblockcount']);
            if ($result->successful() && is_numeric(trim($result->output()))) {
                $height = (int) trim($result->output());

                $result = Process::timeout(10)->run([$cli, '-datadir='.$dataDir, 'getblockhash', (string) $height]);
                $hash = trim($result->output());
                if ($result->successful() && $hash) {
                    $result = Process::timeout(10)->run([$cli, '-datadir='.$dataDir, 'getblock', $hash]);
                    $block = json_decode($result->output(), true);
                    if ($block && isset($block['time'])) {
                        $this->blockNumber = $height;
                        $this->lastBlockMinedAt = Carbon::createFromTimestamp($block['time']);
                        $this->updateTimeSinceLastBlock();
                        $this->dispatch('block-update');

                        return;
                    }
                }
            }
        } catch (\Exception $e) {
            // Fall through to explorer
        }

        // Fallback: explorer API
        try {
            $response = Http::timeout(10)->get(config('blockchain.explorer.fallback_url').'/api/status?q=getInfo');
            if ($response->successful()) {
                $this->blockNumber = $response->json()['info']['blocks'] ?? '---';
                $this->dispatch('block-update');
            }
        } catch (\Exception $e) {
            $this->blockNumber = '---';
        }
    }

    public function updateTimeSinceLastBlock()
    {
        if ($this->lastBlockMinedAt instanceof Carbon) {
            $this->timeSinceLastBlock = $this->lastBlockMinedAt->diffForHumans(null, true);
        }
    }

    public function render()
    {
        return view('livewire.block-display');
    }
}
