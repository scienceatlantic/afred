<?php

namespace App\Http\Controllers;

use App\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Encryption\Encrypter;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $email = $request->email;

        // User is allowed to login via email or wp_username. If a username was
        // provided, grab user's email for auth.
        if ($user = User::findByWpUsername($request->email)) {
            $email = $user->email;
        }

        $user = User::findByEmail($email);

        // Skip if user is not active, or user's password is blank.
        if (!$user->is_active || $user->password) {
            $credentials = ['email' => $email, 'password' => $request->password];

            if (Auth::attempt($credentials)) {
                return Auth::user();
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
