<?php

namespace App;

// Laravel.
use Illuminate\Database\Eloquent\Model;

// Models.
use App\FacilityRepository;

class Facility extends Model
{   
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['datePublished',
                        'dateUpdated'];
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'facilityRepositoryId',
        'organizationId',
        'provinceId',
        'name',
        'city',
        'website',
        'description',
        'descriptionNoHtml',
        'isPublic',
        'datePublished',
        'dateUpdated'
    ];
    
    /**
     * Relationship between a facility the revision (facility repository record) 
     * it belongs to.
     */
    public function currentRevision()
    {
        return $this->belongsTo('App\FacilityRepository',
            'facilityRepositoryId');
    }
    
    /**
     * Relationship between a facility and all its revisions (facility 
     * repository records).
     */
    public function revisions()
    {
        return $this->hasMany('App\FacilityRepository', 'facilityId');
    }
    
    /**
     * Relationship between a facility and the organization it belongs to.
     */
    public function organization()
    {
        return $this->belongsTo('App\Organization', 'organizationId');
    }
    
    /**
     * Relationship between a facility and the province it belongs to.
     */
    public function province()
    {
        return $this->belongsTo('App\Province', 'provinceId');
    }
    
    /**
     * Relationship between a facility and all the disciplines it belongs to.
     */
    public function disciplines()
    {
        return $this->belongsToMany('App\Discipline',
            'discipline_facility', 'facilityId', 'disciplineId');
    }
    
    /**
     * Relationship between a facility and all the sectors it belongs to.
     */
    public function sectors()
    {
        return $this->belongsToMany('App\Sector', 'facility_sector',
            'facilityId', 'sectorId');
    }
    
    /**
     * Relationship between a facility and its primary contact.
     */
    public function primaryContact()
    {
        return $this->hasOne('App\PrimaryContact', 'facilityId');
    }
    
    /**
     * Relationship between a facility and all its contacts.
     */
    public function contacts()
    {
        return $this->hasMany('App\Contact', 'facilityId');
    }
    
    /**
     * Relationship between a facility and all its equipment.
     */
    public function equipment()
    {
        return $this->hasMany('App\Equipment', 'facilityId');
    }
    
    /**
     * Scope for hidden facilities.
     */
    public function scopeHidden($query)
    {
        return $query->where('isPublic', false);
    }
    
    /**
     * Scope for public facilities.
     */
    public function scopeNotHidden($query)
    {
        return $query->where('isPublic', true);
    }
}
