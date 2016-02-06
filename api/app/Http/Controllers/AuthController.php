<?php

namespace App\Http\Controllers;

// Controllers.
use App\Http\Controllers\Controller;

// Laravel.
use Illuminate\Http\Request;

// Misc.
use Hash;

// Models.
use App\SystemUser;

// Requests.
use App\Http\Requests;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $uid = $request->input('username', null);
        $pwd = $request->input('password', null);        
        $systemUser = SystemUser::where('username', $uid)->first();
        
        if ($systemUser && (Hash::check($pwd, $systemUser->password))) {
            $request->session()->put('authUser', $systemUser);
            return $systemUser;
        }
        else {
            return response('Not authenticated', 401);
        }
    }
    
    public function ping(Request $request)
    {
        if ($request->session()->get('authUser')) {
            return $request->session()->get('authUser');
        }
        else {
            return response('Not authenticated', 401);
        }
    }
    
    public function logout(Request $request)
    {
        $request->session()->forget('authUser');
    }
}
