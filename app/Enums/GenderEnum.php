<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\BaseEnum;

enum GenderEnum: string
{
    use BaseEnum;

    case OTHER = '0';
    case MALE = '1';
    case FEMALE = '2';

    public function getLabel(): string
    {
        return match ($this) {
            GenderEnum::OTHER => 'other',
            GenderEnum::MALE => 'male',
            GenderEnum::FEMALE => 'female',
        };
    }
}
