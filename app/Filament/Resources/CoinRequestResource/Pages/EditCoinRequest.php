<?php

namespace App\Filament\Resources\CoinRequestResource\Pages;

use App\Filament\Resources\CoinRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCoinRequest extends EditRecord
{
    protected static string $resource = CoinRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
