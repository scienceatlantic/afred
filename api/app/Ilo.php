<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ilo extends Model
{
    use \Eloquence\Database\Traits\CamelCaseModel;
    
    public function institution()
    {
        return $this->belongsTo('App\Institution');
    }
}
