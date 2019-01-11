<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function store(User $user)
    {
        return $user->is_administrator;
    }

    public function update(User $user, User $userBeingUpdated)
    {
        if ($user->is_administrator) {
            return true;
        } else if ($user->id === $userBeingUpdated->id) {
            return true;
        }

        return false;
    }

    public function destroy(User $user, User $userBeingDestroyed)
    {
        return $user->is_administrator;
    }
}
