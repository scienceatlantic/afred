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

    public static function toTemplateArray($id)
    {
        $e = self::with('formEntries')->findOrFail($id);

        $array = [
            'id'         => $e->id,
            'created_at' => $e->created_at,
            'updated_at' => $e->updated_at
        ];

        foreach($e->formEntries->first()->sections as $section) {
            $array[$section->object_key] = [];
            $fields = [];

            foreach($section->fields as $field) {
                $value = null;

                switch ($field->type->name) {
                    case 'plaintext':
                    case 'richtext':
                        if ($valueObject = $field->textValues->first()) {
                            $value = $valueObject->value;
                        }
                        break;
                    case 'checkbox':
                        $value = $field->labelledValues->toArray();
                        break;
                    case 'radio':
                    case 'dropdown':
                        if ($valueObject = $field->labelledValues->first()) {
                            $value = $valueObject->form_label;
                        }
                        break;
                    default:
                        $valueMethod = $field->type->name . 'Values';
                        if ($valueObject = $field->$valueMethod->first()) {
                            $value = $valueObject->value;
                        }
                }
                
                $fields[$field->object_key] = $value;
            }

            array_push($array[$section->object_key], $fields);
        }

        return $array;
    }
}
