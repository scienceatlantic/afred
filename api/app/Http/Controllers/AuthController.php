<?php

namespace App\Http\Controllers;

// Controllers.
use App\Http\Controllers\Controller;

// Laravel.
use Illuminate\Http\Request;

// Misc.
use App;
use Hash;

// Models.
use App\SystemUser;

// Requests.
use App\Http\Requests;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $authUser = [
            'firstName' => null,
            'lastName' => null,
            'username' => null,
            'isAdmin' => null,
            'isAuth' => null
        ];
        
        $systemUser = SystemUser::where('username', $request->
            input('username'))->first();
        
        if (is_object($systemUser) && (Hash::check($request->input('password'),
            $systemUser->password))) {
                $authUser['firstName'] = $systemUser->first_name;
                $authUser['lastName'] = $systemUser->last_name;
                $authUser['username'] = $systemUser->username;
                $authUser['isAdmin'] = $systemUser->role == 'ADMIN';
                $authUser['isAuth'] = true;
                $request->session()->put('authUser', $authUser);
                
                return $authUser;
        }
        else {
            return App::abort(401, 'Not authenticated');
        }
    }
    
    public function ping(Request $request)
    {
        if ($request->session()->get('authUser')) {
            return $request->session()->get('authUser');
        }
        else {
            return App::abort(401, 'Not authenticated');
        }
    }
    
    public function logout(Request $request)
    {
        $request->session()->forget('authUser');
    }
}
