<?php

namespace App;

use App\Events\FormEntryUpdate;
use App\Events\ListingCreated;
use App\Events\ListingDeleted;
use App\Form;
use App\FormEntryStatus as Status;
use App\FormEntryToken as Token;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Log;

class FormEntry extends Model
{
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'is_submitted',
        'is_published',
        'is_revision',
        'is_rejected',
        'is_deleted',
        'wp_admin_url',
        'data'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['reviewed_at'];    

    /** 
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'cache'
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
        return $this->belongsTo('App\User', 'author_user_id');
    }

    public function reviewer()
    {
        return $this->belongsTo('App\User', 'reviewer_user_id');
    }

    public function primaryContact()
    {
        return $this->belongsTo('App\User', 'primary_contact_user_id');
    }

    public function editors()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }

    public function listings()
    {
        return $this->hasManyThrough('App\Listing', 'App\EntrySection');
    }

    public function tokens()
    {
        return $this->hasMany('App\FormEntryToken');
    }

    public function scopeSubmitted($query)
    {
        $statusId = Status::findStatus('Submitted')->id;
        return $query->where('form_entry_status_id', $statusId);
    }

    public function scopePublished($query)
    {
        $statusId = Status::findStatus('Published')->id;
        return $query->where('form_entry_status_id', $statusId);
    }

    public function getCacheAttribute($value)
    {
        return $this->is_cache_valid ? json_decode($value, true) : null;
    }

    public function setCacheAttribute($value)
    {
        $this->attributes['cache'] = $value ? json_encode($value) : null;
        $this->is_cache_valid = $value ? true : false;
    }

    public function getIsSubmittedAttribute()
    {
        $statusId = Status::findStatus('Submitted')->id;
        return $this->form_entry_status_id === $statusId;
    }    
    
    public function getIsPublishedAttribute()
    {
        $statusId = Status::findStatus('Published')->id;
        return $this->form_entry_status_id === $statusId;
    }

    public function getIsRevisionAttribute()
    {
        $statusId = Status::findStatus('Revision')->id;
        return $this->form_entry_status_id === $statusId;
    }
    
    public function getIsRejectedAttribute()
    {
        $statusId = Status::findStatus('Rejected')->id;
        return $this->form_entry_status_id === $statusId;
    }
    
    public function getIsDeletedAttribute()
    {
        $statusId = Status::findStatus('Deleted')->id;
        return $this->form_entry_status_id === $statusId;
    }

    public function getWpAdminUrlAttribute()
    {
        return $this->form->directory->wp_admin_base_url
            . '/admin.php?page=afredwp-resource&directoryId='
            . $this->form->directory->id
            . '&formId='
            . $this->form->id
            . '&formEntryId='
            . $this->id;
    }

    public function getHasPendingOperationsAttribute()
    {
        return false;
    }

    public static function hasOpenToken(self $formEntry)
    {
        return (bool) Token
            ::whereIn(
                'form_entry_id',
                self::where('resource_id', $formEntry->resource_id)->pluck('id')
            )
            ->open()
            ->count();
    }

    public static function hasLockedToken(self $formEntry)
    {
        return (bool) Token
            ::whereIn(
                'form_entry_id',
                self::where('resource_id', $formEntry->resource_id)->pluck('id')
            )
            ->locked()
            ->count();
    }
    
    public static function hasUnclosedToken(self $formEntry)
    {
        return (bool) Token
            ::whereIn(
                'form_entry_id',
                self::where('resource_id', $formEntry->resource_id)->pluck('id')
            )
            ->unclosed()
            ->count();
    }

    public static function submitFormEntry(
        Request $request,
        Form $rootForm,
        self $oldFormEntry = null
    ) {
        $isEdit = (bool) $oldFormEntry;

        // TODO
        if ($isEdit) {
            $resourceId = $oldFormEntry->resource_id;
        } else {
            $resourceId = (self::max('resource_id') + 1);
        }
        
        // Create new form entry
        $formEntry = new self();
        $formEntry->resource_id = $resourceId;
        $formEntry->form_id = $rootForm->id;
        $formEntry->form_entry_status_id = Status::findStatus('Submitted')->id;
        if ($request->user()) {
            $formEntry->author_user_id = $request->user()->id;
        }
        if ($request->user()) {
            $formEntry->primary_contact_user_id = $request->user()->id;
        }
        $formEntry->is_edit = $isEdit;
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

                if ($rootFormSection->is_primary_contact) {
                    self::addPrimaryContact($formEntry, $entrySection);
                }

                if ($rootFormSection->is_editor) {
                    self::addEditor($formEntry, $entrySection);
                }                
            }
        }

        // TODO
        if (!$formEntry->primaryContact) {
            
        }

        // Update cache.
        $formEntry->cache = $formEntry->data;
        $formEntry->update();

        event(new FormEntryUpdate($formEntry));
        
        return $formEntry;
    }

    public static function publishFormEntry(Request $request, self $formEntry) {
        // Determine if publishing an edited form entry. If it is an edit, get
        // the currently published form entry (i.e. `$oldFormEntry`).
        $oldFormEntry = null;
        if ($formEntry->is_edit) {
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
        if ($formEntry->is_edit) {
            $revisionStatus = Status::findStatus('Revision');
            $oldFormEntry->form_entry_status_id = $revisionStatus->id;
            $oldFormEntry->update();
        }
        $formEntry->form_entry_status_id = Status::findStatus('Published')->id;
        $formEntry->update();

        // Set reviewer.
        $formEntry->reviewer_user_id = $request->user()->id;

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
            $targetFormSections = FormSection
                ::whereIn('form_id', $formsAttachedToIds)
                ->where('object_key', $rootFormSection->object_key)
                ->get();

            // Loop through each entry section.
            foreach($entrySections as $entrySection) {
                // Skip entry section if it's not meant to be public.
                if (!$entrySection->is_public) {
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
                foreach($targetFormSections as $targetFormSection) {
                    // Determine if a listing for this combination (entry
                    // section and form section) already exists.
                    $listing = Listing::findListing(
                        $entrySection->published_entry_section_id,
                        $targetFormSection->id
                    );

                    // If it exists, update it with the new entry section's id
                    // and set WP and Algolia flags to false (to signify that
                    // they need to be updated).
                    if ($listing) {
                        $listing->entry_section_id = $entrySection->id;
                        $listing->is_in_wp = false;
                        $listing->is_in_algolia = false;
                        $listing->update();
                    } 
                    // If it doesn't, create a new listing.
                    else {
                        $listing = new Listing();
                        $listing->entry_section_id = $entrySection->id;
                        $listing->form_section_id = $targetFormSection->id;
                        $listing->published_entry_section_id = $entrySection
                            ->published_entry_section_id;
                        $listing->is_in_wp = false;
                        $listing->is_in_algolia = false;
                        $listing->save();
                    }
                }
            }
        }

        // Delete any old WordPress resources or Algolia objects from the old
        // form entry (that were not carried over to the new form entry).
        if ($oldFormEntry) {
            foreach($oldFormEntry->listings as $listing) {
                event(new ListingDeleted(
                    $listing->entrySection->formSection->form->directory,
                    $listing->formSection,
                    $oldFormEntry,
                    $listing->wp_post_id,
                    $listing->published_entry_section_id
                ));
            }
            $oldFormEntry->listings()->delete();
        }        

        // Set cache flags to flags (so that the caches are rebuilt).
        $formEntry->is_cache_valid = false;
        if ($oldFormEntry) {
            $oldFormEntry->is_cache_valid = false;
        }

        foreach($formEntry->listings as $listing) {
            event(new ListingCreated($formEntry, $listing));
        }

        // Set reviewed at timestamp.
        $formEntry->reviewed_at = now();
        $formEntry->update();

        return $formEntry;
    }

    public static function rejectFormEntry(Request $request, self $formEntry)
    {
        // Update status.
        $formEntry->form_entry_status_id = Status::findStatus('Rejected')->id;

        // Set reviewer.
        $formEntry->reviewer_user_id = $request->user()->id;

        // Invalidate cache to rebuild the data.
        $formEntry->is_cache_valid = false;

        // Set reviewed at timestamp.
        $formEntry->reviewed_at = now();

        $formEntry->update();        

        event(new FormEntryUpdate($formEntry));        

        return $formEntry;
    }

    public static function deleteFormEntry(Request $request, self $formEntry)
    {
        // Update status.
        $formEntry->form_entry_status_id = Status::findStatus('Deleted')->id;
        $formEntry->update();

        foreach($formEntry->listings as $listing) {
            event(new ListingDeleted(
                $listing->formSection->form->directory,
                $listing->formSection,
                $formEntry,
                $listing->wp_post_id,
                $listing->published_entry_section_id
            ));
        }
        $formEntry->listings()->delete();

        // Invalidate cache to rebuild the data.
        $formEntry->is_cache_valid = false;

        return $formEntry;
    }

    public function getDataAttribute()
    {
        // Check cache first.
        if ($this->is_cache_valid) {
            return $this->cache;
        }

        // Get a copy of the object this way to avoid eager loading the
        // relationships on to the actual instance.
        $formEntry = self::with([
            'form',
            'entrySections.formSection',
            'entrySections.entryFields.formField.type',
            'entrySections.entryFields.stringValue',
            'entrySections.entryFields.textValue',
            'entrySections.entryFields.numberValue',
            'entrySections.entryFields.dateValue',
            'entrySections.entryFields.labelledValues'
        ])->find($this->id);

        $data = [
            'pagination_title' => null,
            'sections'         => []
        ];

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
            $fields['_meta'] = $entrySection->meta;

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
            $data['pagination_title'] = $data['sections'][$sectionKey][0][$fieldKey];
        }

        // Update cache
        $formEntry->cache = $data;
        $formEntry->update();

        return $data;
    }

    public static function addEntrySectionAsAuthor($data, FormEntry $formEntry)
    {
        if (!$user = User::findByEmail($data['email'])) {
            $user = new User();
            $user->role_id = Role::findRole('Author')->id;
            $user->email = $data['email'];
            $user->first_name = $data['last_name'];
            $user->last_name = $data['last_name'];
            $user->save();   
        }

        $formEntry->author_user_id = $user->id;
        $formEntry->update();
    }

    public static function addPrimaryContact(
        self $formEntry,
        EntrySection $entrySection
    ) {
        if (!$email = $entrySection->getFieldValue('email')) {
            $msg = 'The `email` field cannot be empty. Unable to add entry '
                 . 'section as primary contact';
            Log::error($msg, [
                'entrySection' => $entrySection->toArray()
            ]);
            abort(500);
        }

        if (!$user = User::findByEmail($email)) {
            $user = new User();
            $user->role_id = Role::findRole('Contributor')->id;
            $user->email = $email;
            $user->first_name = $entrySection->getFieldValue('first_name');
            $user->last_name = $entrySection->getFieldValue('last_name');
            $user->password = 'password'; // TODO
            $user->save();
        } else if ($user->is_subscriber) {
            $user->role_id = Role::findRole('Contributor')->id;
            $user->update();
        }

        $formEntry->primary_contact_user_id = $user->id;
        if (!$formEntry->author) {
            $formEntry->author_user_id = $user->id;
        }
        $formEntry->update();
    }
    
    public static function addEditor(
        self $formEntry,
        EntrySection $entrySection
    ) {
        if (!$email = $entrySection->getFieldValue('email')) {
            $msg = 'The `email` field cannot be empty. Unable to add entry '
                 . 'section as an editor';
            Log::error($msg, [
                'entrySection' => $entrySection->toArray()
            ]);
            abort(500);
        }

        if (!$user = User::findByEmail($email)) {
            $user = new User();
            $user->role_id = Role::findRole('Contributor')->id;
            $user->email = $email;
            $user->first_name = $entrySection->getFieldValue('first_name');
            $user->last_name = $entrySection->getFieldValue('last_name');
            $user->password = 'password'; // TODO
            $user->save();
        } else if ($user->is_subscriber) {
            $user->role_id = Role::findRole('Contributor')->id;
            $user->update();
        }

        $formEntry->editors()->attach($user->id);
    }     
}
