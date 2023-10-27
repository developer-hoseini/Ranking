<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\GeneralPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use GeneralPolicy;
    use HandlesAuthorization;

    public function delete(User $user, User $model): bool
    {
        return $model->id !== auth()->id() && $user->can(['delete User']);
    }
}
