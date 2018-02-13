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

    public function getSearchIndexAttribute()
    {
        return $this->formSection()->first()->search_index;
    }

    public function getFormSectionObjectKeyAttribute()
    {
        return $this->formSection()->first()->object_key;
    }
}
