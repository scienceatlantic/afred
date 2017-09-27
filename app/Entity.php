<?php

namespace App;

use App\FormEntry;
use App\FormSection;
use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    public function directories()
    {
        return $this->belongsToMany('App\Directory');
    }
    
    public function formEntries()
    {
        return $this->hasMany('App\FormEntry');
    }
}
