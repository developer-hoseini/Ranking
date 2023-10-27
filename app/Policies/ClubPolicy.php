<?php

namespace App\Policies;

use App\Traits\BasePolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClubPolicy
{
    use BasePolicy,HandlesAuthorization;
}
