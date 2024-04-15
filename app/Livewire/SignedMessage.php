<?php

namespace App\Livewire;
use Livewire\Component;
use App\Models\Feed; 
use App\Models\Votes;
use Illuminate\Support\Facades\Auth;

class SignedMessage extends Component
{
    public function render()
    {
        return view('livewire.signed-message');
    }
}
