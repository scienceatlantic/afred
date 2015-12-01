<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use \Eloquence\Database\Traits\CamelCaseModel;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'institution_id',
        'province_id',
        'name',
        'city',
        'website',
        'description',
        'is_public'
    ];
    
    public function institution()
    {
        return $this->belongsTo('App\Institution');
    }
    
    public function province()
    {
        return $this->belongsTo('App\Province');
    }
    
    public function primaryContact()
    {
        return $this->hasOne('App\PrimaryContact');
    }
    
    public function contacts()
    {
        return $this->hasMany('App\Contact');
    }
    
    public function equipment()
    {
        return $this->hasMany('App\Equipment');
    }
}
