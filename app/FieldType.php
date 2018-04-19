<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FieldType extends Model
{
    /**
     * Relationship with all the form fields of a particular type.
     */
    public function formFields()
    {
        return $this->hasMany('App\FormField');
    }

    /**
     * Find a particular field type record
     * 
     * @param {string} $name Name of the field type (e.g. String, Date, etc.)
     */
    public static function findType($name)
    {
        return self::where('name', $name)->first();
    }
}
