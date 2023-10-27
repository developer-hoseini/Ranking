<?php

namespace App\Policies;

use App\Models\User;

class BasePolicy
{
    public function authorize(User $user, string $action, string $modelClass)
    {
        // Use $modelClass to call the getModel method
        return $user->hasPermissionTo($action.' '.$modelClass);
    }
}
