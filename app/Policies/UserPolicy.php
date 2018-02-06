<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->is_at_least_subscriber;
    }

    public function show(User $user, User $userBeingShowned)
    {
        return $user->is_at_least_subscriber;
    }

    public function create(User $user)
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
}
