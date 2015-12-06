<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacilityRevisionHistory extends Model
{   
    /**
     * Name of the database table. An exception had to be made here since
     * we're not calling it 'facility_revision_histories'. Otherwise, this
     * property wouldn't be necessary.
     */
    protected $table = 'facility_revision_history';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'facilityId',
        'provinceId',
        'institutionId',
        'state',
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
    
    public function facility()
    {
        $this->belongsTo('App\Facility', 'facilityId');
    }
}
