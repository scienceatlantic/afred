<?php

namespace App\Http\Controllers;

// Controllers.
use App\Http\Controllers\Controller;

// Laravel.
use Illuminate\Http\Request;

// Misc.
use Auth;

// Models.
use App\Role;
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
            // Check if user is active, otherwise logout.
            if (Auth::user()->isActive || Auth::logout()) {
                return $this->format(true);              
            }
        }

        return 'Not authorised';
    }
    
    public function ping()
    {
        return Auth::check() ? $this->format() : 'Not authenticated';
    }
    
    public function logout()
    {
        Auth::logout();
    }

    private function format($updateDateLastLogin = false)
    {
        // Update date last login.
        if ($updateDateLastLogin) {
            Auth::user()->dateLastLogin = $this->now();
            Auth::user()->save();
        }

        $user = Auth::user()->toArray();
        $maxPermission = Auth::user()->getMaxPermission();

        // Add "Strict" (i.e. have been explicitly assigned) roles.
        foreach (Auth::user()->roles()->get() as $r) {
            $user['is' . studly_case(strtolower($r->name)) . 'Strict'] = true;
        }

        // Add roles below maximum (i.e. highest permission level) explicitly 
        // assigned role (without "Strict" suffix).
        foreach (Role::all() as $r) {
            if ($maxPermission >= $r->permission) {
                $user['is' . studly_case(strtolower($r->name))] = true;
            }
        }
        
        return $user;
    }
}
