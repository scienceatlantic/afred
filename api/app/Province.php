<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['dateAdded',
                        'created_at',
                        'updated_at'];
    
    public function facilities() {
        return $this->hasMany('App\Facility', 'provinceId');
    }
}
