<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormSection extends Model
{
    public function form()
    {
        return $this->belongsTo('App\Form');
    }

    public function compatibleFormSections()
    {
        return $this->belongsToMany
            (
                'App\FormSection',
                'form_section_form_section',
                'form_section_id', 
                'compatible_form_section_id'
            )
            ->withPivot('resource_template', 'search_index')
            ->withTimestamps();
    }

    public function formFields()
    {
        return $this->hasMany('App\FormField');
    }

    public function getSearchIndices()
    {
        $searchIndices = [];

        foreach($this->compatibleFormSections as $compatibleFormSection) {
            $searchIndex = $compatibleFormSection->pivot->search_index;
            if ($searchIndex) {
                array_push($searchIndices, $searchIndex);
            }
        }
        
        return $searchIndices;    
    }

    public function getResourceTemplates()
    {
        $resourceTemplates = [];

        foreach($this->compatibleFormSections as $compatibleFormSection) {
            $resourceTemplate = $compatibleFormSection->pivot->resource_template;
            if ($resourceTemplate) {
                array_push($resourceTemplates, $resourceTemplate);
            }
        }
        
        return $resourceTemplates;  
    }
}
