<?php

namespace App\Filament\Resources\CupResource\Pages;

use App\Filament\Resources\CupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCups extends ListRecords
{
    protected static string $resource = CupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
