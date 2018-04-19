<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use View;

class Form extends Model
{
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'submission_messages'
    ];

    /**
     * Relationship with the directory it belongs to.
     */
    public function directory()
    {
        return $this->belongsTo('App\Directory');
    }

    /**
     * Relationship with all the forms this form is compatible with.
     */
    public function compatibleForms()
    {
        return $this->belongsToMany(
                'App\Form',
                'form_form', 
                'form_id', 
                'compatible_form_id'
            )
            ->withTimestamps();
    }

    /**
     * Relationship with all the form section it has.
     */
    public function formSections()
    {
        return $this->hasMany('App\FormSection');
    }

    /**
     * Relationship with all the form fields it has (via form sections).
     */
    public function formFields()
    {
        return $this->hasManyThrough('App\FormField', 'App\FormSection');
    }

    /**
     * Relationship with all the form entries it has.
     */
    public function formEntries()
    {
        return $this->hasMany('App\FormEntry');
    }

    /**
     * Relationship with all the form reports it has.
     */
    public function formReports()
    {
        return $this->hasMany('App\FormReport');
    }

    /**
     * Relationship with all the searc sections it has (via form sections).
     */
    public function searchSections()
    {
        return $this->hasManyThrough('App\SearchSection', 'App\FormSection');
    }

    /**
     * Dynamic attribute that contains all the submission messages (i.e.
     * submission successfully received, etc.) that the client WordPress plugin
     * can use.
     */
    public function getSubmissionMessagesAttribute()
    {
        $form = $this->fresh();

        $template = 'wp.'
            . $form->directory->resource_folder
            . '.forms.'
            . $form->resource_folder
            . '.';

        // Can't pass `$form` itself, because that will cause an internal 
        // infinite loop because each instance is trying to retrieve this
        // attribute (i.e. $form->submission_messages).
        $data = [
            'directory' => $form->directory
        ];

        return [
            'successful' => View::make(
                $template . 'submission-successful',
                $data
            )->render(),
            'successful_edit' => View::make(
                $template . 'submission-edit-successful',
                $data
            )->render(),
            'failed' => View::make(
                $template . 'submission-failed',
                $data
            )->render(),
            'failed_edit' => View::make(
                $template . 'submission-edit-failed',
                $data
            )->render(),    
        ];
    }
}
