<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LanguageCode extends Model
{
    public static function findCode($iso_639_1)
    {
        return self::where('iso_639_1', $iso_639_1)->first();
    }
}
