<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LabelledValue extends Model
{
    function formFields()
    {
        return $this->belongsToMany('App\FormField');
    }    

    function formEntries()
    {
        return $this->belongsToMany('App\FormEntry');
    }

    function categories()
    {
        return $this->belongsToMany('App\LabelledValueCategory');
    }
}
