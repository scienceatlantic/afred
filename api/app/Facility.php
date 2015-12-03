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
        'institutionId',
        'provinceId',
        'name',
        'city',
        'website',
        'description',
        'isPublic'
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
