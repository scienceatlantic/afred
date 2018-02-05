<?php

namespace App\Policies;

use App\User;
use App\Directory;
use Illuminate\Auth\Access\HandlesAuthorization;

class DirectoryPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return true;
    }
}
