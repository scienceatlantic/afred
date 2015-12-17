<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacilityEditRequest extends Model
{   
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['token'];
    
    public function frhBeforeUpdate()
    {
        $this->belongsTo('App\FacilityRevisionHistory', 'frhBeforeUpdateId');
    }
    
    public function frhAfterUpdate()
    {
        $this->belongsTo('App\FacilityRevisionHistory', 'frhAfterUpdateId');
    }
}
