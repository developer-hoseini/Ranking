<?php

namespace App\Policies;

use App\Traits\GeneralPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class GamePolicy
{
    use GeneralPolicy;
    use HandlesAuthorization;
}
