<?php

namespace App\Http\Controllers;

// Misc.
use Auth;

// Models.
use App\Role;
use App\User;

// Requests.
use Illuminate\Http\Request;

class AuthController extends Controller
{    
    public function login(Request $request)
    {
        $credentials = [
            'email'    => $request->input('email', null),
            'password' => $request->input('password', null),
            'isActive' => 1
        ];

        if (Auth::attempt($credentials)) {
            return $this->format('login');
        }

        return ['error' => 'Invalid login credentials'];
    }
    
    public function ping()
    {
        if (Auth::check()) {
            return $this->format();
        }
        return ['error' => 'Not authenticated'];
    }
    
    public function logout()
    {
        Auth::logout();
    }

    public static function format($updateDateLast = 'pinged', $user = false)
    {
        // Set default.
        $user = $user ?: Auth::user();
        
        // Update dates.
        if ($updateDateLast) {
            self::updateDates($updateDateLast);
        }
        
        // Get array.
        $array = $user->toArray();

        // Add max permission level.
        $maxPermission = $user->getMaxPermission();
        $array['maxPermissionLevel'] = $maxPermission;

        $roles = [];
        // Add "Strict" (i.e. have been explicitly assigned) roles.
        foreach ($user->roles()->get() as $r) {
            $roles[$r->id] = [
                'name'       => $r->name,
                'permission' => $r->permission
            ];
            $key = 'is' . studly_case(strtolower($r->name)) . 'Strict';
            $array[$key] = true;
        }

        // Add roles below maximum (i.e. highest permission level) explicitly 
        // assigned role (without "Strict" suffix).
        foreach (Role::all() as $r) {
            if ($maxPermission >= $r->permission) {
                $key = 'is' . studly_case(strtolower($r->name));
                $array[$key] = true;
            }
        }
        $array['roles'] = (object) $roles;

        return $array;
    }

    private static function updateDates($dateLast = 'pinged', $user = false)
    {
        // Get default.
        $user = $user ?: Auth::user();

        // Get current time.
        $now = Controller::now();

        switch ($dateLast) {
            case 'login':
                $user->dateLastLogin = $now;
                // No break.
            case 'pinged':
                $user->dateLastPinged = $now;
        }
        $user->save();
    }
}
