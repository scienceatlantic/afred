<?php

namespace App;

use App\FormEntry;
use App\FormSection;
use DB;
use Illuminate\Database\Eloquent\Model;
use Log;

class Resource extends Model
{
    public function formEntries()
    {
        return $this->hasMany('App\FormEntry');
    }

    public function format(
        $formEntryStatus = 'Published',
        $type = 'Search'
    ) {
        $this->formatted = null;

        $status = FormEntryStatus::where('name', $formEntryStatus)->first();
        if (!$status) {
            Log::error('Form entry status not found');
            return;
        }

        $formEntry = $this->formEntries()
            ->where('form_entry_status_id', $status->id)
            ->first();
        if (!$formEntry) {
            Log::error('Form entry not found');
            return;
        }
        
        $data = [
            'directory_id'  => [1,2,3],
            'resource_id'     => $this->id,
            'form_id'       => '',
            'form_entry_id' => $formEntry->id,
            'status'        => $status->name,
            'sections'      => [],
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at
        ];
        foreach($formEntry->sections as $section) {
            $data['sections'][$section->object_key] = [];
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
                                ->whereIn('id', $ids);
                            break;
                        case 'radio':
                        case 'dropdown':
                            $ids = DB::table('form_entry_labelled_value')
                                ->where('form_entry_id', $formEntry->id)
                                ->where('section_repeat_index', $sectIndex)
                                ->pluck('labelled_value_id');
                            $valueArray = $field->labelledValues
                                ->whereIn('id', $ids);
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

                array_push($data['sections'][$section->object_key], $fields);
            }
        }

        $this->formatted = $data;
    }
}
