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
    
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['token'];
    
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
}
