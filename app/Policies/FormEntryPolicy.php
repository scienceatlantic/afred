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

    // TODO: call it conflicts with ?action=show
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
        if (!$user->is_at_least_editor) {
            return false;
        }

        if (!$formEntry->is_submitted) {
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
        if (!$user->is_at_least_editor) {
            return false;
        }
        
        if (!$formEntry->is_published) {
            abort(400);
        }

        if ($formEntry->has_pending_operations) {
            $msg = 'Attempting to delete form entry with pending operations';
            Log::warning($msg, [
                'formEntry' => $formEntry->toArray(),
                'user'      => $user->toArray()
            ]);
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
        if (!$user->is_at_least_editor) {
            return false;
        }
        
        if (!$formEntry->is_published) {
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
        if (!$user->is_at_least_editor) {
            return false;
        }
        
        if (!$formEntry->is_hidden) {
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
        if (!$formEntry->is_published) {
            $msg = 'Attempting to open token on form entry that is not '
                 . 'published';
            Log::warning($msg, [
                'formEntry' => $formEntry->toArray(),
                'user'      => $user->toArray()
            ]);
            abort(400);            
        }

        if ($formEntry->tokens()->unclosed()->count()) {
            $msg = 'Attempting to open token on form entry that already has '
                 . 'open/locked token';
            Log::warning($msg, [
                'formEntry' => $formEntry->toArray(),
                'user'      => $user->toArray()
            ]);
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
