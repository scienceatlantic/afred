<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\FacilityRevisionHistory;

class Facility extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['dateSubmitted',
                        'dateUpdated',
                        'created_at',
                        'updated_at'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'facilityRepositoryId',
        'organizationId',
        'provinceId',
        'name',
        'city',
        'website',
        'description',
        'isPublic',
        'dateSubmitted',
        'dateUpdated'
    ];
    
    public function currentRevision()
    {
        return $this->belongsTo('App\FacilityRevisionHistory',
            'facilityRevisionHistoryId');
    }
    
    public function facilityRevisionHistory()
    {
        return $this->hasMany('App\FacilityRevisionHistory', 'facilityId');
    }
    
    public function organization()
    {
        return $this->belongsTo('App\Organization', 'organizationId');
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
