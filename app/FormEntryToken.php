<?php

namespace App;

use App\FormEntry;
use App\FormEntryTokenStatus as TokenStatus;
use Illuminate\Database\Eloquent\Model;

class FormEntryToken extends Model
{
    public function formEntry()
    {
        return $this->belongsTo('App\FormEntry');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function scopeOpen($query)
    {
        return $query->where(
            'form_entry_token_status_id',
            TokenStatus::findStatus('Open')->id
        );
    }

    public function scopeLocked($query)
    {
        return $query->where(
            'form_entry_token_status_id',
            TokenStatus::findStatus('Locked')->id
        );
    }
    
    public function scopeClosed($query)
    {
        return $query->where(
            'form_entry_token_status_id',
            TokenStatus::findStatus('Closed')->id
        );
    }

    public function scopeUnclosed($query)
    {
        return $query
            ->where(
                'form_entry_token_status_id',
                TokenStatus::findStatus('Open')->id
            )
            ->orWhere(
                'form_entry_token_status_id',
                TokenStatus::findStatus('Locked')->id
            );
    }

    public function getEditUrlAttribute()
    {
        $formEntry = $this->formEntry()->first();
        
        return $formEntry->form->directory->wp_base_url
            .'/?p='
            . $formEntry->form->wp_post_id
            . '&afredwp-form-entry-id='
            . $formEntry->id
            . '&afredwp-token='
            . $this->value;
    }    
}
