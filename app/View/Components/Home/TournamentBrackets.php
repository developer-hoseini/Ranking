<?php

namespace App\View\Components\Home;

use App\Models\Cup;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TournamentBrackets extends Component
{
    public function render(): View
    {
        $cups = Cup::query()->orderBy('id', 'DESC')
            ->withWhereHas('competitions')

//            ->whereHas('latest_bracket')
//            ->orWhereHas('latest_team_bracket')
//            ->with(['latest_bracket', 'latest_team_bracket'])
            ->take(10)
            ->get();

        return view('components.home.tournament-brackets', compact('cups'));
    }
}
