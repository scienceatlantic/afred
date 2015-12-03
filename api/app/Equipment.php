<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{    
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
        return $this->belongsTo('App\Facility');
    }
}
