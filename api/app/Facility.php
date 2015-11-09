<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    public function institution()
    {
        return $this->belongsTo('App\Institution');
    }
    
    public function province()
    {
        return $this->belongsTo('App\Province');
    }
    
    public function people()
    {
        return $this->belongsToMany('App\People');
    }
    
    public function equipment()
    {
        return $this->hasMany('App\Equipment');
    }
}
