<?php

namespace App;

// Laravel.
use Illuminate\Database\Eloquent\Model;

// Misc.
use DB;

// Models.
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
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    public $appends = ['facilityName',
                       'isBeingUpdated',
                       'isDeleted',
                       'isPublishedRevision',
                       'isPreviousRevision',
                       'isPublic',
                       'publishedId',
                       'unclosedUpdateRequest'];

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
     *
     * @var string
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
        'data' => 'array'
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
     * Relationship between a facility repository and its facility update links.
     *
     * Each facility repository record can have zero or more facility update
     * link records in the 'before' field. This is because the update request
     * could have been rejected by a user or have expired. Meaning that the
     * facility repository record (frIdBefore) is still the most recent
     * version of the facility.
     */
    public function updateRequests()
    {
        return $this->hasMany('App\FacilityUpdateLink', 'frIdBefore');
    }
    
    /**
     * Relationship between a facility repository and the facility update link
     * record that created it.
     *
     * Each facility repository record can only have one facility update link
     * record in the 'after' field. The update is either approved or rejected.
     * If it was rejected, the previous facility repository record (frIdBefore)
     * is still the most recent version of the facility.
     */
    public function originRequest()
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
        $fr = FacilityRepository::select(DB::raw('MAX(id) as id, facilityId'))
            ->whereNotIn('facilityId', Facility::all()->pluck('id')->toArray())
            ->whereNotNull('facilityId')
            ->where('state', '!=', 'PENDING_APPROVAL')
            ->where('state', '!=', 'PENDING_EDIT_APPROVAL')
            ->where('state', '!=', 'REJECTED')
            ->where('state', '!=', 'REJECTED_EDIT')
            ->groupBy('facilityId')
            ->get();

        return $query->whereIn('id', $fr->pluck('id')->toArray());
    }

    /**
     * Custom attribute accessor.
     *
     * @return int Id of published facility repository or -1 if not 
     *     applicable (i.e. record has been deleted or rejected).
     */
    public function getPublishedIdAttribute()
    {
        $f = $this->facility()->first();
        return $this->attributes['publishedId'] =
            $f ? $f->facilityRepositoryId : -1;
    }

    /**
     * Custom attribute accessor.
     *
     * @return int 1 = true, 0 = false, -1 = not applicable (i.e. record has 
     *     been deleted or rejected).
     */
    public function getIsPublishedRevisionAttribute()
    {
        $f = $this->facility()->first();
        return $this->attributes['isPublishedRevision'] =
            $f ? ($f->facilityRepositoryId === $this->id ? 1 : 0) : -1;
    }

    /**
     * Custom attribute accessor.
     *
     * @return int 1 = true, 0 = false, -1 = not applicable (i.e. record has 
     *     been deleted, rejected, or is pending edit approval).
     */
    public function getIsPreviousRevisionAttribute()
    {
        $f = $this->facility()->first();
        return $this->attributes['isPreviousRevision'] = 
            $f ? ($f->facilityRepositoryId !== $this->id 
                  && $this->state !== 'PENDING_EDIT_APPROVAL' ? 1 : 0) : -1;
            
    }

    /**
     * Custom attribute accessor.
     *
     * @return int 1 = true, 0 = false, -1 = not applicable (i.e. record is not
     *     published).
     */
    public function getIsPublicAttribute()
    {
        $f = $this->publishedFacility()->first();
        return $this->attributes['isPublic'] = $f ? $f->isPublic : -1;
    }

    /**
     * Custom attribute accessor.
     *
     * @return int 1 = true, 0 = false.
     */
    public function getIsDeletedAttribute()
    {
        return $this->attributes['isDeleted'] = 
            $this->removed()->where('facilityId', $this->facilityId)
            ->count() > 0 ? 1 : 0;
    }

    /**
     * Custom attribute accessor.
     *
     * @return string|null Name of facility.
     */
    public function getFacilityNameAttribute()
    {
        if (array_key_exists('facility', $this->data)) {
            return $this->attributes['facilityName'] =
                $this->data['facility']['name'];
        }
        return null;
    }

    /**
     * Custom attribute accessor.
     *
     * @return int 1 = true, 0 = false, -1 = not applicable (i.e. record is not 
     *     published).
     */
    public function getIsBeingUpdatedAttribute()
    {
        $f = $this->publishedFacility()->first();
        return $this->attributes['isBeingUpdated'] =
            $f ? ($this->updateRequests()->notClosed()->count() ? 1 : 0) : -1;
    }

    /**
     * Custom attribute accessor.
     *
     * @return model|null OPEN or PENDING facility update link record, null 
     *     if none exist.
     */
    public function getUnclosedUpdateRequestAttribute()
    {
        $f = $this->publishedFacility()->first();
        return $this->attributes['unclosedUpdateRequest'] = 
            $f ? $this->updateRequests()->notClosed()->first() : null;
    }
}
