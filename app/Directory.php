<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Directory extends Model
{
    public function form()
    {
        return $this->hasOne('App\Form');
    }

    public function entities()
    {
        return $this->belongsToMany('App\Entity');
    }
}
