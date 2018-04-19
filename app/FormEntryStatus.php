<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormEntryStatus extends Model
{
    /**
     * Relationship with all its form entries of a particular status.
     */
    public function formEntries()
    {
        return $this->hasMany('App\FormEntry');
    }
    
    /**
     * Find a particular form entry status by using the status's name
     * 
     * @return {FormEntryStatus|null}
     */
    public static function findStatus($status)
    {
        return self::where('name', $status)->first();
    }
}
