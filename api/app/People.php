<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class People extends Model
{
    public function facilities()
    {
        return $this->hasMany('App\Facility');
    }
}
