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

    /**
     * Relationship with the search section it belongs to.
     */
    public function formSection()
    {
        return $this->belongsTo('App\FormSection');
    }

    /**
     * Relationship with all the search facets it has.
     */
    public function searchFacets()
    {
        return $this->hasMany('App\SearchFacet');
    }

    /**
     * Dynamic attribute containing associative of search blade templates.
     *
     * This is the template that will be used to present the search
     * results/hits.
     *
     * It will return an array that is keyed by the root form section's
     * object key and optionally the target directory's resource folder if the
     * target directory and root form section's directory are not the same.
     *
     * Example:
     *
     * "equipment"           => "<div>...</div>",
     * "equipment-ucalgary"  => "<div>...</div>",
     * "facilities"          => "<div>...</div>",
     * "facilities-ucalgary" => "<div>...</div>"
     */
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

    /**
     * Helper method to retrieve the base (home) WordPress URL.
     *
     * Note: This is being used by the plugin.
     */
    public function getWpBaseUrlAttribute()
    {
        return $this->fresh()->formSection->form->directory->wp_base_url;
    }

    /**
     * Helper method to retrieve the form section's search index.
     *
     * Note: This is being used by the plugin.
     */
    public function getSearchIndexAttribute()
    {
        return $this->fresh()->formSection->search_index;
    }

    /**
     * Helper method to retrieve the form section's object key.
     *
     * Note: this is being used by the plugin.
     */
    public function getFormSectionObjectKeyAttribute()
    {
        return $this->fresh()->formSection->object_key;
    }
}
