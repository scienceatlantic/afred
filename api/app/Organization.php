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
    protected $dates = ['dateAdded',
                        'created_at',
                        'updated_at'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'dateAdded'
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
}
