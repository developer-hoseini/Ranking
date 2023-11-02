<?php

namespace App\View\Components\Home;

use App\Enums\StatusEnum;
use App\Models\Competition;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Tournaments extends Component
{
    public function render(): View
    {
        ////////// Tournaments //////////
        $tournaments = Competition::query()
            ->statusTournament()
            ->where('end_register_at', '>=', now())
            ->orderBy('id', 'DESC')
            ->with(['users', 'game', 'teams', 'state.country'])
            ->take(12)
            ->get();

        $tournamentImages = Competition::query()
            ->whereHas('teams')
            ->whereHas('status', function ($query) {
                $query->whereIn('name', [
                    StatusEnum::COMPETITION_TOURNAMENT->value,
                ]);
            })
            ->orderBy('id', 'DESC')
            ->with([])
            ->take(12)
            ->get();

        return view('components.home.tournaments', compact('tournaments', 'tournamentImages'));
    }
}
