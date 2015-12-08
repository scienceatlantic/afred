<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacilityEditRequest extends Model
{
    
    public function revisionBeforeEdit()
    {
        $this->belongsTo('App\FacilityRevisionHistory', 'frhIdBeforeEdit');
    }
    
    public function revisionAfterEdit()
    {
        $this->belongsTo('App\FacilityRevisionHistory', 'frhIdAfterEdit');
    }
}
