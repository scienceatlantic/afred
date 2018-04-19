<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LabelledValueCategory extends Model
{
    /**
     * Relationship with all the labelled values it has.
     */
    public function values()
    {
        return $this->belongsToMany('App\LabelledValue')->withTimestamps();
    }

    /**
     * Find a labelled value category by name
     * 
     * @param {string} $name Name of category
     * @param {number=null} $languageCodeId ID of language code the category
     *     belongs to. Default is English.
     */
    public static function findCategory($name, $languageCodeId = null)
    {
        // Default language code is 'en'.
        if (!($languageCode = LanguageCode::find($languageCodeId))) {
            $languageCode = LanguageCode::findCode('en');
        }

        return self
            ::where('language_code_id', $languageCode->id)
            ->where('name', $name)
            ->first();
    }
}
