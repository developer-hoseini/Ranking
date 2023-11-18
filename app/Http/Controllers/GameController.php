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
            return redirect()->route('page.home');
        }

        $game->loadCount(['invites']);

        $users = User::query()
            ->withSum([
                'userScoreAchievements' => function ($query) use ($game) {
                    $query->whereHas('occurredModel', fn ($q) => $q->where('game_id', $game->id));
                },
            ], 'count')
            ->withSum('userCoinAchievements', 'count')
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

        return view('games.show', ['game' => $game, 'users' => $users]);
    }

    public function showOnline($id)
    {
        $game = Game::query()
            ->where('id', $id)
            ->active()
            ->withWhereHas('onlineGames')
            ->select(['id', 'name'])
            ->orderBy('sort')
            ->firstOrFail();

        return view('games.show-online', compact('game'));
    }

    public function join($id)
    {
        /* TODO: compelete join method */
        // only 2 player game can join
        $games = Game::query()
            ->where('id', $id)
            ->active()
            ->gameTypesScope(['two player'], true)
            ->select(['id', 'name'])
            ->firstOrFail();

        return $games;
    }
}
