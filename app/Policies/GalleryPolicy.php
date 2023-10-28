<?php

namespace App\Policies;

use App\Traits\GeneralPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class GalleryPolicy
{
    use GeneralPolicy;
    use HandlesAuthorization;
}
