<?php

namespace App\Services\Actions\User\Achievement;

use App\Enums\AchievementTypeEnum;
use App\Enums\StatusEnum;
use App\Models\Achievement;
use App\Models\Status;
use App\Models\User;

class ReceiveCoin
{
    public static function handle(User $user, int $coin, StatusEnum $statusEnum): \ErrorException|Achievement
    {

        if (
            $statusEnum != StatusEnum::ACHIEVEMENT_APPROVE &&
             $statusEnum != StatusEnum::ACHIEVEMENT_BUY_OR_SELL_COIN &&
             $statusEnum != StatusEnum::ACHIEVEMENT_COMPLETE_PROFILE &&
             $statusEnum != StatusEnum::ACHIEVEMENT_LOSE &&
             $statusEnum != StatusEnum::ACHIEVEMENT_SIGNUP &&
             $statusEnum != StatusEnum::ACHIEVEMENT_WIN
        ) {
            throw new \ErrorException('status must be achievemnet');
        }

        $acheivement = $user->coinAchievements()->create([
            'count' => $coin,
            'status_id' => Status::nameScope($statusEnum->value)->first()?->id,
            'type' => AchievementTypeEnum::COIN->value,
        ]);

        return $acheivement;
    }
}
