<?php
namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;

class BlockDisplay extends Component
{
    public $blockNumber = 'Loading...';
    public $timeSinceLastBlock = "n/a";
    public $lastBlockMinedAt;

    private const CLI = '/usr/local/bin/marscoin-cli';
    private const DATA_DIR = '/root/.marscoin';

    public function mount()
    {
        $this->fetchBlockNumber();
    }

    public function fetchBlockNumber()
    {
        try {
            $result = Process::timeout(10)->run([self::CLI, '-datadir=' . self::DATA_DIR, 'getblockcount']);
            if ($result->successful() && is_numeric(trim($result->output()))) {
                $height = (int) trim($result->output());

                $result = Process::timeout(10)->run([self::CLI, '-datadir=' . self::DATA_DIR, 'getblockhash', (string) $height]);
                $hash = trim($result->output());
                if ($result->successful() && $hash) {
                    $result = Process::timeout(10)->run([self::CLI, '-datadir=' . self::DATA_DIR, 'getblock', $hash]);
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
