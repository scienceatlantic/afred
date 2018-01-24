<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FieldType extends Model
{
    public function formFields()
    {
        return $this->hasMany('App\FormField');
    }
}
