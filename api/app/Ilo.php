<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ilo extends Model
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
        'organizationId',
        'firstName',
        'lastName',
        'email',
        'telephone',
        'extension',
        'position',
        'website'
    ];
    
    /**
     * Relationship between an ILO and the organization it belongs to.
     */
    public function organization()
    {
        return $this->belongsTo('App\Organization', 'organizationId');
    }
    
    /**
     * Returns the ILO's full name.
     * 
     * Note: This method can only be used on a single instance of an ILO.
     */
    public function getFullName()
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}
