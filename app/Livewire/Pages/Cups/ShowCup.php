<?php

namespace App\Livewire\Pages\Cups;

use App\Models\Cup;
use Livewire\Component;

class ShowCup extends Component
{
    public $cup = [];

    public function mount($id)
    {
        $this->cup = $this->getCup($id);

    }

    private function getCup($cupId)
    {
        $cup = Cup::whereId($cupId)
            ->with([
                'competitions.gameResults.gameResultStatus',
                'competitions.users.media',
            ])
            ->firstOrFail();

        return $cup;
    }

    public function render()
    {
        return view('livewire.pages.cups.show-cup');
    }
}
