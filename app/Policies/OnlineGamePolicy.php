<?php

namespace App\Policies;

use App\Traits\GeneralPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class OnlineGamePolicy
{
    use GeneralPolicy;
    use HandlesAuthorization;
}