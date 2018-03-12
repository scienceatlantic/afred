<?php

namespace App\Policies;

use App\User;
use App\FormReport;
use Illuminate\Auth\Access\HandlesAuthorization;

class FormReportPolicy
{
    use HandlesAuthorization;

    public function show(User $user, FormReport $formReport)
    {
        return $user->is_at_least_editor;
    }
}
