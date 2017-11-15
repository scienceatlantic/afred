<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Directory extends Model
{
    public function forms()
    {
        return $this->hasMany('App\Form');
    }

    public function formEntries()
    {
        return $this->belongsToMany('App\FormEntries');
    }
}
