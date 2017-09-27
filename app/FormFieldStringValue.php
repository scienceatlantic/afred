<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormFieldStringValue extends Model
{
    public function formField()
    {
        return $this->belongsTo('App\FormField');
    }
}
