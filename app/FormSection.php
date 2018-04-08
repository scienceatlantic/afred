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

    public function formSectionsIncludedInSearch()
    {
        return $this->belongsToMany(
            'App\FormSection',
            'form_section_form_section',
            'root_form_section_id',
            'target_form_section_id'
        )->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
