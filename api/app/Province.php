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
    
    public function facilities() {
        return $this->hasMany('App\Facility', 'provinceId');
    }
    
    public function scopeNotHidden($query)
    {
        return $query->where('isHidden', false);
    }
    
    public function scopeHidden($query)
    {
        return $query->where('isHidden', true);
    }
}
