<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormField extends Model
{
    /**
     * Relationship with its form section.
     */
    public function formSection()
    {
        return $this->belongsTo('App\FormSection', 'form_section_id');
    }

    /**
     * Relationship with its entry fields.
     */
    public function entryFields()
    {
        return $this->hasMany('App\EntryField');
    }

    /**
     * Form field's field type.
     */
    public function type()
    {
        return $this->belongsTo('App\FieldType', 'field_type_id');
    }

    /**
     * Relationship with its labelled values (i.e. radio, dropdown, checkbox).
     */
    public function labelledValues()
    {
        return $this->belongsToMany('App\LabelledValue')->withTimestamps();
    }

    /**
     * Scope for whether this field is active (i.e. is in use).
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
