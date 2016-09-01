<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacilityUpdateLink extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['dateOpened',
                        'datePending',
                        'dateClosed'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'frIdBefore',
        'frIdAfter',
        'editorFirstName',
        'editorLastName',
        'editorEmail',
        'token',
        'status'
    ];
    
    /**
     * Relationship between facility update link and its facility repository
     * after it has been reviewed.
     */
    public function frA()
    {
        return $this->belongsTo('App\FacilityRepository', 'frIdAfter');
    }    
    
    /**
     * Relationship between facility update link and its facility repository
     * before it has been reviewed.
     */
    public function frB()
    {
        return $this->belongsTo('App\FacilityRepository', 'frIdBefore');
    }
    
    /**
     * Scope for OPEN records.
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'OPEN');
    }
    
    /**
     * Scope for PENDING records.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'PENDING');
    }
    
    /**
     * Scope for CLOSED records.
     */
    public function scopeClosed($query)
    {
        return $query->where('status', 'CLOSED');
    }
    
    /**
     * Scope for records that not CLOSED.
     */
    public function scopeNotClosed($query)
    {
        return $query->where('status', '<>','CLOSED');
    }
    
    /**
     * Get the full name of the editor.
     * 
     * Note: This method can only be called on a single instance of a facility
     * update link record.
     */
    public function getFullName()
    {
        return $this->editorFirstName . ' ' . $this->editorLastName;
    }
    
    /**
     * Verifies that the token provided matches a record with an OPEN status.
     *
     * @param integer $frIdBefore Facility repository ID (before the update)
     *     was made.
     * @param string $token The token to match.
     */
    public static function verifyToken($frIdBefore, $token)
    {
        return (bool) FacilityUpdateLink::where('frIdBefore', $frIdBefore)
            ->where('token', $token)->where('status', 'OPEN')->first();
    }
}
