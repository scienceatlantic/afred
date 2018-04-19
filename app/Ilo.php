<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ilo extends Model
{
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'name'
    ];

    /**
     * Relationship with the labelled value it belongs to.
     */
    public function labelledValue()
    {
        return $this->belongsTo('App\LabelledValue');
    }

    /**
     * Dynamic attribute that returns the ILO's full name.
     */
    public function getNameAttribute()
    {
        if ($name = trim($this->first_name . ' ' . $this->last_name)) {
            return $name;
        }
        return null;
    }
}
