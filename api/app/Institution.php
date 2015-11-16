<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    use \Eloquence\Database\Traits\CamelCaseModel;
    
    public function ilo()
    {
        return $this->hasOne('App\Institution');
    }
    
    public function facilities() {
        return $this->hasMany('App\Facility');
    }
}
