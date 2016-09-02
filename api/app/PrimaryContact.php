<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrimaryContact extends Model
{
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
        'facilityId',
        'firstName',
        'lastName',
        'email',
        'telephone',
        'extension',
        'position',
        'website'
    ];
    
    /**
     * Relationship between a primary contact and the facility it belongs to.
     */
    public function facility()
    {
        return $this->belongsTo('App\Facility', 'facilityId');
    }
    
    /**
     * Returns the primary contact's full name.
     *
     * Note: This method can only be called on single instance of a primary
     * contact.
     */
    public function getFullName()
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}
