<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LabelledValue extends Model
{
    public function formFields()
    {
        return $this->belongsToMany('App\FormField')->withTimestamps();
    }    

    public function formEntries()
    {
        return $this->belongsToMany('App\FormEntry')->withTimestamps();
    }

    public function categories()
    {
        return $this
            ->belongsToMany('App\LabelledValueCategory')
            ->withTimestamps();
    }
}
