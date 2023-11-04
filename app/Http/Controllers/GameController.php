<?php

namespace App\Http\Controllers;

use App\Enums\AchievementTypeEnum;
use App\Models\Achievement;
use App\Models\Game;
use App\Models\User;

class GameController extends Controller
{
    public function show(Game $game)
    {

        if (! $game->active) {
            return redirect()->route('home');
        }

        $game->loadCount(['invites']);

        $users = User::query()
            ->withSum([
                'scoreAchievements' => function ($query) use ($game) {
                    $query->whereHas('occurredModel', fn ($q) => $q->where('game_id', $game->id));
                },
            ], 'count')
            ->withSum('coinAchievements', 'count')
            ->withCount('likes')
            ->whereHas('competitions', function ($query) use ($game) {
                $query->where('game_id', $game->id);
            })
            ->with([
                'profile',
                'media',
            ])
            ->orderByDesc(
                Achievement::selectRaw('sum(count) as total_scores')
                    ->where('achievementable_type', User::class)
                    ->where('type', AchievementTypeEnum::SCORE->value)
                    ->whereColumn('achievementable_id', 'users.id')
                    ->whereHas('occurredModel', fn ($q) => $q->where('game_id', $game->id))
                    ->groupBy('achievementable_id')
            )
            ->paginate(config('setting.gameinfo_list'));

        return view('game.show', ['game' => $game, 'users' => $users]);
    }
}
