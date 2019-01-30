<?php

namespace App\Http\Controllers;

use App\User;
use Log;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Encryption\Encrypter;

class LoginController extends Controller
{
    public function login(Request $request)
    {

        // User is allowed to login via email or wp_username.
        if (!$user = User::findByEmail($request->email)) {
            $user = User::findByWpUsername($request->email);
        }

        if ($user) {
            // Skip if user is not active, or user's password is blank.
            if ($user->is_active && $user->password) {
                $credentials = [
                    'email'    => $user->email,
                    'password' => $request->password
                ];

                if (Auth::attempt($credentials)) {
                    return Auth::user();
                }
            }
        }

        return ['error' => 'Authentication failed'];
    }

    public function ping()
    {
        if (Auth::check()) {
            return Auth::user();
        } 
        return ['error' => 'Not authenticated'];
    }

    public function logout(Request $request)
    {
        Auth::logout();
    }
}
