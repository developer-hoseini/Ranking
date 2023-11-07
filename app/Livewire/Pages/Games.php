<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class Games extends Component
{
    public $gameType = 'double';

    public function render()
    {
        return view('livewire.pages.games');
    }
}
