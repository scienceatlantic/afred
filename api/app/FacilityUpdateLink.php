<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacilityUpdateLink extends Model
{   
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['token'];
    
    public function facilityRepositoryBefore()
    {
        $this->belongsTo('App\FacilityRepository', 'fr_id_before');
    }
    
    public function facilityRepositoryAfter()
    {
        $this->belongsTo('App\FacilityRepository', 'fr_id_after');
    }
}
