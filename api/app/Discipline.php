<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discipline extends Model
{
    public function facilities()
    {
        return $this->belongsToMany('App\Facility', 'discipline_facility',
            'disciplineId', 'facilityId');
    }
}
