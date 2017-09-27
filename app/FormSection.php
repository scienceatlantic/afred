<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormSection extends Model
{
    public function fields()
    {
        return $this->hasMany('App\FormField');
    }
}
