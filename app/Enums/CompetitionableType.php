<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\BaseEnum;

enum CompetitionableType: string
{
    use BaseEnum;

    case PLAYER = 'player';
    case AGENT = 'agent';
}
