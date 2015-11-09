<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    public function facility()
    {
        return $this->belongsTo('App\Facility');
    }
}
