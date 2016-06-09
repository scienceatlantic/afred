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
            // Update date last logged in.
            Auth::user()->dateLastLogin = $this->now();
            Auth::user()->save();
            
            // Lazy load user roles.
            Auth::user()->roles;
            
            return $this->toCcArray(Auth::user()->toArray());
        }
        
        return 'Not authorised';
    }
    
    public function ping()
    {
        if (Auth::check()) {
            // Lazy load user roles.
            Auth::user()->roles;
            
            return $this->toCcArray(Auth::user()->toArray());
        }
        
        return 'Not authenticated';
    }
    
    public function logout()
    {
        Auth::logout();
    }
}
