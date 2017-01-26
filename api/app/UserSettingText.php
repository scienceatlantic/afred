<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSettingText extends Model
{
    /**
     * Override default naming scheme.
     *
     * @var string
     */
    protected $table = 'user_settings_text';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
