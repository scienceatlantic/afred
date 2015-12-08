<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['datePublished',
                        'dateUpdated',
                        'created_at',
                        'updated_at'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'institutionId',
        'provinceId',
        'name',
        'city',
        'website',
        'description',
        'isPublic',
        'dateAdded'
    ];
    
    public function facilityRevisionHistory()
    {
        return $this->hasMany('App\FacilityRevisionHistory', 'facilityId');
    }
    
    public function institution()
    {
        return $this->belongsTo('App\Institution', 'institutionId');
    }
    
    public function province()
    {
        return $this->belongsTo('App\Province', 'provinceId');
    }
    
    public function primaryContact()
    {
        return $this->hasOne('App\PrimaryContact', 'facilityId');
    }
    
    public function contacts()
    {
        return $this->hasMany('App\Contact', 'facilityId');
    }
    
    public function equipment()
    {
        return $this->hasMany('App\Equipment', 'facilityId');
    }
}
