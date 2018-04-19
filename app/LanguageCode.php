<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LanguageCode extends Model
{
    /**
     * Find a language code by its two-letter code (e.g. 'en')
     * 
     * @param {string} $iso_639_1
     */
    public static function findCode($iso_639_1)
    {
        return self::where('iso_639_1', $iso_639_1)->first();
    }
}
