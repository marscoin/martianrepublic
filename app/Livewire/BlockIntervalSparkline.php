<?php
namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Process;

class BlockIntervalSparkline extends Component
{
    public array $intervals = [];
    public float $avgInterval = 0;
    public float $currentDifficulty = 0;

    public function mount()
    {
        $this->fetchIntervals();
    }

    private function cli(string ...$args): ?string
    {
        $cli = config('blockchain.rpc.cli_path');
        $dataDir = config('blockchain.rpc.data_dir');
        $command = array_merge([$cli, '-datadir=' . $dataDir], $args);
        $result = Process::timeout(10)->run($command);
        return $result->successful() ? trim($result->output()) : null;
    }

    public function fetchIntervals()
    {
        try {
            $output = $this->cli('getblockcount');
            if (!$output || !is_numeric($output)) return;
            $height = (int) $output;
            if ($height < 2) return;

            $intervals = [];
            $prevTime = null;
            $startBlock = max(1, $height - 29);

            for ($i = $startBlock; $i <= $height; $i++) {
                $hash = $this->cli('getblockhash', (string) $i);
                if (!$hash) continue;
                $blockJson = $this->cli('getblock', $hash);
                if (!$blockJson) continue;
                $block = json_decode($blockJson, true);
                if (!$block || !isset($block['time'])) continue;

                if ($prevTime !== null) {
                    $intervals[] = $block['time'] - $prevTime;
                }
                $prevTime = $block['time'];

                if ($i === $height && isset($block['difficulty'])) {
                    $this->currentDifficulty = (float) $block['difficulty'];
                }
            }

            $this->intervals = $intervals;
            $this->avgInterval = count($intervals) > 0 ? array_sum($intervals) / count($intervals) : 0;
        } catch (\Exception $e) {
            $this->intervals = [];
        }
    }

    public function render()
    {
        return view('livewire.block-interval-sparkline');
    }
}
