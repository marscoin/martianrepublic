<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CountdownTimer extends Component
{
    /**
     * Create a new component instance.
     */
    public $endTime;
    public $proposalId;
    public $startTime;

    public function __construct($proposalId, $endTime, $startTime)
    {
        $this->proposalId = $proposalId;
        $this->endTime = $endTime;
        $this->startTime = $startTime;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.countdown-timer');
    }
}
