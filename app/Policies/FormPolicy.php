<?php

namespace App\Policies;

use App\User;
use App\Form;
use Illuminate\Auth\Access\HandlesAuthorization;

class FormPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->is_administrator) {
            return true;
        }
    }

    public function show(User $user, Form $form)
    {
        if ($user->is_editor) {
            return (bool) $form
                ->directories
                ->users()
                ->where('id', $user->id)
                ->first();
        }

        return false;
    }
}
