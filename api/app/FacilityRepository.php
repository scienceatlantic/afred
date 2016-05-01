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
                        'dateReviewed',
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
        'reviewerId',
        'state',
        'data',
        'reviewerMessage',
        'dateSubmitted',
        'dateReviewed'
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
    
    public function reviewer()
    {
        return $this->belongsTo('App\User', 'reviewerId');
    }
    
    /**
     * Each facility repository record can have zero or more facility update
     * link records in the 'before' field. This is because the update request
     * could have been rejected by the admin or have expired. Meaning that
     * the facility repository record (frIdBefore) is still the most recent
     * version of the facility.
     */
    public function fulB()
    {
        return $this->hasMany('App\FacilityUpdateLink', 'frIdBefore');
    }
    
    /**
     * Each facility repository record can only have one facility update link
     * record in the 'after' field. The update is either approved or rejected.
     * If it was rejected, the previous facility repository record (frIdBefore)
     * is still the most recent version of the facility.
     */
    public function fulA()
    {
        return $this->hasOne('App\FacilityUpdateLink', 'frIdAfter');
    }
}
