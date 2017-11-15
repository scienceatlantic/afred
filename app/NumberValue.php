<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NumberValue extends Model
{
    public function formField()
    {
        return $this->belongsTo('App\FormField');
    }

    public function formEntry()
    {
        return $this->belongsTo('App\FormEntry');
    }    
}
