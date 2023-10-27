<?php

namespace App\Policies;

use App\Traits\GeneralPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPolicy
{
    use GeneralPolicy;
    use HandlesAuthorization;
}
