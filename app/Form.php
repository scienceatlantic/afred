<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    public function directory()
    {
        return $this->belongsTo('App\Directory');
    }

    public function compatibleForms()
    {
        return $this->belongsToMany(
                'App\Form',
                'form_form', 
                'form_id', 
                'compatible_form_id'
            )
            ->withTimestamps();
    }

    public function formSections()
    {
        return $this->hasMany('App\FormSection');
    }

    public function formEntries()
    {
        return $this->hasMany('App\FormEntry');
    }

    public function searchSections()
    {
        return $this->hasManyThrough('App\SearchSection', 'App\FormSection');
    }
}
