<?php

namespace App\Livewire\Pages\Tournaments\Index;

use App\Models\Competition;
use App\Models\Cup;
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
                'gameResults.gameResultUserStatus',
            ])
            ->latest()
            ->take(12)
            ->get();

        return $competitions ?? [];
    }

    #[\Livewire\Attributes\Computed]
    public function cups()
    {
        $cups = Cup::query()
            ->teamScope(true)
            ->cupAcceptedStatusScope()
            ->with([
                'game',
                'state.country',
                'competitions.gameResults.gameResultUserStatus',
            ])
            ->withCount(['cupRegisteredTeamsUsers'])
            ->latest()
            ->take(12)
            ->get();

        return $cups ?? [];
    }

    public function render()
    {
        return view('livewire.pages.tournaments.index.team-tournaments');
    }
}
