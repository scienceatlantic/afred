<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacilityRepository extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['dateSubmitted',
                        'created_at',
                        'updated_at'];
    
    /**
     * Name of the database table. An exception had to be made here since
     * we're not calling it 'facility_repositories'. Otherwise, this
     * property wouldn't be necessary.
     */
    protected $table = 'facility_repository';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'facilityId',
        'state',
        'data',
        'dateSubmitted'
    ];
    
/**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];
    
    public function facility()
    {
        return $this->hasOne('App\Facility', 'facilityRepositoryId');
    }
    
    public function updateLinkBefore()
    {
        return $this->hasOne('App\FacilityUpdateLink', 'frIdBefore');
    }
    
    public function updateLinkAfter()
    {
        return $this->hasOne('App\FacilityUpdateLink', 'frIdAfter');
    }
}
