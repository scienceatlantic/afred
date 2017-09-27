<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormFieldDateValue extends Model
{
    public function formField()
    {
        return $this->belongsTo('App\FormField');
    }
}
