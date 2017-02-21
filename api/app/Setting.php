<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends SettingBase
{
    /**
     * Relationship between a setting and its text value (if available).
     */
    public function text()
    {
        return $this->hasOne('App\SettingText', 'settingId');
    }

    public function authRoleOnGet()
    {
        return $this->belongsTo('App\Role', 'minAuthRoleOnGet');
    }

    public function authRoleOnPut()
    {
        return $this->belongsTo('App\Role', 'minAuthRoleOnPut');
    }
}
