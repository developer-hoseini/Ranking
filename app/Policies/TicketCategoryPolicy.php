<?php

namespace App\Policies;

use App\Traits\GeneralPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketCategoryPolicy
{
    use GeneralPolicy;
    use HandlesAuthorization;
}
