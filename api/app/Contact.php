<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use \Eloquence\Database\Traits\CamelCaseModel;
    
    public function facilities()
    {
        return $this->belongsTo('App\Facility');
    }
}
