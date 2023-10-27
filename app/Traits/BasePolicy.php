<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

trait BasePolicy
{
    public function getModel(): array|string
    {
        return str_replace('Policy', '', basename(str_replace('\\', '/', __CLASS__)));
    }

    public function viewAny(User $user): bool
    {
        return $user->can('view-any '.$this->getModel());
    }

    public function view(User $user, Model $model): bool
    {
        return $user->can('view '.$this->getModel());
    }

    public function create(User $user): bool
    {
        return $user->can('create '.$this->getModel());
    }

    public function update(User $user, Model $model): bool
    {
        return $user->can('update '.$this->getModel());
    }

    public function delete(User $user, Model $model): bool
    {
        return $user->can('delete '.$this->getModel());
    }

    public function restore(User $user, Model $model): bool
    {
        return $user->can('restore '.$this->getModel());
    }

    public function forceDelete(User $user, Model $model): bool
    {
        return $user->can('force-delete '.$this->getModel());
    }
}
