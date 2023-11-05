<?php

namespace App\Livewire\Pages\Tournaments;

use App\Models\Competition;
use Livewire\Component;

class SoloTournaments extends Component
{
    #[\Livewire\Attributes\Computed]
    public function competitions()
    {
        $competitions = Competition::query()
            ->statusTournament()
            ->has('users', '<=', 2)
            ->doesntHave('teams')
            ->with([
                'users',
                'game',
                'state.country',
                'gameResults.status',
            ])
            ->latest()
            ->take(12)
            ->get();

        return $competitions ?? [];
    }

    public function render()
    {
        return view('livewire.pages.tournaments.solo-tournaments');
    }
}
