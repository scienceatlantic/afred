<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormField extends Model
{
    public function formSection()
    {
        return $this->belongsTo('App\FormSection', 'form_section_id');
    }

    public function entryFields()
    {
        return $this->hasMany('App\EntryField');
    }

    public function type()
    {
        return $this->belongsTo('App\FieldType', 'field_type_id');
    }

    public function labelledValues()
    {
        return $this->belongsToMany('App\LabelledValue')->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
