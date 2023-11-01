<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\BaseEnum;

enum AchievementTypeEnum: string
{
    use BaseEnum;
    case COIN = 'coin';
    case SCORE = 'score';

}
