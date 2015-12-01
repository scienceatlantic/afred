<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    use \Eloquence\Database\Traits\CamelCaseModel;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];
    
    public function ilo()
    {
        return $this->hasOne('App\Institution');
    }
    
    public function facilities() {
        return $this->hasMany('App\Facility');
    }
    
    public function facilityRevisionHistory()
    {
        return $this->hasMany('App\Facility');
    }
}
