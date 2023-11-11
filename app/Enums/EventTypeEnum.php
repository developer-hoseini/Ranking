<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\BaseEnum;

enum EventTypeEnum: string
{
    use BaseEnum;

    case WIN = 'Win';
    case LOSE = 'Lose';
    case WARNING = 'Warning';
    case INFO = 'Info';

    case IN_CLUB_STAR = 'In_Club_Star';
    case WITH_IMAGE_STAR = 'With_Image_Star';
}
