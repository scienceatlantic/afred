<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use \Eloquence\Database\Traits\CamelCaseModel;
    
    public function facility()
    {
        return $this->belongsTo('App\Facility');
    }
}
