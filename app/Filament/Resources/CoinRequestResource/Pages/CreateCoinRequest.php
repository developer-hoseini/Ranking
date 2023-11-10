<?php

namespace App\Filament\Resources\CoinRequestResource\Pages;

use App\Filament\Resources\CoinRequestResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCoinRequest extends CreateRecord
{
    protected static string $resource = CoinRequestResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by_user_id'] = auth()->id();

        return $data;
    }
}
