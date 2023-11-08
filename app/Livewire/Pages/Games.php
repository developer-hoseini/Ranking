<?php

namespace App\Livewire\Pages;

use Illuminate\Http\Request;
use Livewire\Component;

class Games extends Component
{
    public $gameType = 'double';

    public function mount(Request $request)
    {
        $gameTypeRequest = $request->get('gameType');
        if ($gameTypeRequest) {
            if (in_array($gameTypeRequest, ['double', 'online', 'team'])) {
                $this->gameType = $gameTypeRequest;
            }
        }
    }

    public function render()
    {
        return view('livewire.pages.games');
    }
}
