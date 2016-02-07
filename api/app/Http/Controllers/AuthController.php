<?php

namespace App\Http\Controllers;

// Controllers.
use App\Http\Controllers\Controller;

// Laravel.
use Illuminate\Http\Request;

// Misc.
use Auth;
use Hash;
use Log;

// Models.
use App\User;

// Requests.
use App\Http\Requests;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $email = $request->input('email', null);
        $password = $request->input('password', null);
        
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            return Auth::user();
        }
        
        return response('Not authorized', 401);
    }
    
    public function ping()
    {
        if (Auth::check()) {
            return Auth::user();
        }
        
        return response('Not authenticated', 401);
    }
    
    public function logout()
    {
        Auth::logout();
    }
}
