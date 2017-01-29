<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends SettingBase
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Relationship between a user setting and the user it belongs to.
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'userId');
    }

    /**
     * Relationship between a user setting and its text value (if available).
     */
    public function text()
    {
        return $this->hasOne('App\UserSettingText', 'userSettingId');
    }
}
