<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    public function entrySection()
    {
        return $this->belongsTo('App\EntrySection');
    }

    public function formSection()
    {
        return $this->belongsTo('App\FormSection');
    }
}
