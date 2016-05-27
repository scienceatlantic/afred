<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['dateCreated',
                        'dateUpdated',
                        'created_at',
                        'updated_at'];
    
    public function facilities()
    {
        return $this->belongsToMany('App\Facility', 'facility_sector',
            'sectorId', 'facilityId');
    }
}
