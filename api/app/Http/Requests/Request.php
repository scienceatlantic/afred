<?php

namespace App\Http\Requests;

// Misc.
use Auth;

// Laravel.
use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest
{
    protected function _isAtLeastAdmin()
    {
        return Auth::check() && Auth::user()->isAtLeastAdmin();
    }
}
