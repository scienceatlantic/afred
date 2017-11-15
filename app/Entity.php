<?php

namespace App;

use App\FormEntry;
use App\FormSection;
use DB;
use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{   
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
                            $ids = DB::table('form_entry_labelled_value')
                                ->where('form_entry_id', $formEntry->id)
                                ->where('section_repeat_index', $sectIndex)
                                ->pluck('labelled_value_id');
                            $value = $field->labelledValues
                                ->whereIn('id', $ids)
                                ->pluck('label');
                            break;
                        case 'radio':
                        case 'dropdown':
                            $ids = DB::table('form_entry_labelled_value')
                                ->where('form_entry_id', $formEntry->id)
                                ->where('section_repeat_index', $sectIndex)
                                ->pluck('labelled_value_id');
                            $valueArray = $field->labelledValues
                                ->whereIn('id', $ids)
                                ->pluck('label');
                            if (count($valueArray)) {
                                $value = $valueArray[0];
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
