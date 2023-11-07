<?php

namespace App\Filament\Resources\CupResource\Pages;

use App\Filament\Resources\CupResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCup extends CreateRecord
{
    protected static string $resource = CupResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by_user_id'] = auth()->id();

        return $data;
    }
}
