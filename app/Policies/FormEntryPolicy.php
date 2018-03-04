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
}
