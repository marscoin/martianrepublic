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
        // Primary: use marscoin-cli directly (fastest, most reliable)
        try {
            $output = shell_exec('/usr/local/bin/marscoin-cli -datadir=/root/.marscoin getblockcount 2>/dev/null');
            if ($output && is_numeric(trim($output))) {
                $height = (int) trim($output);
                $hash = trim(shell_exec("/usr/local/bin/marscoin-cli -datadir=/root/.marscoin getblockhash {$height} 2>/dev/null"));
                if ($hash) {
                    $blockJson = shell_exec("/usr/local/bin/marscoin-cli -datadir=/root/.marscoin getblock {$hash} 2>/dev/null");
                    $block = json_decode($blockJson, true);
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
            $response = Http::timeout(10)->get('https://explore1.marscoin.org/api/status?q=getInfo');
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