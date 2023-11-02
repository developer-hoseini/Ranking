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
                'gameCompetitionsUsers.profile',
                'gameCompetitionsUsers.media',
            ])
            ->withWhereHas('gameCompetitionsUsers', function ($q) {
                $q->orderByDesc(
                    Achievement::selectRaw('sum(count) as total_scores')
                        ->where('achievementable_type', User::class)
                        ->where('type', AchievementTypeEnum::SCORE->value)
                        ->whereColumn('achievementable_id', 'users.id')
                        ->groupBy('achievementable_id')
                )
                    ->withSum('scoreAchievements', 'count')
                    ->withSum('coinAchievements', 'count')
                    ->limit(3);
            })
            ->get();

        return view('components.home.ranks', compact('games'));
    }
}
