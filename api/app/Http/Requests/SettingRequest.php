<?php

namespace App\Http\Requests;

// Misc. 
use Auth;
use Route;

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
        switch ($this->method()) {
            case 'GET':
                if ($name = $this->instance()->input('name')) {
                    $name = is_array($name) ? $name : [$name];

                    $settings = Setting::whereIn('name', $name)->get();
                    if ($settings->count() !== count($name)) {
                        abort(404);
                    }

                    $maxRequiredRole = Role::join('settings', 
                        'settings.minAuthRoleOnGet', '=', 'roles.id')
                        ->whereIn('settings.name', $name)
                        ->max('roles.permission');

                    if (!$maxRequiredRole) {
                        return true;
                    } else if (!Auth::check()) {
                        return false;
                    }
                    
                    return Auth::user()->getMaxPermission() >= $maxRequiredRole;
                }
                return $this->isSuperAdmin();
            case 'PUT':
                if (!Auth::check()) {
                    return false;
                }

                $setting = Setting::findOrFail(Route::input('setting'));

                return Auth::user()->getMaxPermission() >= $setting
                    ->authRoleOnPut->permission;
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
        if ($this->method() !== 'PUT') {
            return [];
        }

        $type = Setting::findOrFail(Route::input('setting'))->type;
        return parent::applyRules($type);
    }
}
