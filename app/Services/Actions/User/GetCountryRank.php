<?php

namespace App\Services\Actions\User;

use App\Enums\AchievementTypeEnum;
use App\Models\Achievement;
use App\Models\Country;
use App\Models\User;

class GetCountryRank
{
    public static function handle(int $userId, ?int $countryId, int $gameId): int
    {
        if (! $countryId) {
            return 0;
        }
        $country = Country::query()
            ->with([
                'countryCompetitionsUsers' => function ($q) use ($countryId, $gameId) {
                    $q->orderByDesc(
                        Achievement::selectRaw('sum(count) as total_scores')
                            ->where('achievementable_type', User::class)
                            ->where('type', AchievementTypeEnum::SCORE->value)
                            ->whereColumn('achievementable_id', 'users.id')
                            ->whereHas('achievementCompetitionStateCountry', fn ($q) => $q->where('countries.id', $countryId))
                            ->whereHas('achievementCompetition', fn ($q) => $q->where('game_id', $gameId))
                            ->groupBy('achievementable_id')
                    );
                },
            ])
            ->find($gameId);

        $rank = $country?->countryCompetitionsUsers
            ->where('id', $userId)
            ->keys()
            ?->first() + 1;

        return $rank ?? 0;
    }
}
