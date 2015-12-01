<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Hash;

use App;
use App\SystemUser;
use App\Http\Requests;
use App\Http\Controllers\Controller;

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
