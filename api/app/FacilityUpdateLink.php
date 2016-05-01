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
                        'dateClosed',
                        'created_at',
                        'updated_at'];
    
    public function frA()
    {
        return $this->belongsTo('App\FacilityRepository', 'frIdAfter');
    }    
    
    public function frB()
    {
        return $this->belongsTo('App\FacilityRepository', 'frIdBefore');
    }
    
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
    
    /**
     * Verifies that the token provided matches a record with an open status.
     *
     * @param {integer} $frIdBefore Facility repository ID (before the update)
     *     was made.
     * @param {string} $token The token to match.
     */
    public static function verifyToken($frIdBefore, $token)
    {
        return (bool) FacilityUpdateLink::where('frIdBefore', $frIdBefore)
            ->where('token', $token)->where('status', 'OPEN')->first();
    }
}
