<?php

namespace App\Http\Controllers;

// Controllers.
use App\Http\Controllers\Controller;

// Laravel.
use Illuminate\Http\Request;

// Misc.
use Auth;
use Log;

// Models.
use App\User;

// Requests.
use App\Http\Requests;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = [
            'email'    => $request->input('email', null),
            'password' => $request->input('password', null)
        ];
        
        if (Auth::attempt($credentials)) {
            return $this->_toCamelCase(Auth::user()->toArray());
        }
        
        abort(401, 'Not authorized');
    }
    
    public function ping()
    {
        if (Auth::check()) {
            return $this->_toCamelCase(Auth::user()->toArray());
        }
        
        abort(401, 'Not authenticated');
    }
    
    public function logout()
    {
        Auth::logout();
    }
}
