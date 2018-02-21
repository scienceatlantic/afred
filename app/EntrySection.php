<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EntrySection extends Model
{
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'is_public',
        'title'
    ];

    public function formEntry()
    {
        return $this->belongsTo('App\FormEntry');
    }

    public function formSection()
    {
        return $this->belongsTo('App\FormSection');
    }

    public function entryFields()
    {
        return $this->hasMany('App\EntryField');
    }

    public function listings()
    {
        return $this->hasMany('App\Listing');
    }

    public function getIsPublicAttribute()
    {
        $formFieldIds = $this
            ->entryFields()
            ->pluck('form_field_id')
            ->toArray();

        $formField = FormField
            ::whereIn('id', $formFieldIds)
            ->where('object_key', '_is_public')
            ->first();

        // If property doesn't exist on the form, return true.
        if (!$formField) {
            return true;
        }

        $entryField = $this
            ->entryFields()
            ->where('id', $formField->id)
            ->first();

        // If property doesn't exist in the fieldset, return true.
        if (!$entryField) {
            return true;
        }

        // Property exists, return flag's value.
        return $entryField->value;
    }

    public function getTitleAttribute()
    {
        $key = $this
            ->formSection()
            ->first()
            ->field_resource_title_object_key;
        
        $fields = $this
            ->entryFields()
            ->get()
            ->keyBy('formField.object_key');
        
        return isset($fields[$key]) ? $fields[$key]->value : null;
    }

    public function getFieldValue($objectKey)
    {
        $formFieldIds = FormField
            ::where('object_key', $objectKey)
            ->pluck('id');

        $field = $this
            ->entryFields()
            ->whereIn('form_field_id', $formFieldIds)
            ->first();

        return $field ? $field->value : null;
    }    
}
