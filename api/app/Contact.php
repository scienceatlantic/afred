<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use \Eloquence\Database\Traits\CamelCaseModel;
    
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
