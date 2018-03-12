<?php

namespace App\Policies;

use App\User;
use App\FormEntryToken as Token;
use Illuminate\Auth\Access\HandlesAuthorization;
use Log;

class FormEntryTokenPolicy
{
    use HandlesAuthorization;

    public function closeToken(User $user, Token $token)
    {
        if (!$token->is_open) {
            $msg = 'Attempting to close token that isn\'t open';
            Log::warning($msg, [
                'token' => $token->toArray(),
                'user' => $user->toArray()
            ]);
            abort(400);
        }
        
        return $user->is_at_least_editor;
    }
}
