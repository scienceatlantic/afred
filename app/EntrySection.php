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
        'title'
    ];

    /**
     * Relationship with the form entry it belongs to.
     */
    public function formEntry()
    {
        return $this->belongsTo('App\FormEntry');
    }

    /**
     * Relationship with the form section it belongs to.
     */
    public function formSection()
    {
        return $this->belongsTo('App\FormSection');
    }

    /**
     * Relationship with all the entry fields it owns.
     */
    public function entryFields()
    {
        return $this->hasMany('App\EntryField');
    }

    /**
     * Relationship with all the listings it owns.
     */
    public function listings()
    {
        return $this->hasMany('App\Listing');
    }

    /**
     * Get the entry section's title.
     * 
     * It uses the `field_resource_title_object_key` property of  the form
     * section this entry section is attached to to determine how this entry
     * section should be titled.
     */
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

    /**
     * Will return the value entry field value based on the object key provided
     * 
     * @param {string} $objectKey Form field's object key
     * @return {mixed}
     */
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
