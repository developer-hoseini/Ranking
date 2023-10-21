<?php

namespace App\Filament\Resources\GameResultResource\Pages;

use App\Filament\Resources\GameResultResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGameResults extends ListRecords
{
    protected static string $resource = GameResultResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
