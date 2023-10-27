<?php

namespace App\Policies;

use App\Traits\GeneralPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class GameTypePolicy
{
    use GeneralPolicy;
    use HandlesAuthorization;
}
