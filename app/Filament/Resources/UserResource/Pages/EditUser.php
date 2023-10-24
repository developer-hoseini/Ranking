<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if(empty($data['password'])) {
            unset($data['password']);
        }

        unset($data['password_confirmation']);

        if($this->record->id == auth()->id()){
            $data['active'] = 1;
        }

        return $data;
    }
}
