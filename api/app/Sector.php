<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    public function facilities()
    {
        return $this->belongsToMany('App\Facility', 'facility_sector',
            'sectorId', 'facilityId');
    }
}
