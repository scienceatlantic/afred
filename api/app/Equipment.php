<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use \Eloquence\Database\Traits\CamelCaseModel;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'facility_id',
        'type',
        'manufacturer',
        'model',
        'purpose',
        'specifications',
        'is_public',
        'has_excess_capacity'
    ];
    
    public function facility()
    {
        return $this->belongsTo('App\Facility');
    }
}
