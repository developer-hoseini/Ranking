<?php

namespace App\View\Components\Home;

use App\Models\Competition;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LatestPlayed extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {

        $competitions = Competition::query()
            ->withWhereHas('gameResults.gameResultStatus')
            ->with(['users.media', 'game'])
            ->latest()
            ->get();

        return view('components.home.latest-played', compact('competitions'));
    }
}
