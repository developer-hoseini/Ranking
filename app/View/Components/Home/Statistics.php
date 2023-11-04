<?php

namespace App\View\Components\Home;

use App\Models\Competition;
use App\Models\Cup;
use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Statistics extends Component
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
        $statistics = [
            'users_count' => User::count(),
            'tournoments_count' => Cup::count(),
            'games_count' => Competition::count(),
            'games_images_count' => Competition::whereHas('game')->has('media')->count(),
        ];

        return view('components.home.statistics', compact('statistics'));
    }
}
