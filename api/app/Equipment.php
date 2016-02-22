<?php

namespace App;

// Laravel.
use Illuminate\Database\Eloquent\Model;

// Misc.
use Sofa\Eloquence\Eloquence;

class Equipment extends Model
{
    use Eloquence;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'facilityId',
        'type',
        'manufacturer',
        'model',
        'purpose',
        'specifications',
        'isPublic',
        'hasExcessCapacity'
    ];
    
    public function facility()
    {
        return $this->belongsTo('App\Facility', 'facilityId');
    }
}
