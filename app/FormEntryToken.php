<?php

namespace App;

use App\FormEntryTokenStatus as TokenStatus;
use Illuminate\Database\Eloquent\Model;

class FormEntryToken extends Model
{
    public function scopeOpen($query)
    {
        return $query->where(
            'form_entry_token_status_id',
            TokenStatus::findStatus('Open')
        );
    }

    public function scopeLocked($query)
    {
        return $query->where(
            'form_entry_token_status_id',
            TokenStatus::findStatus('Locked')
        );
    }
    
    public function scopeClosed($query)
    {
        return $query->where(
            'form_entry_token_status_id',
            TokenStatus::findStatus('Closed')
        );
    }

    public function scopeUnclosed($query)
    {
        return $query
            ->where(
                'form_entry_token_status_id',
                TokenStatus::findStatus('Open')
            )
            ->orWhere(
                'form_entry_token_status_id',
                TokenStatus::findStatus('Locked')
            );
    }
}
