<?php

namespace App;

use App\FormEntry;
use App\FormEntryTokenStatus as TokenStatus;
use App\User;
use App\Events\FormEntryTokenCreated;
use Illuminate\Database\Eloquent\Model;
use Log;

class FormEntryToken extends Model
{
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'is_open',
        'is_locked',
        'is_closed',
        'is_unclosed',
        'wp_edit_url'
    ];

    /**
     * Relationship with the form entry before the update.
     */
    public function beforeUpdateFormEntry()
    {
        return $this->belongsTo(
            'App\FormEntry',
            'before_update_form_entry_id'
        );
    }

    /**
     * Relationship with the form entry after the update.
     */
    public function afterUpdateFormEntry()
    {
        return $this->belongsTo(
            'App\FormEntry',
            'after_update_form_entry_id'
        );
    }

    /**
     * Relationship with the user that opened the token.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * The form entry token's status.
     */
    public function status()
    {
        return $this->belongsTo(
            'App\FormEntryTokenStatus',
            'form_entry_token_status_id'
        );
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

    public function getIsOpenAttribute()
    {
        return $this->form_entry_token_status_id
            === TokenStatus::findStatus('Open')->id;
    }

    public function getIsLockedAttribute()
    {
        return $this->form_entry_token_status_id
            === TokenStatus::findStatus('Locked')->id;
    }

    public function getIsClosedAttribute()
    {
        return $this->form_entry_token_status_id
            === TokenStatus::findStatus('Closed')->id;
    }
    
    public function getIsUnclosedAttribute()
    {
        return $this->form_entry_token_status_id
            !== TokenStatus::findStatus('Closed')->id;
    }

    public function getWpEditUrlAttribute()
    {
        $openStatus = TokenStatus::findStatus('Open');
        if ($this->form_entry_token_status_id !== $openStatus->id) {
            return null;
        }

        $formEntry = $this->beforeUpdateFormEntry()->first();
        
        return $formEntry->form->directory->wp_base_url
            .'/?p='
            . $formEntry->form->wp_post_id
            . '&afredwp-form-entry-id='
            . $formEntry->id
            . '&afredwp-token='
            . $this->value;
    }

    public static function openToken(
        FormEntry $beforeUpdateFormEntry,
        User $user
    ) {
        if ($beforeUpdateFormEntry->tokens()->unclosed()->count()) {
            $msg = 'Attempting to open a new token where there\'s an open or '
                 . 'locked token already attached to this form entry';
            Log::error($msg, [
                'beforeUpdateFormEntry' => $beforeUpdateFormEntry->toArray(),
                'user' => $user->toArray()
            ]);
            abort(500);
        }

        $openStatus = TokenStatus::findStatus('Open');

        $token = new self();
        $token->before_update_form_entry_id = $beforeUpdateFormEntry->id;
        $token->resource_id = $beforeUpdateFormEntry->resource_id;
        $token->user_id = $user->id;
        $token->form_entry_token_status_id = $openStatus->id;
        $token->value = str_random(20);
        $token->save();

        event(new FormEntryTokenCreated($token));

        return $token;
    }

    public static function lockToken(
        self $token,
        FormEntry $afterUpdateFormEntry = null
    ) {
        if (!$token->is_open) {
            Log::error('Attempting to lock token that isn\'t open', [
                'token' => $token->toArray()
            ]);
            abort(500);
        }

        $lockStatus = TokenStatus::findStatus('Locked');

        if ($afterUpdateFormEntry) {
            $token->after_update_form_entry_id = $afterUpdateFormEntry->id;
        }
        $token->form_entry_token_status_id = $lockStatus->id;
        $token->update();

        return $token;
    }

    public static function closeToken(self $token)
    {
        if (!$token->is_unclosed) {
            Log::error('Attempting to close token that isn\'t open or locked', [
                'token' => $token->toArray()
            ]);
            abort(500);
        }

        $closeStatus = TokenStatus::findStatus('closed');
        $token->form_entry_token_status_id = $closeStatus->id;
        $token->update();

        return $token;
    }
}
