<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['dateCreated',
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
        'name'
    ];
    
    /**
     * Relationship between sectors and its facilities.
     */
    public function facilities()
    {
        return $this->belongsToMany('App\Facility', 'facility_sector',
            'sectorId', 'facilityId');
    }
}
