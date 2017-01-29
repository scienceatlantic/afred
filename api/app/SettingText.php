<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingText extends Model
{
    /**
     * Override default naming scheme.
     *
     * @var string
     */
    protected $table = 'settings_text';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
