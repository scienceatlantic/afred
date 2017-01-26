<?php

namespace App\Http\Requests;

// Misc. 
use Auth;

// Models. 
use App\Role;
use App\Setting;

class SettingRequest extends SettingBaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $column = null;
        if ($name = $this->instance()->input('name')) {
            $name = is_array($name) ? $name : [$name];
        }
        
        switch ($this->method()) {
            case 'GET':
                if ($name) {
                    $column = 'settings.minAuthRoleOnGet';
                    break;
                }
                return $this->isSuperAdmin();
            case 'PUT':
                if ($name) {
                    $column = 'settings.minAuthRoleOnPut';
                    break;
                }
                return $this->isSuperAdmin();
            default:
                return false; 
        }
        
        $maxPermissionLevel = Role::join('settings', $column, '=', 'roles.id')
            ->whereIn('settings.name', $name)
            ->max('roles.permission');

        if ($maxPermissionLevel) {
            if (Auth::check() && $user = Auth::user()) {
                return $user->getMaxPermission() >= $maxPermissionLevel;
            }
            return false;
        }
        return true;
    }
}
