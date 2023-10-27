<?php

namespace App\Policies;

use App\Traits\GeneralPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvitePolicy
{
    use GeneralPolicy;
    use HandlesAuthorization;
}
