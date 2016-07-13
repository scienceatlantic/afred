<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrimaryContact extends Model
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
    
    public function facility()
    {
        return $this->belongsTo('App\Facility', 'facilityId');
    }
    
    public function getFullName()
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}
