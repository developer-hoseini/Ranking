<?php

namespace App\Policies;

use App\Traits\GeneralPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class GameResultPolicy
{
    use GeneralPolicy;
    use HandlesAuthorization;
}
