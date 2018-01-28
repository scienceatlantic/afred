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
        'token',
        'message',
        'notes',
        'isCacheValid',
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

    public static function saveEntry(Request $request, Form $form)
    {
        // Generate a new resource id.
        $resourceId = (self::max('resource_id') + 1);

        // Create new form entry
        $formEntry = new self();
        $formEntry->resource_id = $resourceId;
        $formEntry->form_id = $form->id;
        $formEntry->form_entry_status_id = Status::findStatus('Submitted')->id;
        $formEntry->save();

        // Attach compatible forms (i.e. directories).
        $formEntry->formsAttachedTo()->attach($request->forms);

        // Attach fields and values.
        foreach($request->sections as $section => $fieldsets) {
            // Check that the section exists, otherwise skip.
            $formSection = FormSection
                ::where('object_key', $section)
                ->first();
            if (!$formSection) {
                continue;
            }

            // Get all compatible form section IDs.
            $compatibleFormSectionIds = $formSection->compatibleFormSections()
                ->pluck('compatible_form_section_id');

            // Create entry sections for each fieldset.
            foreach($fieldsets as $fieldset) {
                // Create entry section.
                $entrySection = new EntrySection();
                $entrySection->form_entry_id = $formEntry->id;
                $entrySection->form_section_id = $formSection->id;
                $entrySection->save();

                // Attach compatible form sections.
                $entrySection->formSectionsAttachedTo()
                    ->attach($compatibleFormSectionIds);

                // Create fields.
                foreach($formSection->formFields as $formField) {
                    // Get value of field if it exists, otherwise skip.
                    if (isset($fieldset[$formField->object_key])) {
                        $value = $fieldset[$formField->object_key];
                    } else {
                        continue;
                    }

                    // Create field.
                    $entryField = new EntryField();
                    $entryField->entry_section_id = $entrySection->id;
                    $entryField->form_field_id = $formField->id;
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

    public static function updateEntry(FormEntry $formEntry)
    {
        
    }

    public function updateStatus($formEntryStatusId)
    {
        $this->form_entry_status_id = $formEntryStatusId;
        $this->cache = null;
        $this->update();
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
            'resource'       => []
        ];

        foreach(FormEntryStatus::all() as $status) {
            $data['is_' . strtolower($status->name)]
                = $formEntry->status->name === $status->name;
        }

        foreach($formEntry->entrySections as $entrySection) {
            $formSection = $entrySection->formSection;

            if (!isset($data['resource'][$formSection->object_key])) {
                $data['resource'][$formSection->object_key] = [];
            }

            $fields = [];
            // TODO: COMMENT HERE!
            $fields['entry_section_id'] = $entrySection->id;

            foreach($entrySection->entryFields as $entryField) {
                $fields[$entryField->formField->object_key]
                    = $entryField->value;
            }
            array_push($data['resource'][$formSection->object_key], $fields);
        }

        // Get pagination title
        $sectionKey = $formEntry->form->pagination_section_object_key;
        $fieldKey = $formEntry->form->pagination_field_object_key;
        if (isset($data['resource'][$sectionKey][0][$fieldKey])) {
            $data['title'] = $data['resource'][$sectionKey][0][$fieldKey];
        }

        // Update cache
        $formEntry->cache = $data;
        $formEntry->update();

        return $data;
    }
}
