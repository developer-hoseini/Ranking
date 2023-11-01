<?php

namespace App\View\Components\Home;

use App\Models\Competition;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TournamentBrackets extends Component
{
    public function render(): View
    {
        $tournamentsBracket = Competition::orderBy('id', 'DESC')
//            ->whereHas('latest_bracket')
//            ->orWhereHas('latest_team_bracket')
//            ->with(['latest_bracket', 'latest_team_bracket'])
            ->take(10)
            ->get();

        return view('components.home.tournament-brackets', compact('tournamentsBracket'));
    }
}
