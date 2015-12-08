<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['dateAdded',
                        'created_at',
                        'updated_at'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'dateAdded'
    ];
    
    public function ilo()
    {
        return $this->hasOne('App\Ilo', 'institutionId');
    }
    
    public function facilities() {
        return $this->hasMany('App\Facility', 'institutionId');
    }
}
