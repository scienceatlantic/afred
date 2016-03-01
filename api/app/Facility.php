<?php

namespace App;

// Laravel.
use Illuminate\Database\Eloquent\Model;

// Misc.
use Sofa\Eloquence\Eloquence;

// Models.
use App\FacilityRepository;

class Facility extends Model
{
    use Eloquence;
    
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
    
    public function revision()
    {
        return $this->belongsTo('App\FacilityRepository',
            'facilityRepositoryId');
    }
    
    public function revisions()
    {
        return $this->hasMany('App\FacilityRepository', 'facilityId');
    }
    
    public function organization()
    {
        return $this->belongsTo('App\Organization', 'organizationId');
    }
    
    public function province()
    {
        return $this->belongsTo('App\Province', 'provinceId');
    }
    
    public function disciplines()
    {
        return $this->belongsToMany('App\Discipline',
            'discipline_facility', 'facilityId', 'disciplineId');
    }
    
    public function sectors()
    {
        return $this->belongsToMany('App\Sector', 'facility_sector',
            'facilityId', 'sectorId');
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
