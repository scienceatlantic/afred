<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormFieldTextValue extends Model
{
    public function formField()
    {
        return $this->belongsTo('App\FormField');
    }
}
