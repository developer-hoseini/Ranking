<?php

namespace App\Filament\Resources\CoinRequestResource\Pages;

use App\Filament\Resources\CoinRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCoinRequests extends ListRecords
{
    protected static string $resource = CoinRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
