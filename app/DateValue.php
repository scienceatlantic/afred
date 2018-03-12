<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DateValue extends Model
{
    public function formField()
    {
        return $this->belongsTo('App\FormField');
    }

    public function entryField()
    {
        return $this->belongsTo('App\EntryField');
    }    
}
