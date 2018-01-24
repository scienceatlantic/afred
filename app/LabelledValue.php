<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LabelledValue extends Model
{
    function formFields()
    {
        return $this->belongsToMany('App\FormField')->withTimestamps();
    }    

    function formEntries()
    {
        return $this->belongsToMany('App\FormEntry')->withTimestamps();
    }

    function categories()
    {
        return $this->belongsToMany('App\LabelledValueCategory')
            ->withTimestamps();
    }
}
