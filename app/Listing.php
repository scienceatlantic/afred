<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'root_directory',
        'target_directory',
        'wp_post_url'
    ];

    /**
     * Relationship with the entry section it belongs to.
     */
    public function entrySection()
    {
        return $this->belongsTo('App\EntrySection');
    }

    /**
     * Relationship with the target section it belongs to.
     * 
     * Have a look at the ERD. If a listing is meant to be published to multiple
     * directories, it is done via this relationship.
     */
    public function formSection()
    {
        return $this->belongsTo('App\FormSection');
    }

    /**
     * Find a listing via its published entry section ID and target form section
     * ID.
     * 
     * @param {number} $publishedEntrySectionId 
     * @param {number} $targetFormSectionId
     */
    public static function findListing(
        $publishedEntrySectionId,
        $targetFormSectionId
    ) {
        return self
            ::where('published_entry_section_id', $publishedEntrySectionId)
            ->where('form_section_id', $targetFormSectionId)
            ->first();
    }

    /**
     * Dynamic attribute that contains the full path (full path + filename) of
     * the template that should be used when viewing this published listing.
     */
    public function getTemplateAttribute()
    {
        $listing = self
            ::with([
                'entrySection.formSection.form.directory',
                'formSection'
            ])
            ->find($this->id);

        $rootFormSection = $listing->entrySection->formSection;
        $targetFormSection = $listing->formSection;

        // Get the target directory. It will be an empty string if this listing
        // belongs to a form section
        $directory = '';
        if ($rootFormSection->id !== $targetFormSection->id) {
            $directory = '-' 
                . $targetFormSection
                ->form
                ->directory
                ->resource_folder;
        }

        // Format: 
        // wp
        // .<directory-resource-folder>
        // .form-entries
        // .<form-resource-folder>
        // .<root-form-section-object-key>
        // (-target-directory-object-key?)
        return 'wp.'
            . $rootFormSection
                ->form
                ->directory
                ->resource_folder
            . '.form-entries.'
            . $rootFormSection
                ->form
                ->resource_folder
            . '.'
            . $rootFormSection
                ->object_key
            . $directory;
    }

    /**
     * Dynamic attribute that is meant to identify the right search template
     * that should be used for this listing's entry section.
     */
    public function getSearchTemplateObjectKeyAttribute()
    {
        $template = $this->template;
        $index = strrpos($template, '.');

        // Format:
        // <root-form-section-object-key>(-target-directory-resource-folder?)
        // Examples:
        // (1) facilities
        // (2) facilities-ucalgary
        return substr($this->template, $index + 1);
    }

    /**
     * A helper method to get the root directory (i.e. the entry section's
     * form entry's directory).
     */
    public function getRootDirectoryAttribute()
    {
        return $this->fresh()->entrySection->formEntry->form->directory;        
    }

    /**
     * A helper method to get the target directory (i.e. the target form
     * entry's directory).
     */
    public function getTargetDirectoryAttribute()
    {
        return $this->fresh()->formSection->form->directory;
    }

    /**
     * WordPress page URL of the published listing
     * 
     * This will be attached to the Algolia search object so that we can link
     * a search result with the page it belongs to.
     */
    public function getWpPostUrlAttribute()
    {
        if (!$this->wp_post_id) {
            return null;
        }

        return $this->targetDirectory->wp_base_url . '?p=' . $this->wp_post_id;
    }
}
