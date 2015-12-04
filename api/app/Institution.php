<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
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
        return $this->hasOne('App\Ilo', 'institutionId');
    }
    
    public function facilities() {
        return $this->hasMany('App\Facility', 'institutionId');
    }
}
