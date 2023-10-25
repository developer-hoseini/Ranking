<?php

namespace App\Filament\Resources\GameResultResource\Pages;

use App\Filament\Resources\GameResultResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGameResult extends EditRecord
{
    protected static string $resource = GameResultResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
