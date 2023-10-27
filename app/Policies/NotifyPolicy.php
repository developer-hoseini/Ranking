<?php

namespace App\Policies;

use App\Traits\GeneralPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotifyPolicy
{
    use GeneralPolicy;
    use HandlesAuthorization;
}
