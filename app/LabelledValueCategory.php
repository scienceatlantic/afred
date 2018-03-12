<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LabelledValueCategory extends Model
{
    public function values()
    {
        return $this->belongsToMany('App\LabelledValue')->withTimestamps();
    }

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
