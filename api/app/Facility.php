<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use \Eloquence\Database\Traits\CamelCaseModel;
    
    public function institution()
    {
        return $this->belongsTo('App\Institution');
    }
    
    public function province()
    {
        return $this->belongsTo('App\Province');
    }
    
    public function contacts()
    {
        return $this->hasMany('App\Contact');
    }
    
    public function equipment()
    {
        return $this->hasMany('App\Equipment');
    }
}
