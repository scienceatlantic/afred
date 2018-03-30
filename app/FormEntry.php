<?php

namespace App;

use App\EntrySection;
use App\Form;
use App\FormEntryStatus as Status;
use App\FormEntryToken as Token;
use App\Job;
use App\Events\FormEntryStatusUpdated;
use App\Events\ListingCreated;
use App\Events\ListingDeleted;
use App\Events\ListingHidden;
use App\Events\ListingUnhidden;
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
        'ilo',
        'is_submitted',
        'is_published',
        'is_hidden',
        'is_revision',
        'is_rejected',
        'is_deleted',
        'can_publish',
        'can_reject',
        'can_hide',
        'can_unhide',
        'can_edit',
        'can_delete',
        'job_count',
        'has_pending_jobs',
        'has_open_token',
        'has_locked_token',
        'has_unclosed_token',
        'wp_admin_url',
        'wp_admin_compare_url',
        'wp_admin_history_url',
        'wp_admin_tokens_url',
        'wp_form_url',
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

    /**
     * Relationship between form entry and its status.
     */
    public function status()
    {
        return $this->belongsTo('App\FormEntryStatus', 'form_entry_status_id');
    }

    /**
     * Relationship between form entry and its form.
     */
    public function form()
    {
        return $this->belongsTo('App\Form');
    }

    /**
     * Relationship between form entry and the form(s) it's attached to (i.e. 
     * directories the form entry is saved to).
     */
    public function formsAttachedTo()
    {
        return $this->belongsToMany('App\Form')->withTimestamps();
    }

    /**
     * Relationship between form entry and its entry sections.
     */
    public function entrySections()
    {
        return $this->hasMany('App\EntrySection');
    }

    public function entryFields()
    {
        return $this->hasManyThrough('App\EntryField', 'App\EntrySection');
    }

    /**
     * Relationship between form entry and its author.
     */
    public function author()
    {
        return $this->belongsTo('App\User', 'author_user_id');
    }

    /**
     * Relationship between form entry and its reviewer.
     */
    public function reviewer()
    {
        return $this->belongsTo('App\User', 'reviewer_user_id');
    }

    /**
     * Relationship between form entry and its primary contact.
     */
    public function primaryContact()
    {
        return $this->belongsTo('App\User', 'primary_contact_user_id');
    }

    /**
     * Relationship between form entry and its editors (i.e. users granted
     * access to generate edit "tokens").
     */    
    public function editors()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }

    /**
     * Relationship between form entry and its listings (via EntrySection) (i.e.
     * published data).
     */
    public function listings()
    {
        return $this->hasManyThrough('App\Listing', 'App\EntrySection');
    }

    /**
     * Relationship between form entry and its edit tokens. The relationship is
     * linked via the "resource_id" column so that we're getting all tokens
     * attached to a particular resource (i.e. including all edits).
     */
    public function tokens()
    {
        return $this->hasMany(
            'App\FormEntryToken',
            'resource_id',
            'resource_id'
        );
    }

    /**
     * Scope to get all "submitted" form entries.
     */
    public function scopeSubmitted($query)
    {
        $statusId = Status::findStatus('Submitted')->id;
        return $query->where('form_entry_status_id', $statusId);
    }

    /**
     * Scope to get all "published" form entries.
     */    
    public function scopePublished($query)
    {
        $statusId = Status::findStatus('Published')->id;
        return $query->where('form_entry_status_id', $statusId);
    }

    /**
     * Scope to get all "hidden" form entries.
     */    
    public function scopeHidden($query)
    {
        $statusId = Status::findStatus('Hidden')->id;
        return $query->where('form_entry_status_id', $statusId);
    }    

    /**
     * Scope to get all "rejected" form entries.
     */    
    public function scopeRejected($query, $includeEdits = false)
    {
        $statusId = Status::findStatus('Rejected')->id;
        return $query
            ->where('form_entry_status_id', $statusId)
            ->where('is_edit', $includeEdits);
    }

    /**
     * Scope to get all "deleted" form entries.
     */    
    public function scopeDeleted($query)
    {
        $statusId = Status::findStatus('Deleted')->id;
        return $query->where('form_entry_status_id', $statusId);
    }

    /**
     * 
     */
    public function getPublished()
    {
        return self
            ::where('resource_id', $this->resource_id)
            ->published()
            ->first();
    }

    public function getIloAttribute()
    {
        $formEntry = $this->fresh();

        $formField = $formEntry
            ->form
            ->formFields()
            ->where('has_ilo', true)
            ->first();

        if (!$formField) {
            return null;
        }

        $entryField = $formEntry
            ->entryFields()
            ->where('form_field_id', $formField->id)
            ->first();

        if (!$entryField) {
            return null;
        }

        if ($ilo = $entryField->getValue(true)->ilo) {
            $ilo->labelledValue;
            return $ilo;
        }

        return null;
    }

    /**
     * 
     * 
     * @return 
     */
    public function getCacheAttribute($value)
    {
        return $value ? json_decode($value, true) : null;
    }

    public function setCacheAttribute($value)
    {
        $this->attributes['cache'] = $value ? json_encode($value) : null;
    }

    public function refreshCache()
    {
        $this->cache = null;
        $this->update();
        $this->data;
    }

    public function getJobCountAttribute()
    {
        $value = "%\\\\\\\"formEntryId\\\\\\\";i:{$this->id};%";
        return Job::where('payload', 'like', $value)->count();
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

    public function getIsHiddenAttribute()
    {
        $statusId = Status::findStatus('Hidden')->id;
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

    public function getCanPublishAttribute()
    {
        return $this->is_submitted && !$this->has_pending_jobs;
    }

    public function getCanRejectAttribute()
    {
        return $this->can_publish;
    }

    public function getCanHideAttribute()
    {
        return $this->is_published
            && !$this->has_unclosed_token
            && !$this->has_pending_jobs;
    }

    public function getCanUnhideAttribute()
    {
        return $this->is_hidden && !$this->has_pending_jobs;
    }

    public function getCanEditAttribute()
    {
        return $this->is_published
            && !$this->has_pending_jobs
            && !$this->has_unclosed_token;
    }

    public function getCanDeleteAttribute()
    {
        return $this->is_published
            && !$this->has_pending_jobs
            && !$this->has_unclosed_token;
    }

    public function getHasPendingJobsAttribute()
    {
        return $this->job_count > 0;
    }    

    public function getHasOpenTokenAttribute()
    {
        return (bool) $this->tokens()->open()->count();
    }

    public function getHasLockedTokenAttribute()
    {
        return (bool) $this->tokens()->locked()->count();
    }

    public function getHasUnclosedTokenAttribute()
    {
        return (bool) $this->tokens()->unclosed()->count();
    }    

    public function getWpAdminUrlAttribute()
    {
        return $this->form->directory->getTargetWpAdminBaseUrl()
            . '/admin.php?page=afredwp-resource&afredwp-directory-id='
            . $this->form->directory->id
            . '&afredwp-form-id='
            . $this->form->id
            . '&afredwp-form-entry-id='
            . $this->id;
    }   

    public function getWpAdminCompareUrlAttribute()
    {
        if ($this->is_edit && $this->status->name === 'Submitted') {
            return $this->form->directory->getTargetWpAdminBaseUrl()
                . '/admin.php?page=afredwp-resource-compare&afredwp-directory-id='
                . $this->form->directory->id
                . '&afredwp-form-id='
                . $this->form->id
                . '&afredwp-edited-form-entry-id='
                . $this->id;
        }
        return null;
    }

    public function getWpAdminHistoryUrlAttribute()
    {
        return $this->form->directory->getTargetWpAdminBaseUrl()
            . '/admin.php?page=afredwp-resource-history&afredwp-directory-id='
            . $this->form->directory->id
            . '&afredwp-form-id='
            . $this->form->id
            . '&afredwp-origin-form-entry-id='
            . $this->id
            . '&afredwp-resource-id='
            . $this->resource_id;
    }
    
    public function getWpAdminTokensUrlAttribute()
    {
        return $this->form->directory->getTargetWpAdminBaseUrl()
            . '/admin.php?page=afredwp-resource-tokens&afredwp-directory-id='
            . $this->form->directory->id
            . '&afredwp-form-id='
            . $this->form->id
            . '&afredwp-form-entry-id='
            . $this->id;
    }    

    public function getWpFormUrlAttribute()
    {
        return $this->form->directory->wp_base_url
            . '/?p='
            . $this->form->wp_post_id;
    }

    public function getDataAttribute()
    {
        // Check cache first.
        if ($this->cache) {
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

            // Add the 'entry_section' property so that we can easily identify
            // each entry section in the fieldset.
            $fields['entry_section'] = EntrySection
                ::with('listings')
                ->find($entrySection->id)
                ->toArray();

            foreach($entrySection->entryFields as $entryField) {
                $formField = $entryField->formField;
                $fields[$formField->object_key] = $entryField->value;

                // Add additional '<object_key>_no_html' attribute for richtext
                // fields
                if ($formField->type->name === 'richtext') {
                    $key = $formField->object_key . '_no_html';
                    $fields[$key] = strip_tags($entryField->value);
                }
            }

            array_push($data['sections'][$formSection->object_key], $fields);
        }

        // Get the title of the resource (for pagination).
        $sKey = $formEntry->form->pagination_section_object_key;
        $fKey = $formEntry->form->pagination_field_object_key;
        if (isset($data['sections'][$sKey][0][$fKey])) {
            $data['pagination_title'] = $data['sections'][$sKey][0][$fKey];
        }

        // Set the "order_by_title" (i.e. = pagination_title)
        if (isset($data['pagination_title'])) {
            $formEntry->order_by_title = $data['pagination_title'];
            $formEntry->update();
        }

        // Update cache
        $formEntry->cache = $data;
        $formEntry->update();

        return $data;
    }

    public static function submitFormEntry(
        Request $request,
        Form $rootForm,
        self $oldFormEntry = null
    ) {
        $isEdit = (bool) $oldFormEntry;

        // Set resource ID (if it's an edit, use existing).
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
        // Set author of form entry if request is submitted by a logged-in
        // WordPress user.
        if ($request->user()) {
            $formEntry->author_user_id = $request->user()->id;
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
        foreach($request->sections as $sectionObjectKey => $fieldsets) {
            // Check that the section exists, otherwise skip.
            $rootFormSection = $rootForm
                ->formSections()
                ->where('object_key', $sectionObjectKey)
                ->first();
            if (!$rootFormSection) {
                continue;
            }

            // Create entry sections for each fieldset.
            foreach($fieldsets as $fieldset) {
                // Create new entry section.
                $entrySection = new EntrySection();
                $entrySection->form_entry_id = $formEntry->id;
                $entrySection->form_section_id = $rootFormSection->id;
                // Add "published_entry_section_id" if this is an edit of an
                // existing entry section.
                if ($isEdit && isset($fieldset['entry_section'])) {
                    $entrySection->published_entry_section_id
                        = $fieldset['entry_section']['published_entry_section_id'];
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

                    // TODO
                    if ($rootFormField->object_key === 'is_public') {
                        $labelledValue = $entryField->value;

                        if ($labelledValue['value'] === 'Public') {
                            $entrySection->is_public = true;
                        } else if ($labelledValue['value'] === 'Private') {
                            $entrySection->is_public = false;
                        }
                        
                        $entrySection->update();
                    }
                }

                // Add entry section as primary contact if form section's 
                // primary contact flag is true and primary contact has not been
                // set.
                if ($rootFormSection->is_primary_contact
                    && !$formEntry->primaryContact) {
                    self::addPrimaryContact($formEntry, $entrySection);

                    // This ensures that the model's eager loads are re-loaded.
                    $formEntry = $formEntry->fresh();
                    
                }

                // Add entry section as an editor if the form section's editor
                // flag is true.
                if ($rootFormSection->is_editor) {
                    self::addEditor($formEntry, $entrySection);

                    // This ensures that the model's eager loads are re-loaded.
                    $formEntry = $formEntry->fresh();
                }                
            }
        }

        // If the primary contact has not been set, and the request was
        // submitted by an authenticated user, set that user as the primary
        // contact. Otherwise, abort.
        if (!$formEntry->primaryContact) {
            if (!$request->user()) {
                Log::error('Primary contact not set on form entry', [
                    'formEntry' => $formEntry->toArray()
                ]);
                abort(500);
            }

            $formEntry->primary_contact_user_id = $request->user()->id;
            $formEntry->update();
            $formEntry = $formEntry->fresh();
        }

        // If the author has not been set, but the primary contact has, set the
        // primary contact as the author. Otherwise, abort.
        if (!$formEntry->author) {
            if (!$formEntry->primaryContact) {
                Log::error('Author not set on form entry', [
                    'formEntry' => $formEntry->toArray()
                ]);
                abort(500);
            }

            $formEntry->author_user_id = $formEntry->primaryContact->id;
            $formEntry->update();
            $formEntry = $formEntry->fresh();
        }

        // If this is an edit, lock the edit token (so that it can be used to
        // submit more edits).
        if ($formEntry->is_edit) {
            Token::lockToken(
                $formEntry->tokens()->open()->first(),
                $formEntry
            );
        }

        $formEntry->refreshCache();

        event(new FormEntryStatusUpdated($formEntry));
        
        return $formEntry;
    }

    public static function publishFormEntry(Request $request, self $formEntry) {
        // Determine if publishing an edited form entry. If it is an edit, get
        // the currently published form entry. If we can't find the currently
        // published form entry, abort.
        if ($formEntry->is_edit && !$oldFormEntry = $formEntry->getPublished()) {
            $msg = 'Trying to publish an edited form entry but currently '
                 . 'published form entry was not found';
            Log::error($msg, [
                '$formEntry' => $formEntry->toArray()
            ]);
            abort(500);
        }

        // Update status of currently published form entry (if this is an edit)
        // to "Revision".
        if ($formEntry->is_edit) {
            $revisionStatus = Status::findStatus('Revision');
            $oldFormEntry->form_entry_status_id = $revisionStatus->id;
            $oldFormEntry->update();
        }

        // Update status of form entry from "Submitted" to "Published".
        $formEntry->form_entry_status_id = Status::findStatus('Published')->id;
        $formEntry->update();

        // Add message/notes
        $formEntry->message = $request->message;
        $formEntry->notes = $request->notes;
        $formEntry->update();

        // Set reviewer.
        $formEntry->reviewer_user_id = $request->user()->id;
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
            $targetFormSections = FormSection
                ::whereIn('form_id', $formsAttachedToIds)
                ->where('object_key', $rootFormSection->object_key)
                ->get();

            // Loop through each entry section.
            foreach($entrySections as $entrySection) {
                // Skip entry section if it's not meant to be public (i.e. not
                // published to WordPress or Algolia).
                if (!$entrySection->is_public) {
                    $entrySection->published_entry_section_id = null;
                    $entrySection->update();
                    continue;
                }

                // Set the "published_entry_section_id" that will be used by
                // WordPress and Algolia (if it is not already set - i.e. not an
                // edit).
                if (!$entrySection->published_entry_section_id) {
                    $entrySection->published_entry_section_id
                        = $entrySection->id;
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

                    // If it exists, link it to the new entry section.
                    if ($listing) {
                        $listing->entry_section_id = $entrySection->id;
                        $listing->update();
                    } 
                    // If it doesn't exist, create a new listing.
                    else {
                        $listing = new Listing();
                        $listing->entry_section_id = $entrySection->id;
                        $listing->form_section_id = $targetFormSection->id;
                        $listing->published_entry_section_id
                            = $entrySection->published_entry_section_id;
                        $listing->save();
                    }

                    // Set the flags to false to signify that they either need
                    // to be updated (i.e. if it's an edit) or created in WP and
                    // Algolia.
                    $listing->is_in_wp = false;
                    $listing->is_in_algolia = false;
                    $listing->update();
                }
            }
        }

        // Delete any old WordPress resources or Algolia objects from the old
        // form entry (that were not carried over to the new form entry).
        if ($formEntry->is_edit) {
            foreach($oldFormEntry->listings as $listing) {
                event(new ListingDeleted(
                    $listing->targetDirectory,
                    $listing->formSection,
                    $oldFormEntry,
                    $listing->wp_post_id,
                    $listing->published_entry_section_id
                ));
            }
            $oldFormEntry->listings()->delete();
        }

        // Set "reviewed_at" timestamp.
        $formEntry->reviewed_at = now();
        $formEntry->update();

        // If this is an edit, close the edit token (signifying that the update
        // process is complete).
        if ($formEntry->is_edit) {
            Token::closeToken($formEntry->tokens()->locked()->first());
        }
        
        $formEntry->refreshCache();
        if ($formEntry->is_edit) {
            $oldFormEntry->refreshCache();
        }        

        // Create events for new listings.
        foreach($formEntry->listings as $listing) {
            event(new ListingCreated($listing));
        }

        event(new FormEntryStatusUpdated($formEntry));

        return $formEntry;
    }

    public static function rejectFormEntry(Request $request, self $formEntry)
    {
        $formEntry->form_entry_status_id = Status::findStatus('Rejected')->id;
        $formEntry->reviewer_user_id = $request->user()->id;
        $formEntry->message = $request->message;
        $formEntry->notes = $request->notes;        
        $formEntry->reviewed_at = now();
        $formEntry->update();
        
        // Close the edit token.
        if ($formEntry->is_edit) {
            Token::closeToken($formEntry->tokens()->locked()->first());
        }

        $formEntry->refreshCache();

        event(new FormEntryStatusUpdated($formEntry));

        return $formEntry;
    }

    public static function deleteFormEntry(self $formEntry)
    {
        // Not allowed to delete form entries with open or locked edit tokens.
        if ($token = $formEntry->tokens()->unclosed()->first()) {
            Log::error('Attempting to delete form entry with unclosed token', [
                'formEntry' => $formEntry->toArray(),
                'token'     => $token->toArray()
            ]);
            abort(500);
        }

        $formEntry->form_entry_status_id = Status::findStatus('Deleted')->id;
        $formEntry->update();

        foreach($formEntry->listings as $listing) {
            event(new ListingDeleted(
                $listing->targetDirectory,
                $listing->formSection,
                $formEntry,
                $listing->wp_post_id,
                $listing->published_entry_section_id
            ));
        }
        $formEntry->listings()->delete();

        $formEntry->refreshCache();

        return $formEntry;
    }

    public static function hideFormEntry(self $formEntry)
    {
        $formEntry->form_entry_status_id = Status::findStatus('Hidden')->id;
        $formEntry->update();

        foreach($formEntry->listings as $listing) {
            $listing->is_in_algolia = false;
            $listing->update();

            event(new ListingHidden($listing));
        }

        $formEntry->refreshCache();

        return $formEntry;
    }

    public static function unhideFormEntry(self $formEntry)
    {
        $formEntry->form_entry_status_id = Status::findStatus('Published')->id;
        $formEntry->update();

        foreach($formEntry->listings as $listing) {
            event(new ListingUnhidden($listing));
        }

        $formEntry->refreshCache();

        return $formEntry;
    }

    private static function addPrimaryContact(
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
        $formEntry->update();
    }
    
    private static function addEditor(
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
