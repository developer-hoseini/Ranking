<?php

namespace App\Services\Actions\User;

use App\Enums\AchievementTypeEnum;
use App\Models\Achievement;
use App\Models\Game;
use App\Models\User;

class GetGameRank
{
    public static function handle(int $userId, int $gameId): int
    {
        $game = Game::query()
            ->with([
                'gameCompetitionsUsers' => function ($q) use ($gameId) {
                    $q->orderByDesc(
                        Achievement::selectRaw('sum(count) as total_scores')
                            ->where('achievementable_type', User::class)
                            ->where('type', AchievementTypeEnum::SCORE->value)
                            ->whereColumn('achievementable_id', 'users.id')
                            ->whereHas('achievementCompetition', fn ($q) => $q->where('game_id', $gameId))
                            ->groupBy('achievementable_id')
                    );
                },
            ])
            ->find($gameId);

        $rank = $game?->gameCompetitionsUsers
            ->where('id', $userId)
            ->keys()
            ?->first();

        return $rank ? $rank + 1 : 0;
    }
}
