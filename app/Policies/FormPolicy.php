<?php

namespace App\Policies;

use App\User;
use App\Form;
use Illuminate\Auth\Access\HandlesAuthorization;

class FormPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->is_at_least_subscriber;
    }

    public function show(User $user, Form $form)
    {
        return true;
    }
}
