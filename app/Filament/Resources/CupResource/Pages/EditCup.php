<?php

namespace App\Filament\Resources\CupResource\Pages;

use App\Filament\Resources\CupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCup extends EditRecord
{
    protected static string $resource = CupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
