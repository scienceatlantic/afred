<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SearchSection extends Model
{
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'wp_base_url',
        'search_index',
        'form_section_object_key'
    ];

    public function formSection()
    {
        return $this->belongsTo('App\FormSection');
    }

    public function searchFacets()
    {
        return $this->hasMany('App\SearchFacet');
    }

    public function getWpBaseUrlAttribute()
    {
        return $this->fresh()->formSection->form->directory->wp_base_url;
    }

    public function getSearchIndexAttribute()
    {
        return $this->fresh()->formSection->search_index;
    }

    public function getFormSectionObjectKeyAttribute()
    {
        return $this->fresh()->formSection->object_key;
    }
}
