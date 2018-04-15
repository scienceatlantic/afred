<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use View;

class SearchSection extends Model
{
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'templates',
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

    public function getTemplatesAttribute()
    {
        $templates = [];

        $section = $this->fresh();

        $form = $section
            ->formSection
            ->form
            ->resource_folder;

        $dir = $section
            ->formSection
            ->form
            ->directory
            ->resource_folder;

        $path = resource_path("views/wp/{$dir}/search-sections/$form/");
        $bladePath = "wp/{$dir}/search-sections/$form/";

        $files = array_diff(scandir($path), ['.', '..']);
        
        foreach($files as $file) {
            $key = str_replace('.blade.php', '', $file);
            $templates[$key] = View::make($bladePath . $key)->render();
        }

        return $templates;
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
