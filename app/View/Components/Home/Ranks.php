<?php

namespace App\View\Components\Home;

use App\Models\Game;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Ranks extends Component
{
    public function render(): View
    {
        $games = Game::has('scores', '>=', config('setting.home_ranks_table'))
            ->with([
                'scores' => fn ($q) => $q->with(['user', 'user.profile'])
                    ->orderBy('score', 'desc'),
            ])
            ->active()
            ->take(3)
            ->inRandomOrder()
            ->get();

        return view('components.home.ranks', compact('games'));
    }
}
