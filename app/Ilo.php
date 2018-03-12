<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ilo extends Model
{
    public function labelledValue()
    {
        return $this->belongsTo('App\LabelledValue');
    }

    public function getNameAttribute()
    {
        if ($name = trim($this->first_name . ' ' . $this->last_name)) {
            return $name;
        }
        return null;
    }
}
