<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at',
                        'updated_at'];
    
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
     * Relationship between a contact and the facility it belongs to.
     */
    public function facility()
    {
        return $this->belongsTo('App\Facility', 'facilityId');
    }
    
    /**
     * Returns a contact's full name.
     *
     * Note: This method can only be called on a single instance of a contact.
     */
    public function getFullName()
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}
