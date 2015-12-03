<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{   
    public function facilities() {
        return $this->hasMany('App\Facility');
    }
    
    public function facilityRevisionHistory()
    {
        return $this->hasMany('App\FacilityRevisionHistory');
    }
}
