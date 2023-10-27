<?php

namespace App\Policies;

use App\Traits\GeneralPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class CupPolicy
{
    use GeneralPolicy;
    use HandlesAuthorization;
}
