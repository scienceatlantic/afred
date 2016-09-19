<?php

namespace App\Http\Requests;

// Misc.
use Auth;
use Route;

// Models.
use App\Role;
use App\User;

// Requests.
use App\Http\Requests\Request;

class UserRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        switch ($this->method()) {
            case 'GET':
                return $this->isAdmin();
            // All other methods require that the authenticated user be at least 
            // an admin. The authenticated user is not allowed to 
            // create/edit/delete a user of a higher permission level. 
            case 'POST':
            case 'PUT':
                if ($this->isAdmin()) {
                    // If updating user password.
                    if ($this->instance()->input('password', false)) {
                        $maxAuthRole = Auth::user()->getMaxPermission();
                        $maxUserRole = User::findOrFail(Route::input('users'))
                            ->getMaxPermission();
                        return $maxAuthRole >= $maxUserRole;
                    }
                    // If updating other user data.
                    $roleIds = $this->instance()->input('roles', [-1]);
                    $maxAuthRole = Auth::user()->getMaxPermission();
                    $maxAssignRole = Role::maxPermission($roleIds);
                    if ($maxAssignRole !== -1) {
                        return $maxAuthRole >= $maxAssignRole;
                    }
                }
                return false;
            case 'DELETE':
                if ($this->isAdmin()) {
                    // Not allowed to delete yourself.
                    if (Auth::user()->id == Route::input('users')) {
                        return false;
                    }

                    // Not allowed to delete a user that has reviewed facility
                    // repository records.
                    if (User::find(Route::input('users'))->frs()->count()) {
                        return false;
                    }

                    $maxAuthRole = Auth::user()->getMaxPermission();
                    $maxUserRole = User::findOrFail(Route::input('users'))
                        ->getMaxPermission();
                    return $maxAuthRole >= $maxUserRole;
                }
                return false;
            default:
                return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $r = [];
        switch ($this->method()) {            
            case 'PUT':
                // If we're updating the user's password, that's all we need to 
                // check for.
                if ($this->instance()->input('password', false)) {
                    $r['password'] = 'required|between:8,16';
                    break;
                }
                // No break.
            case 'POST':
                $r['firstName'] = 'required';
                $r['lastName'] = 'required';
                $r['email'] = 'required|email';
                if ($this->method() == 'POST') {
                    $r['password'] = 'required|between:8,16';
                }
                $r['isActive'] = 'required|digits_between:0,1';
                $r['roles'] = 'required|array';
                $roles = $this->instance()->input('roles', []);
                $length = count($roles);
                for ($i = 0; $i < $length; $i++) {
                    $r["roles.$i"] = 'exists:roles,id';
                }
                break;
        }
        return $r;
    }
}
