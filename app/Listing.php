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

    public function entrySection()
    {
        return $this->belongsTo('App\EntrySection');
    }

    /**
     * Target form section
     */
    public function formSection()
    {
        return $this->belongsTo('App\FormSection');
    }

    public static function findListing(
        $publishedEntrySectionId,
        $targetFormSectionId
    ) {
        return self
            ::where('published_entry_section_id', $publishedEntrySectionId)
            ->where('form_section_id', $targetFormSectionId)
            ->first();
    }

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

        $directory = '';
        if ($rootFormSection->id !== $targetFormSection->id) {
            $directory = '-' 
                . $targetFormSection
                ->form
                ->directory
                ->resource_folder;
        }

        //wp.<dir>.form-entries.<form>.<rootFormSection>(-targetDirectory?)
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

    public function getRootDirectoryAttribute()
    {
        return $this->fresh()->entrySection->formEntry->form->directory;        
    }

    public function getTargetDirectoryAttribute()
    {
        return $this->fresh()->formSection->form->directory;
    }

    public function getWpPostUrlAttribute()
    {
        if (!$this->wp_post_id) {
            return null;
        }

        return $this->target_directory->wp_base_url . '?p=' . $this->wp_post_id;
    }
}
