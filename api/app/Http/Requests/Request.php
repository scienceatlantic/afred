<?php

namespace App\Http\Requests;

// Misc.
use Auth;

// Laravel.
use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest
{
    protected function isSuperAdmin()
    {
        return Auth::check() && Auth::user()->isSuperAdmin();
    }
    
    protected function isAdmin()
    {
        return Auth::check() && Auth::user()->isAdmin();
    }
}
