<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LabelledValueCategory extends Model
{
    function values()
    {
        return $this->belongsToMany('App\LabelledValue');
    }
}
