<?php

namespace App;

use App\Form;
use App\FormEntryStatus as Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class FormEntry extends Model
{
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'data'
    ];

    /** 
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'cache',
        'token'
    ];

    public function status()
    {
        return $this->belongsTo('App\FormEntryStatus', 'form_entry_status_id');
    }

    public function form()
    {
        return $this->belongsTo('App\Form');
    }

    public function formsAttachedTo()
    {
        return $this->belongsToMany('App\Form')->withTimestamps();
    }

    public function entrySections()
    {
        return $this->hasMany('App\EntrySection');
    }

    public function author()
    {
        return $this->belongsTo('App\User');
    }

    public function reviewer()
    {
        return $this->belongsTo('App\User');
    }

    public function users()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }

    public function scopePublished($query)
    {
        $status = Status::findStatus('Published');
        return $query->where('form_entry_status_id', $status->id);
    }    

    public static function submitFormEntry(
        Request $request,
        Form $rootForm,
        self $oldFormEntry = null
    ) {
        $isEdit = (bool) $oldFormEntry;

        // Get submitted status.
        $submittedStatus = Status::findStatus('Submitted');

        //
        if ($isEdit) {
            $resourceId = $oldFormEntry->resource_id;
        } else {
            $resourceId = (self::max('resource_id') + 1);
        }
        
        // Create new form entry
        $formEntry = new self();
        $formEntry->resource_id = $resourceId;
        $formEntry->form_id = $rootForm->id;
        $formEntry->form_entry_status_id = $submittedStatus->id;
        $formEntry->isEdit = $isEdit;
        $formEntry->save();

        // Attach compatible form (that were selected).
        $compatibleFormIds = $rootForm
            ->compatibleForms()
            ->pluck('compatible_form_id')
            ->toArray();
        $formsAttachedToIds = array_where($request->forms,
            function($formId) use ($compatibleFormIds) {
                return array_search($formId, $compatibleFormIds) !== false;
            }
        );
        $formEntry->formsAttachedTo()->attach($formsAttachedToIds);

        // Attach fields and values.
        foreach($request->sections as $section => $fieldsets) {
            // Check that the section exists, otherwise skip.
            $rootFormSection = $rootForm
                ->formSections()
                ->where('object_key', $section)
                ->first();
            if (!$rootFormSection) {
                continue;
            }

            // Create entry sections for each fieldset.
            foreach($fieldsets as $fieldset) {
                // TODO
                $meta = isset($fieldset['_meta']) ?  $fieldset['_meta'] : [];

                // Create new entry section.
                $entrySection = new EntrySection();
                $entrySection->form_entry_id = $formEntry->id;
                $entrySection->form_section_id = $rootFormSection->id;
                if ($isEdit && isset($meta['published_entry_section_id'])) {
                    $entrySection->published_entry_section_id
                        = $meta['published_entry_section_id'];
                }
                $entrySection->save();

                // Create fields.
                foreach($rootFormSection->formFields as $rootFormField) {
                    // Get value of field if it exists, otherwise skip.
                    if (isset($fieldset[$rootFormField->object_key])) {
                        $value = $fieldset[$rootFormField->object_key];
                    } else {
                        continue;
                    }

                    // Create field.
                    $entryField = new EntryField();
                    $entryField->entry_section_id = $entrySection->id;
                    $entryField->form_field_id = $rootFormField->id;
                    $entryField->save();

                    // Set value.
                    $entryField->setValue($value);
                }
            }
        }

        // Update cache.
        $formEntry->cache = $formEntry->data;
        $formEntry->update();
        
        return $formEntry;
    }

    public static function publishFormEntry(self $formEntry) {
        // Determine if publishing an edited form entry. If it is an edit, get
        // the currently published form entry (i.e. `$oldFormEntry`).
        $oldFormEntry = null;
        if ($formEntry->isEdit) {
            $oldFormEntry = self
                ::where('resource_id', $formEntry->resource_id)
                ->published()
                ->first();
            
            if (!$oldFormEntry) {
                $msg = 'Trying to publish an edited form entry but currently '
                     . 'published form entry was not found';
                Log::error($msg, [
                    '$formEntry'    => $formEntry->toArray(),
                    '$oldFormEntry' => 'null'
                ]);
                abort(500);
            }
        }

        // Update status.
        $revisionStatus = Status::findStatus('Revision');
        $publishedStatus = Status::findStatus('Published');
        if ($formEntry->isEdit) {
            $oldFormEntry->form_entry_status_id = $revisionStatus->id;
            $oldFormEntry->update();
        }
        $formEntry->form_entry_status_id = $publishedStatus->id;
        $formEntry->update();

        // Get the root form (i.e. form that generated this form entry).
        $rootForm = $formEntry->form;

        // Get IDs of all forms this form entry is attached to (in order for us
        // to get all the form sections that it is attached to).
        $formsAttachedToIds = $formEntry->formsAttachedTo()->pluck('form_id');

        // Loop through each root form section in order to create the required
        // listings.
        foreach($rootForm->formSections as $rootFormSection) {
            // Skip form section if not a resource
            if (!$rootFormSection->is_resource) {
                continue;
            }

            // Get all entry sections of `$rootFormSection` type.
            $entrySections = $formEntry
                ->entrySections()
                ->where('form_section_id', $rootFormSection->id)
                ->get();

            // Get all form sections that will be attached to this set of entry
            // sections.
            $formSections = FormSection
                ::whereIn('form_id', $formsAttachedToIds)
                ->where('object_key', $rootFormSection->object_key)
                ->get();

            // Loop through each entry section.
            foreach($entrySections as $entrySection) {
                // Skip entry section if it's not meant to be public.
                if (!$entrySection->isPublic) {
                    $entrySection->published_entry_section_id = null;
                    $entrySection->update();
                    continue;
                }

                // Get the entry section id that will be used by the listing
                // when publishing to WordPress and Algolia.
                if (!$entrySection->published_entry_section_id) {
                    $entrySection->published_entry_section_id = $entrySection
                        ->id;
                    $entrySection->update();
                }

                // Loop through each form section that the entry section will be
                // attached to.
                foreach($formSections as $formSection) {
                    // Determine if a listing for this combination (entry
                    // section and form section) already exists.
                    $listing = Listing
                        ::where(
                            'published_entry_section_id',
                            $entrySection->published_entry_section_id
                        )
                        ->where('form_section_id', $formSection->id)
                        ->first();

                    // If it exists, update it with the new entry section's id.
                    if ($listing) {
                        $listing->entry_section_id = $entrySection->id;
                        $listing->update();
                    } 
                    // If it doesn't, create a new listing.
                    else {
                        $listing = new Listing();
                        $listing->entry_section_id = $entrySection->id;
                        $listing->form_section_id = $formSection->id;
                        $listing->published_entry_section_id = $entrySection
                            ->published_entry_section_id;
                        $listing->save();
                    }                    
                }

                // Publish each listing to WordPress.
                foreach($entrySection->listings as $listing) {
                    // Add to WordPress.
                    $response = WordPress::addListing($listing);
                    $wpPost = json_decode($response->getBody(), true);

                    $listing->wp_post_id = $wpPost['id'];
                    $listing->wp_slug = $wpPost['slug'];
                    $listing->update();
                }
            }
        }

        // Add all listings to Algolia. This has to be done after the listings
        // have been added to WordPress. If not, we wouldn't have a post ID for
        // each entry section.
        Algolia::addFormEntry($formEntry);

        // Delete any old WordPress resources or Algolia objects from the old
        // form entry (that were not carried over to the new form entry).
        if ($oldFormEntry) {
            foreach($oldFormEntry->entrySections as $entrySection) {
                foreach($entrySection->listings as $listing) {
                    WordPress::deleteListing($listing);
                    Algolia::deleteListing($listing);
                }
                $entrySection->listings()->delete();
            }
        }        

        // Set cache flags to flags (so that the caches are rebuilt).
        $formEntry->isCacheValid = false;
        if ($oldFormEntry) {
            $oldFormEntry->isCacheValid = false;
        }

        return $formEntry;
    }

    public static function rejectFormEntry(self $formEntry)
    {
        // Update status.
        $formEntry->form_entry_status_id = Status::findStatus('Rejected')->id;
        $formEntry->update();

        // Invalidate cache to regenerate the data.
        $formEntry->isCacheValid = false;

        return $formEntry;
    }

    public static function deleteFormEntry(self $formEntry)
    {
        // Updated status.
        $formEntry->form_entry_status_id = Status::findStatus('Deleted')->id;
        $formEntry->update();

        // Delete all WordPress resources and Algolia objects attached to the
        // form entry.
        foreach($formEntry->entrySections as $entrySection) {
            foreach($entrySection->listings as $listing) {
                WordPress::deleteListing($listing);
                Algolia::deleteListing($listing);
            }
        }

        // Invalidate cache to regenerate the data.
        $formEntry->isCacheValid = false;

        return $formEntry;
    }

    public function getCacheAttribute($value)
    {
        return $this->isCacheValid ? json_decode($value, true) : null;
    }

    public function setCacheAttribute($value)
    {
        $this->attributes['cache'] = $value ? json_encode($value) : null;
        $this->isCacheValid = $value ? true : false;
    }

    public function getDataAttribute()
    {
        // Check cache first.
        if ($this->isCacheValid) {
            return $this->cache;
        }

        // Get required relationships.
        $formEntry = self::with([
            'status',
            'form.directory',
            'entrySections.formSection',
            'entrySections.entryFields.formField.type',
            'entrySections.entryFields.stringValue',
            'entrySections.entryFields.textValue',
            'entrySections.entryFields.numberValue',
            'entrySections.entryFields.dateValue',
            'entrySections.entryFields.labelledValues'
        ])->find($this->id);

        $data = [
            'home_directory' => $formEntry->form->directory,
            'form_id'        => $formEntry->form->id,
            'status'         => $formEntry->status,
            'title'          => null,
            'sections'       => []
        ];

        // Attach statuses (i.e. `is_published`,... etc.).
        foreach(FormEntryStatus::all() as $status) {
            $property = 'is_' . strtolower($status->name);
            $data[$property] = $formEntry->status->name === $status->name;
        }

        // Attach section and fields.
        foreach($formEntry->entrySections as $entrySection) {
            $formSection = $entrySection->formSection;

            // Create section if it hasn't already been created.
            if (!isset($data['sections'][$formSection->object_key])) {
                $data['sections'][$formSection->object_key] = [];
            }

            // Add fields and corresponding values.
            $fields = [];

            // Add the '_meta' property so that we can easily identify each
            // entry section in the fieldset.
            $fields['_meta'] = [
                'entry_section_id'           => $entrySection->id,
                'published_entry_section_id' => $entrySection
                    ->published_entry_section_id,
                'title'                      => $entrySection->title
            ];

            foreach($entrySection->entryFields as $entryField) {
                $formField = $entryField->formField;
                $fields[$formField->object_key] = $entryField->value;
            }
            array_push($data['sections'][$formSection->object_key], $fields);
        }

        // Get the title of the resource (for pagination).
        $sectionKey = $formEntry->form->pagination_section_object_key;
        $fieldKey = $formEntry->form->pagination_field_object_key;
        if (isset($data['sections'][$sectionKey][0][$fieldKey])) {
            $data['title'] = $data['sections'][$sectionKey][0][$fieldKey];
        }

        // Update cache
        $formEntry->cache = $data;
        $formEntry->update();

        return $data;
    }
}
