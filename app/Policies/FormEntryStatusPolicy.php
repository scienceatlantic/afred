<?php

namespace App\Policies;

use App\User;
use App\FormEntryStatus;
use Illuminate\Auth\Access\HandlesAuthorization;

class FormEntryStatusPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->is_at_least_editor;
    }
}
