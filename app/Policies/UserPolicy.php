<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\BasePolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use BasePolicy,HandlesAuthorization;

    public function delete(User $user, User $model): bool
    {
        return $model->id !== auth()->id() && $user->can(['delete User']);
    }
}
