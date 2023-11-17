<?php

namespace App\View\Components\Home;

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
            ->cupAcceptedStatusScope()
            ->with([
                'game',
                'state.country',
                'competitions.gameResults',
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
