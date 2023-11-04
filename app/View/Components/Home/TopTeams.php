<?php

namespace App\View\Components\Home;

use App\Enums\AchievementTypeEnum;
use App\Models\Achievement;
use App\Models\Game;
use App\Models\Team;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TopTeams extends Component
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

        $games = Game::query()
            ->whereHas('competitions', function ($q) {
                $q->has('gameResults');
            })
            ->with([
                'gameCompetitionsTeams' => function ($q) {
                    $q->orderByDesc(
                        Achievement::selectRaw('sum(count) as total_scores')
                            ->where('achievementable_type', Team::class)
                            ->where('type', AchievementTypeEnum::SCORE->value)
                            ->whereColumn('achievementable_id', 'teams.id')
                            ->groupBy('achievementable_id')
                    )->limit(3);
                },
                'gameCompetitionsTeams',
            ])
            ->has('gameCompetitionsTeams', '>=', 3)
            ->take(4)
            ->get();

        return view('components.home.top-teams', compact('games'));
    }
}
