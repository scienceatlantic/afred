<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LabelledValue extends Model
{
    /**
     * Relationship with the form fields that utilise it
     */
    public function formFields()
    {
        return $this->belongsToMany('App\FormField')->withTimestamps();
    }

    /**
     * Relationship with all the labelled value categories it belongs to.
     */
    public function categories()
    {
        return $this
            ->belongsToMany('App\LabelledValueCategory')
            ->withTimestamps();
    }

    /**
     * Relationship with an ILO.
     */
    public function ilo()
    {
        return $this->hasOne('App\Ilo');
    }

    /**
     * Find a labelled value by its label
     * 
     * @param {string} $label
     */
    public static function findLabel($label)
    {
        return self::where('label', $label)->first();
    }
}
