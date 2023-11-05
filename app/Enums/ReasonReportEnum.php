<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\BaseEnum;

enum ReasonReportEnum: string
{
    use BaseEnum;

    case PROFILE_PHOTO = 'profile_photo';
    case OBSCENE_TEXT = 'obscene_text';
}
