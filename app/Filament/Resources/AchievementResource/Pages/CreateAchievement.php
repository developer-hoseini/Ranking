<?php

namespace App\Filament\Resources\AchievementResource\Pages;

use App\Filament\Resources\AchievementResource;
use App\Models\Competition;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;

class CreateAchievement extends CreateRecord
{
    protected static string $resource = AchievementResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by_user_id'] = auth()->id();

        if (isset($data['achievementable_id'])) {
            $data['achievementable_type'] = User::class;
        }

        if (isset($data['occurred_model_id'])) {
            $data['occurred_model_type'] = Competition::class;
        }

        return $data;
    }
}
