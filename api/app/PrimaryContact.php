<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrimaryContact extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'facility_id',
        'first_name',
        'last_name',
        'email',
        'telephone',
        'extension',
        'position',
        'website'
    ];
    
    public function facility()
    {
        return $this->belongsTo('App\Facility');
    }
}
