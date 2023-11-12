<?php

namespace App\View\Components\Home;

use App\Models\Competition;
use App\Models\Cup;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Tournaments extends Component
{
    public function render(): View
    {
        ////////// Tournaments //////////
        // $tournaments = Competition::query()
        //     ->statusTournament()
        //     ->orderBy('id', 'DESC')
        //     ->with(['users', 'game', 'teams', 'state.country'])
        //     ->take(12)
        //     ->get();

        $tournaments = Cup::query()
            ->with([
                'game',
                'state.country',
                'competitions.gameResults.status',
            ])
            ->withCount(['registeredUsers'])
            ->latest()
            ->take(12)
            ->get();

        /* TODO: only show cups that has ->withWhereHas('media') */
        $tournamentImages = Cup::query()
            ->orderBy('id', 'DESC')
            ->take(12)
            ->get();

        return view('components.home.tournaments', compact('tournaments', 'tournamentImages'));
    }
}
