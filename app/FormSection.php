<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormSection extends Model
{
    /**
     * Relationship with the form it belongs to.
     */
    public function form()
    {
        return $this->belongsTo('App\Form');
    }

    /**
     * Relationship with all the form fields it has.
     */
    public function formFields()
    {
        return $this->hasMany('App\FormField');
    }

    /**
     * Relationship with all the other form sections that should be included
     * in the Algolia Search Object that is created should an entry section
     * attached to form section be published.
     */
    public function formSectionsIncludedInSearch()
    {
        return $this->belongsToMany(
            'App\FormSection',
            'form_section_form_section',
            'root_form_section_id',
            'target_form_section_id'
        )->withTimestamps();
    }

    /**
     * Scope for active form sections.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
