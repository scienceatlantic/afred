<?php

namespace App\Http\Requests;

// Misc.
use Auth;

// Laravel.
use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest
{
    protected function isSuperAdmin($strict = false)
    {
        return Auth::check() && Auth::user()->isSuperAdmin($strict);
    }
    
    protected function isAdmin($strict = false)
    {
        return Auth::check() && Auth::user()->isAdmin($strict);
    }
}
