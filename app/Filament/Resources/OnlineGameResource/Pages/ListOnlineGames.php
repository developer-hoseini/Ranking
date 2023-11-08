<?php

namespace App\Filament\Resources\OnlineGameResource\Pages;

use App\Filament\Resources\OnlineGameResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOnlineGames extends ListRecords
{
    protected static string $resource = OnlineGameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
