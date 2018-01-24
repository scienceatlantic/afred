<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormEntryStatus extends Model
{
    public function formEntries()
    {
        return $this->hasMany('App\FormEntry');
    }
    
    public static function findStatus($status)
    {
        return self::where('name', $status)->first();
    }
}
