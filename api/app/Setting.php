<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends SettingBase
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['dateCreated',
                        'dateUpdated'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Relationship between a setting and its text value (if available).
     */
    public function text()
    {
        return $this->hasOne('App\SettingText', 'settingId');
    }
}
