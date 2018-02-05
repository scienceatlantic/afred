<?php

namespace App\Policies;

use App\User;
use App\FormEntry;
use Illuminate\Auth\Access\HandlesAuthorization;

class FormEntryPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->is_at_least_subscriber;
    }

    public function show(User $user, FormEntry $formEntry)
    {
        return $user->is_at_least_subscriber;
    }
}
