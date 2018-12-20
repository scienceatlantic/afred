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

    /**
     * Relationship with all the entry fields it has (via entry sections).
     */
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
     * Returns the currently published form entry record.
     */
    public function getPublished()
    {
        return self
            ::where('resource_id', $this->resource_id)
            ->published()
            ->first();
    }

    /**
     * Dynamic property that returns an ILO if found.
     *
     * @return {Ilo|null}
     */
    public function getIloAttribute()
    {
        $formEntry = $this->fresh();

        // Look through the form fields and return the first form field with
        // `has_ilo` set to true.
        $formField = $formEntry
            ->form
            ->formFields()
            ->where('has_ilo', true)
            ->first();

        if (!$formField) {
            return null;
        }

        // See if that form field has a corresponding entry field.
        $entryField = $formEntry
            ->entryFields()
            ->where('form_field_id', $formField->id)
            ->first();

        if (!$entryField) {
            return null;
        }

        // If the entry field exists, see if its value object has an ILO, if it
        // return it (with the labelled value it belongs to).
        if ($ilo = $entryField->getValue(true)->ilo) {
            $ilo->labelledValue;
            return $ilo;
        }

        return null;
    }

    /**
     * Laravel getter method to automatically decode the cache into JSON if it
     * is not empty.
     *
     * @return
     */
    public function getCacheAttribute($value)
    {
        return $value ? json_decode($value, true) : null;
    }

    /**
     * Laravel setter method to automatically encode the value provided into
     * a JSON object
     */
    public function setCacheAttribute($value)
    {
        $this->attributes['cache'] = $value ? json_encode($value) : null;
    }

    /**
     * Nullifies the `cache` property so that the `data` property will need to
     * be regenerated.
     */
    public function emptyCache()
    {
        unset($this->data);
        $this->cache = null;
        $this->update();
    }

    /**
     * Determines if this form entry has any pending jobs.
     *
     * This method basically searches the `payload` column in the `jobs` table
     * to determine if its form entry ID is in it. That is how it determines if
     * it has any pending jobs.
     *
     * Have a look at the events generated. A lot of them will have a
     * `formEntryId` property that is not being used. It's only purpose is to be
     * used by this method to identify the form entry it belongs to.
     */
    public function getJobCountAttribute()
    {
        $value = "%\\\\\\\"formEntryId\\\\\\\";i:{$this->id};%";
        return Job::where('payload', 'like', $value)->count();
    }

    /**
     * Does this form entry has a form entry status of "Submitted"?
     *
     * @return {bool}
     */
    public function getIsSubmittedAttribute()
    {
        $statusId = Status::findStatus('Submitted')->id;
        return $this->form_entry_status_id === $statusId;
    }

    /**
     * Does this form entry has a form entry status of "Published"?
     *
     * @return {bool}
     */
    public function getIsPublishedAttribute()
    {
        $statusId = Status::findStatus('Published')->id;
        return $this->form_entry_status_id === $statusId;
    }

    /**
     * Does this form entry has a form entry status of "Hidden"?
     *
     * @return {bool}
     */
    public function getIsHiddenAttribute()
    {
        $statusId = Status::findStatus('Hidden')->id;
        return $this->form_entry_status_id === $statusId;
    }

    /**
     * Does this form entry has a form entry status of "Revision"?
     *
     * @return {bool}
     */
    public function getIsRevisionAttribute()
    {
        $statusId = Status::findStatus('Revision')->id;
        return $this->form_entry_status_id === $statusId;
    }

    /**
     * Does this form entry has a form entry status of "Rejected"?
     *
     * @return {bool}
     */
    public function getIsRejectedAttribute()
    {
        $statusId = Status::findStatus('Rejected')->id;
        return $this->form_entry_status_id === $statusId;
    }

    /**
     * Does this form entry has a form entry status of "Deleted"?
     *
     * @return {bool}
     */
    public function getIsDeletedAttribute()
    {
        $statusId = Status::findStatus('Deleted')->id;
        return $this->form_entry_status_id === $statusId;
    }

    /**
     * Can this form entry be published?
     *
     * @return {bool}
     */
    public function getCanPublishAttribute()
    {
        return $this->is_submitted && !$this->has_pending_jobs;
    }

    /**
     * Can this form entry be rejected?
     *
     * @return {bool}
     */
    public function getCanRejectAttribute()
    {
        return $this->can_publish;
    }

    /**
     * Can you hide (i.e. temporarily remove from public view) this form entry?
     *
     * @return {bool}
     */
    public function getCanHideAttribute()
    {
        return $this->is_published
            && !$this->has_unclosed_token
            && !$this->has_pending_jobs;
    }

    /**
     * Can you unhide this form entry?
     *
     * @return {bool}
     */
    public function getCanUnhideAttribute()
    {
        return $this->is_hidden && !$this->has_pending_jobs;
    }

    /**
     * Can this form entry be edited?
     *
     * @return {bool}
     */
    public function getCanEditAttribute()
    {
        return $this->is_published
            && !$this->has_pending_jobs
            && !$this->has_unclosed_token;
    }

    /**
     * Can this form entry be deleted?
     *
     * @return {bool}
     */
    public function getCanDeleteAttribute()
    {
        return $this->is_published
            && !$this->has_pending_jobs
            && !$this->has_unclosed_token;
    }

    /**
     * Does this form entry have any pending jobs?
     *
     * @return {bool}
     */
    public function getHasPendingJobsAttribute()
    {
        return $this->job_count > 0;
    }

    /**
     * Does this form entry have any open form entry tokens?
     *
     * @return {bool}
     */
    public function getHasOpenTokenAttribute()
    {
        return (bool) $this->tokens()->open()->count();
    }

    /**
     * Does this form entry have any locked form entry tokens?
     *
     * @return {bool}
     */
    public function getHasLockedTokenAttribute()
    {
        return (bool) $this->tokens()->locked()->count();
    }

    /**
     * Does this form entry have any open or locked form entry tokens?
     *
     * @return {bool}
     */
    public function getHasUnclosedTokenAttribute()
    {
        return (bool) $this->tokens()->unclosed()->count();
    }

    /**
     * WordPress resource administration page URL.
     *
     * @return {string}
     */
    public function getWpAdminUrlAttribute()
    {
        return $this->form->directory->target_wp_admin_base_url
            . '/admin.php?page=afredwp-resource&afredwp-directory-id='
            . $this->form->directory->id
            . '&afredwp-form-id='
            . $this->form->id
            . '&afredwp-form-entry-id='
            . $this->id;
    }

    /**
     * WordPress resource administration compare page (for edits) URL.
     *
     * @return {string}
     */
    public function getWpAdminCompareUrlAttribute()
    {
        if ($this->is_edit && $this->status->name === 'Submitted') {
            return $this->form->directory->target_wp_admin_base_url
                . '/admin.php?page=afredwp-resource-compare&afredwp-directory-id='
                . $this->form->directory->id
                . '&afredwp-form-id='
                . $this->form->id
                . '&afredwp-edited-form-entry-id='
                . $this->id;
        }
        return null;
    }

    /**
     * WordPress resource administration history (list of edits) page URL.
     *
     * @return {string}
     */
    public function getWpAdminHistoryUrlAttribute()
    {
        return $this->form->directory->target_wp_admin_base_url
            . '/admin.php?page=afredwp-resource-history&afredwp-directory-id='
            . $this->form->directory->id
            . '&afredwp-form-id='
            . $this->form->id
            . '&afredwp-origin-form-entry-id='
            . $this->id
            . '&afredwp-resource-id='
            . $this->resource_id;
    }

    /**
     * WordPress resource administration form entry tokens (list of all the
     * form entry tokens for this particular resource) page URL.
     *
     * @return {string}
     */
    public function getWpAdminTokensUrlAttribute()
    {
        return $this->form->directory->target_wp_admin_base_url
            . '/admin.php?page=afredwp-resource-tokens&afredwp-directory-id='
            . $this->form->directory->id
            . '&afredwp-form-id='
            . $this->form->id
            . '&afredwp-form-entry-id='
            . $this->id;
    }

    /**
     * WordPress submission form page URL.
     *
     * @return {string}
     */
    public function getWpFormUrlAttribute()
    {
        // Note: This property should not use the directory's target_wp_base_url
        // property because the form is (should) attached to only a single
        // WordPress installation.
        return $this->form->directory->wp_base_url
            . '/?p='
            . $this->form->wp_post_id;
    }

    /**
     * Returns the `data` dynamic attribute.
     *
     * This is the attribute that contains all the entry section and all the
     * entry section's entry fields data.
     */
    public function getDataAttribute()
    {
        // Check cache first.
        if ($this->cache && env('APP_ENV') == 'production') {
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

    /**
     * "Submit" (i.e. status=Submitted) a form entry (new or edit).
     *
     * @return {FormEntry} Newly created form entry.
     */
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
        $formEntry->is_edit = $isEdit;
        // If this is an edit, the author is the user that opened the token.
        if ($formEntry->is_edit) {
            $formEntry->author_user_id = $oldFormEntry
                ->tokens()
                ->open()
                ->first()
                ->user
                ->id;
        }
        // If the request was submitted by a logged-in user, then set that user
        // as the author.
        else if ($request->user()) {
            $formEntry->author_user_id = $request->user()->id;
        }

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
            // Check that the section exists and is active, otherwise skip.
            $rootFormSection = $rootForm
                ->formSections()
                ->active()
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

                // Create fields (only those that are active).
                $rootFormFields = $rootFormSection
                    ->formFields()
                    ->active()
                    ->get();
                foreach($rootFormFields as $rootFormField) {
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

                    // Check for the special 'is_public' attribute. If found,
                    // set the corresponding entry section's is_public
                    // attribute (i.e. public = searchable, private = not
                    // searchable).
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

        $formEntry->emptyCache();

        event(new FormEntryStatusUpdated($formEntry));

        return $formEntry;
    }

    /**
     * "Publish" (i.e. status=Published) a form entry (new or edit).
     *
     * @return {FormEntry} Published form entry
     */
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

        $formEntry->emptyCache();
        if ($formEntry->is_edit) {
            $oldFormEntry->emptyCache();
        }

        // Create events for new listings.
        foreach($formEntry->listings as $listing) {
            event(new ListingCreated($listing));
        }

        // Note: Don't create a new FormEntryStatusUpdated event. We need all
        // the listings to be published first before doing that.

        return $formEntry;
    }

    /**
     * "Reject" (i.e. status=Rejected) a form entry (new or edit).
     *
     * @return {FormEntry} Rejected form entry
     */
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

        $formEntry->emptyCache();

        event(new FormEntryStatusUpdated($formEntry));

        return $formEntry;
    }

    /**
     * "Delete" (i.e. status=Delete) a form entry.
     *
     * Note: This is considered a "soft" delete. It still exists in the database
     * but all its listings are removed from WordPress and Algolia and then
     * finally the listings itself are deleted.
     *
     * @return {FormEntry} Deleted form entry
     */
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

        $formEntry->emptyCache();

        return $formEntry;
    }

    /**
     * "Hide" (i.e. status=Hidden) a published form entry.
     *
     * Temporarily remove a form entry from publication.
     *
     * @return {FormEntry} Hidden form entry
     */
    public static function hideFormEntry(self $formEntry)
    {
        $formEntry->form_entry_status_id = Status::findStatus('Hidden')->id;
        $formEntry->update();

        foreach($formEntry->listings as $listing) {
            $listing->is_in_algolia = false;
            $listing->update();

            event(new ListingHidden($listing));
        }

        $formEntry->emptyCache();

        return $formEntry;
    }

    /**
     * "Unhide" (i.e. status=Published) a hidden form entry.
     *
     * Re-publish a hidden form entry.
     *
     * @return {FormEntry} Published form entry
     */
    public static function unhideFormEntry(self $formEntry)
    {
        $formEntry->form_entry_status_id = Status::findStatus('Published')->id;
        $formEntry->update();

        foreach($formEntry->listings as $listing) {
            event(new ListingUnhidden($listing));
        }

        $formEntry->emptyCache();

        return $formEntry;
    }

    /**
     * When a form entry is submitted, this method is used on entry sections
     * that have form sections with `is_primary_contact = true` to add the
     * primary contact to the form entry.
     */
    private static function addPrimaryContact(
        self $formEntry,
        EntrySection $entrySection
    ) {
        // Check if the entry section contains an `email` entry field. If it
        // does not, abort!
        if (!$email = $entrySection->getFieldValue('email')) {
            $msg = 'The `email` field cannot be empty. Unable to add entry '
                 . 'section as primary contact';
            Log::error($msg, [
                'entrySection' => $entrySection->toArray()
            ]);
            abort(500);
        }

        // If the user does not exist in the users table (determined by the
        // email), create the user with a role of "Contributor"
        if (!$user = User::findByEmail($email)) {
            $user = new User();
            $user->role_id = Role::findRole('Contributor')->id;
            $user->email = $email;
            $user->first_name = $entrySection->getFieldValue('first_name');
            $user->last_name = $entrySection->getFieldValue('last_name');
            $user->password = '';
            $user->save();
        }
        // If the user exists in the users table but is a Subscriber, bump their
        // role up to "Contributor".
        else if ($user->is_subscriber) {
            $user->role_id = Role::findRole('Contributor')->id;
            $user->update();
        }

        $formEntry->primary_contact_user_id = $user->id;
        $formEntry->update();
    }

    /**
     * When a form entry is submitted, this method is used on entry sections
     * that have form sections with `is_editor = true` to add the editor to the
     * form entry.
     *
     * Note: This is not the same as a user with a role of "Editor". An editor
     * in this case is a user that has edit rights (i.e. is allowed to generate
     * a form entry token to be used for editing).
     */
    private static function addEditor(
        self $formEntry,
        EntrySection $entrySection
    ) {
        // Check if the entry section contains an `email` entry field. If it
        // does not, abort!
        if (!$email = $entrySection->getFieldValue('email')) {
            $msg = 'The `email` field cannot be empty. Unable to add entry '
                 . 'section as an editor';
            Log::error($msg, [
                'entrySection' => $entrySection->toArray()
            ]);
            abort(500);
        }

        // If the user does not exist in the users table (determined by the
        // email), create the user with a role of "Contributor"
        if (!$user = User::findByEmail($email)) {
            $user = new User();
            $user->role_id = Role::findRole('Contributor')->id;
            $user->email = $email;
            $user->first_name = $entrySection->getFieldValue('first_name');
            $user->last_name = $entrySection->getFieldValue('last_name');
            $user->password = '';
            $user->save();
        }
        // If the user exists in the users table but is a Subscriber, bump their
        // role up to "Contributor".
        else if ($user->is_subscriber) {
            $user->role_id = Role::findRole('Contributor')->id;
            $user->update();
        }

        $formEntry->editors()->attach($user->id);
    }
}
