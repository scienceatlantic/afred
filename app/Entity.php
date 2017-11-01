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

        $formEntry = $e->formEntries->first();
        foreach($formEntry->sections as $section) {
            $array[$section->object_key] = [];
            $sectTotal = $formEntry->getSectionTotal($section->id);
            
            for ($sectIndex = 1; $sectIndex <= $sectTotal; $sectIndex++) {
                $fields = [];
                
                foreach($section->fields as $field) {
                    $value = null;
     
                    switch ($field->type->name) {
                        case 'plaintext':
                        case 'richtext':
                            $valueObject = $field->textValues
                                ->where('section_repeat_index', $sectIndex)
                                ->first();
                            if ($valueObject) {
                                $value = $valueObject->value;
                            }
                            break;
                        case 'checkbox':
                            $value = $field->labelledValues->toArray();
                            break;
                        case 'radio':
                        case 'dropdown':
                            $valueObject = $field->labelledValues
                                ->where('section_repeat_index', $sectIndex)
                                ->first();                        
                            if ($valueObject) {
                                $value = $valueObject->form_label;
                            }
                            break;
                        default:
                            $valueMethod = $field->type->name . 'Values';
                            $valueObject = $field->$valueMethod
                                ->where('section_repeat_index', $sectIndex)
                                ->first();                              
                            if ($valueObject) {
                                $value = $valueObject->value;
                            }
                    }
                    
                    $fields[$field->object_key] = $value;
                }

                array_push($array[$section->object_key], $fields);
            }
        }

        return $array;
    }
}
