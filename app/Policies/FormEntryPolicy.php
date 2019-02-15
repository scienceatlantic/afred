<?php

namespace App\Policies;

use App\Directory;
use App\FormEntry;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Log;

class FormEntryPolicy
{
    use HandlesAuthorization;

    public function show(User $user, FormEntry $formEntry)
    {
        if ($user->is_administrator) {
            return true;
        } else if ($user->is_editor) {
            return (bool) $formEntry
                ->form
                ->directory
                ->users()
                ->where('id', $user->id)
                ->first();
        }

        return false;
    }

    public function publish(User $user, FormEntry $formEntry)
    {
        if (!$formEntry->can_publish) {
            abort(400);
        }

        if ($user->is_administrator) {
            return true;
        } else if ($user->is_editor) {
            return (bool) $formEntry
                ->form
                ->directory
                ->users()
                ->where('id', $user->id)
                ->first();
        }

        return false;
    }

    public function reject(User $user, FormEntry $formEntry)
    {
        return $this->publish($user, $formEntry);
    }

    public function destroy(User $user, FormEntry $formEntry)
    {
        if (!$formEntry->can_delete) {
            abort(400);
        }

        if ($user->is_administrator) {
            return true;
        } else if ($user->is_editor) {
            return (bool) $formEntry
                ->form
                ->directory
                ->users()
                ->where('id', $user->id)
                ->first();
        }

        return false;
    }

    public function hide(User $user, FormEntry $formEntry)
    {
        if (!$formEntry->can_hide) {
            abort(400);
        }

        if ($user->is_administrator) {
            return true;
        } else if ($user->is_editor) {
            return (bool) $formEntry
                ->form
                ->directory
                ->users()
                ->where('id', $user->id)
                ->first();
        }

        return false;
    }

    public function unhide(User $user, FormEntry $formEntry)
    {
        if (!$formEntry->can_unhide) {
            abort(400);
        }

        if ($user->is_administrator) {
            return true;
        } else if ($user->is_editor) {
            return (bool) $formEntry
                ->form
                ->directory
                ->users()
                ->where('id', $user->id)
                ->first();
        }

        return false;
    }

    public function openToken(User $user, FormEntry $formEntry)
    {
        // Administrators can always edit form entries.
        if (!$formEntry->can_edit && !$user->is_administrator) {
            abort(400);
        }

        if ($user->is_administrator) {
            return true;
        } else if ($user->is_editor) {
            return true;
        } else {
            return (bool) $user
                ->formEntries()
                ->where('form_entries.id', $formEntry->id)
                ->first();
        }
    }
}
