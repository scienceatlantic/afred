<?php

namespace App\Http\Middleware;

use Log;
use Closure;
use App\User;
use Illuminate\Session;
use Illuminate\Support\Facades\Auth;

class SetupLocalUsers
{
    public function handle($request, Closure $next)
    {

        // Perform action
        if (env('APP_ENV') != "production"){

          // \Log::debug(print_r($request->route(), true));
          // \Log::debug(print_r(get_object_vars($request), true));

          // This is a hack to allow local environment development
          if(!$request->user()) {
              $credentials = [
                  'email'    => env('LOCAL_USER_ID'),
                  'password' => env('LOCAL_USER_PASSWORD')
              ];
              Auth::attempt($credentials);
          }
        }

        return $next($request);
    }
}