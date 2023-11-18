<?php

namespace App\View\Components\Home;

use App\Enums\AchievementTypeEnum;
use App\Models\Achievement;
use App\Models\Game;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Ranks extends Component
{
    public function render(): View
    {

        $games = Game::query()
            ->whereHas('competitions', function ($q) {
                $q->has('gameResults');
            })
            ->with([
                'gameCompetitionsUsers' => function ($q) {
                    $q->orderByDesc(
                        Achievement::selectRaw('sum(count) as total_scores')
                            ->where('achievementable_type', User::class)
                            ->where('type', AchievementTypeEnum::SCORE->value)
                            ->whereColumn('achievementable_id', 'users.id')
                            ->groupBy('achievementable_id')
                    )
                        ->withSum('userScoreAchievements', 'count')
                        ->withSum('userCoinAchievements', 'count')
                        ->groupBy(['competitions.game_id'])
                        ->limit(3);

                },
                'gameCompetitionsUsers.profile',
                'gameCompetitionsUsers.media',
            ])
            ->has('gameCompetitionsUsers', '>=', 4)
            ->take(4)
            ->inRandomOrder()
            ->get();

        return view('components.home.ranks', compact('games'));
    }
}
