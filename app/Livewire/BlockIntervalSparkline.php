<?php
namespace App\Livewire;

use Livewire\Component;

class BlockIntervalSparkline extends Component
{
    public array $intervals = [];
    public float $avgInterval = 0;
    public float $currentDifficulty = 0;

    public function mount()
    {
        $this->fetchIntervals();
    }

    public function fetchIntervals()
    {
        try {
            $height = (int) trim(shell_exec('/usr/local/bin/marscoin-cli -datadir=/root/.marscoin getblockcount 2>/dev/null'));
            if ($height < 2) return;

            $intervals = [];
            $prevTime = null;
            // Fetch last 30 blocks for the sparkline
            $startBlock = max(1, $height - 29);

            for ($i = $startBlock; $i <= $height; $i++) {
                $hash = trim(shell_exec("/usr/local/bin/marscoin-cli -datadir=/root/.marscoin getblockhash {$i} 2>/dev/null"));
                if (!$hash) continue;
                $blockJson = shell_exec("/usr/local/bin/marscoin-cli -datadir=/root/.marscoin getblock {$hash} 2>/dev/null");
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
