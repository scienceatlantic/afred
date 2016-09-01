<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discipline extends Model
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
     * Relationship between a discipline and all its facilities.
     */
    public function facilities()
    {
        return $this->belongsToMany('App\Facility', 'discipline_facility',
            'disciplineId', 'facilityId');
    }
}
