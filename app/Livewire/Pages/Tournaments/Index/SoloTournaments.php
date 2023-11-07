<?php

namespace App\Livewire\Pages\Tournaments\Index;

use App\Models\Cup;
use Livewire\Component;

class SoloTournaments extends Component
{
    #[\Livewire\Attributes\Computed]
    public function cups()
    {
        $cups = Cup::query()
            ->with([
                'game',
                'state.country',
                'competitions.gameResults.status',
            ])
            ->withCount(['registeredUsers'])
            ->latest()
            ->take(12)
            ->get();

        return $cups ?? [];
    }

    public function render()
    {
        return view('livewire.pages.tournaments.index.solo-tournaments');
    }
}
