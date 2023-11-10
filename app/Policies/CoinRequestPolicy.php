<?php

namespace App\Policies;

use App\Traits\GeneralPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoinRequestPolicy
{
    use GeneralPolicy;
    use HandlesAuthorization;
}