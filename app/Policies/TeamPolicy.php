<?php

namespace App\Policies;

use App\Traits\GeneralPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
{
    use GeneralPolicy;
    use HandlesAuthorization;
}
