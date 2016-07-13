<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Facility;

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
        'reviewerId',
        'facilityId',
        'state',
        'reviewerMessage',
        'data'
    ];
    
/**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];
    
    public function publishedFacility()
    {
        return $this->hasOne('App\Facility', 'facilityRepositoryId');
    }
    
    public function facility()
    {
        return $this->belongsTo('App\Facility', 'facilityId');
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
    
    public function scopePendingApproval($query, $includeEdits = false)
    {
        $query->where('state', 'PENDING_APPROVAL');
        
        if ($includeEdits) {
            $query->orWhere('state', 'PENDING_EDIT_APPROVAL');
        }
        
        return $query;
    }
    
    public function scopePendingEditApproval($query)
    {
        return $query->where('state', 'PENDING_EDIT_APPROVAL');
    }
    
    public function scopePublished($query, $isPublic = -1)
    {
        $f = Facility::query();
        
        if ($isPublic != -1) {
            $f->where('isPublic', $isPublic);
        }
        
        $query->whereIn('id', $f->select('facilityRepositoryId')->get());        
    }
    
    public function scopeRejected($query, $includeEdits = false)
    {
        $query->where('state', 'REJECTED');
        
        if ($includeEdits) {
            $query->orWhere('state', 'REJECTED_EDIT');
        }
        
        return $query;
    }
    
    public function scopeRejectedEdit($query)
    {
        return $query->where('state', 'REJECTED_EDIT');
    }
    
    public function scopeDeleted($query)
    {
        $query->whereNotIn('facilityId', Facility::select('id')->get())
            ->where('state', '!=', 'PENDING_APPROVAL')
            ->where('state', '!=', 'PENDING_EDIT_APPROVAL')
            ->groupBy('facilityId');
    }
}
