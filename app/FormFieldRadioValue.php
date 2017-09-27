<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormFieldRadioValue extends Model
{
    function formFields()
    {
        return $this->belongsToMany('App\FormField');
    }

    function formEntries()
    {
        return $this->belongsToMany('App\FormEntry');
    }
}
