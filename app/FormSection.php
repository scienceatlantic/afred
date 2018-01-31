<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormSection extends Model
{
    public function form()
    {
        return $this->belongsTo('App\Form');
    }

    public function formFields()
    {
        return $this->hasMany('App\FormField');
    }
}
