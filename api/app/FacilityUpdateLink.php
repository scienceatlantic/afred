<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacilityUpdateLink extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'frIdBefore',
        'frIdAfter',
        'editorFirstName',
        'editorLastName',
        'editorEmail',
        'token',
        'status',
        'dateRequested'
    ];
    
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['token'];
        
    public function scopeOpen($query)
    {
        return $query->where('status', 'OPEN');
    }
    
    public function scopePending($query)
    {
        return $query->where('status', 'PENDING');
    }
    
    public function scopeClosed($query)
    {
        return $query->where('status', 'CLOSED');
    }
    
    public function scopeNotClosed($query)
    {
        return $query->where('status', '<>','CLOSED');
    }
}
