<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use \Eloquence\Database\Traits\CamelCaseModel;
    
    public function facilities() {
        return $this->hasMany('App\Facility');
    }
    
    public function facilityRevisionHistory()
    {
        return $this->hasMany('App\FacilityRevisionHistory');
    }
}
