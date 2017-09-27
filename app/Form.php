<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    public function sections()
    {
        return $this->hasMany('App\FormSection');
    }
}
