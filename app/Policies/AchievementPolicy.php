<?php

namespace App\Policies;

use App\Traits\GeneralPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class AchievementPolicy
{
    use GeneralPolicy;
    use HandlesAuthorization;
}
