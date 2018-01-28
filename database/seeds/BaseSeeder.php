<?php

use App\LabelledValue;
use App\LabelledValueCategory;
use App\LanguageCode;
use Illuminate\Database\Seeder;

class BaseSeeder extends Seeder
{
    public static function saveModels($modelClassName, $modelValues)
    {
        $models = [];
        $modelClass = 'App\\' . $modelClassName;

        foreach($modelValues as $modelValue) {
            $model = new $modelClass();
            foreach($modelValue as $property => $value) {
                $model->$property = $value;
            }
            $model->save();

            array_push($models, $model);
        }

        return $models;
    }

    public static function saveLabelledValues($values, $categoryIds = [])
    {
        $labelledValues = [];

        foreach($values as $value) {
            // Check if the value doesn't already exist.
            $labelledValue = LabelledValue::where('label', $value['label'])
                ->first();

            if (!$labelledValue) {
                $labelledValue = new LabelledValue();
                $labelledValue->label = $value['label'];
                $labelledValue->save();
            }

            $labelledValue->categories()->attach($categoryIds);

            array_push($labelledValues, $labelledValue);
        }

        return $labelledValues;
    }

    public static function saveCategory($name, $languageCodeId = null)
    {
        // Default language code is 'en'.
        if (!($languageCode = LanguageCode::find($languageCodeId))) {
            $languageCode = LanguageCode::where('iso_639_1', 'en')->first();
        }

        // Check if the category doesn't already exist.
        $category = LabelledValueCategory
            ::where('name', $name)
            ->where('language_code_id', $languageCode->id)
            ->first();
        if ($category) {
            return $category;
        }

        $category = new LabelledValueCategory();
        $category->language_code_id = $languageCode->id;
        $category->name = $name;
        $category->save();

        return $category;
    }
}
