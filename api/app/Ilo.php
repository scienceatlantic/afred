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
                        'dateUpdated',
                        'created_at',
                        'updated_at'];
    
    public function organization()
    {
        return $this->belongsTo('App\Organization', 'organizationId');
    }
    
    public function getFullName()
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}
