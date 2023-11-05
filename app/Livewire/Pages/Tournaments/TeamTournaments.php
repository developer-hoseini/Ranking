<?php

namespace App\Livewire\Pages\Tournaments;

use App\Models\Competition;
use Livewire\Component;

class TeamTournaments extends Component
{
    #[\Livewire\Attributes\Computed]
    public function competitions()
    {
        $competitions = Competition::query()
            ->statusTournament()
            ->has('teams', '<=', 2)
            ->doesntHave('users')
            ->with([
                'teams',
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
        return view('livewire.pages.tournaments.team-tournaments');
    }
}
