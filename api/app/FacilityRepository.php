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
                        'dateReviewed'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    
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
    
    /**
     * Relationship between a facility repository record and its published
     * (contained in the 'facilities' table) facility.
     *
     * Note: We can use this relationship to determine if a facility repository
     * record is the current published record.
     */
    public function publishedFacility()
    {
        return $this->hasOne('App\Facility', 'facilityRepositoryId');
    }
    
    /**
     * Relationship between a facility repository and its facility (regardless 
     * of whether it's published or not).
     *
     * Note: We can use this relationship to determine if a reviewed record has
     * been deleted.
     */
    public function facility()
    {
        return $this->belongsTo('App\Facility', 'facilityId');
    }
    
    /**
     * Relationship between a facility repository and the user that reviewed it.
     */
    public function reviewer()
    {
        return $this->belongsTo('App\User', 'reviewerId');
    }
    
    /**
     * Relationship between a facility repository and its facility update links
     * (before).
     *
     * Each facility repository record can have zero or more facility update
     * link records in the 'before' field. This is because the update request
     * could have been rejected by a user or have expired. Meaning that the
     * facility repository record (frIdBefore) is still the most recent
     * version of the facility.
     */
    public function fulB()
    {
        return $this->hasMany('App\FacilityUpdateLink', 'frIdBefore');
    }
    
    /**
     * Relationship between a facility repository and its facility update link
     * (after).
     *
     * Each facility repository record can only have one facility update link
     * record in the 'after' field. The update is either approved or rejected.
     * If it was rejected, the previous facility repository record (frIdBefore)
     * is still the most recent version of the facility.
     */
    public function fulA()
    {
        return $this->hasOne('App\FacilityUpdateLink', 'frIdAfter');
    }
    
    /**
     * Scope for PENDING_APPROVAL records.
     * 
     * @param bool $includeEdits Include 'PENDING_EDIT_APPROVAL' records. The
     *    default is false.
     */
    public function scopePendingApproval($query, $includeEdits = false)
    {
        $query->where('state', 'PENDING_APPROVAL');
        
        if ($includeEdits) {
            $query->orWhere('state', 'PENDING_EDIT_APPROVAL');
        }
        
        return $query;
    }
    
    /**
     * Scope for PENDING_EDIT_APPROVAL records.
     */
    public function scopePendingEditApproval($query)
    {
        return $query->where('state', 'PENDING_EDIT_APPROVAL');
    }
    
    /**
     * Scope for PUBLISHED and PUBLISHED_EDIT records.
     *
     * Note: This scope only returns actual (facility repository records that
     * are linked to facility records) published records. It does not return
     * just any facility repository record with a state of either
     * PUBLISHED or PUBLISHED_EDIT because those records might be older
     * revisions or the record might have been deleted (no link to a record
     * in the facilities table).
     *
     * @param bool $isPublic If true, only public published records are 
     *     returned and if false, only private published records are returned.
     *     If not specified, either (public or private) records are returned.
     */
    public function scopePublished($query, $isPublic = -1)
    {
        $f = Facility::select('facilityRepositoryId');
        if ($isPublic !== -1) {
            $f->where('isPublic', $isPublic);
        }
        $query->whereIn('id', $f->get());        
    }
    
    /**
     * Scope for REJECTED records.
     *
     * @param bool $includeEdits Default is to exclude REJECTED_EDIT records.
     *    Warning: If this is set to true and REJECTED_EDITS are included, it
     *    may include records that have been deleted (no link to a record in
     *    facilities table).
     */
    public function scopeRejected($query, $includeEdits = false)
    {
        $query->where('state', 'REJECTED');
        if ($includeEdits) {
            $query->orWhere('state', 'REJECTED_EDIT');
        }
        return $query;
    }
    
    /**
     * Scope for REJECTED_EDIT records.
     *
     * Warning: This scope might return records that been deleted (no link to a
     * record in the facilities table).
     */
    public function scopeRejectedEdit($query)
    {
        return $query->where('state', 'REJECTED_EDIT');
    }
    
    /**
     * Scope for deleted records.
     *
     * Note: 'Deleted' might be a reserved word in Laravel because naming the
     * function that causes issues. 
     * 
     * A deleted facility repository record is a reviewed record that does not
     * have a related record in the facilities table.
     */
    public function scopeRemoved($query)
    {
        return $query->whereNotIn('facilityId', Facility::select('id')->get())
            ->where('state', '!=', 'PENDING_APPROVAL')
            ->where('state', '!=', 'PENDING_EDIT_APPROVAL')
            ->where('state', '!=', 'REJECTED')
            ->where('state', '!=', 'REJECTED_EDIT')
            ->groupBy('facilityId');
    }
}
