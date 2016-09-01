<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['dateCreated',
                        'dateUpdated'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'isHidden'
    ];
    
    /**
     * Relationship between a province and its facilities.
     */
    public function facilities() {
        return $this->hasMany('App\Facility', 'provinceId');
    }
    
    /**
     * Scope for public provinces.
     */
    public function scopeNotHidden($query)
    {
        return $query->where('isHidden', false);
    }
    
    /**
     * Scope for hidden provinces.
     */
    public function scopeHidden($query)
    {
        return $query->where('isHidden', true);
    }
}
