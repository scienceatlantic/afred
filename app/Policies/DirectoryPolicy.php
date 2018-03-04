<?php

namespace App\Policies;

use App\User;
use App\Directory;
use Illuminate\Auth\Access\HandlesAuthorization;

class DirectoryPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->is_administrator) {
            return true;
        }
    }

    public function index(User $user)
    {
        return $user->is_at_least_editor;
    }

    public function indexForms(User $user, Directory $directory)
    {
        if ($user->is_editor) {
            return (bool) $directory
                ->users()
                ->where('id', $user->id)
                ->first();
        }

        return false;        
    }    

    public function indexFormEntries(User $user, Directory $directory)
    {
        if ($user->is_editor) {
            return (bool) $directory
                ->users()
                ->where('id', $user->id)
                ->first();
        }

        return false;        
    }
}
