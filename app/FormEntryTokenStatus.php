<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormEntryTokenStatus extends Model
{
    public static function findStatus($name)
    {
        return self::where('name', $name)->first();
    }
}
