<?php

namespace App\Filament\Resources\OnlineGameResource\Pages;

use App\Filament\Resources\OnlineGameResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOnlineGame extends EditRecord
{
    protected static string $resource = OnlineGameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
