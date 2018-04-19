<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormEntryTokenStatus extends Model
{
    /**
     * Find a form entry token status by the status's name
     */
    public static function findStatus($name)
    {
        return self::where('name', $name)->first();
    }
}
