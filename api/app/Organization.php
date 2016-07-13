<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
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
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'isHidden'
    ];
    
    public function ilo()
    {
        return $this->hasOne('App\Ilo', 'organizationId');
    }
    
    public function facilities() {
        return $this->hasMany('App\Facility', 'organizationId');
    }
    
    public function scopeNotHidden($query)
    {
        $query->where('isHidden', false);
    }
    
    public function scopeHidden($query)
    {
        $query->where('isHidden', true);
    }
}
