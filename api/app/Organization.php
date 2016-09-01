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
                        'dateUpdated'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    
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
     * Relationship between an organization and its ILO.
     */
    public function ilo()
    {
        return $this->hasOne('App\Ilo', 'organizationId');
    }
    
    /**
     * Relationship between an organization and its facilities.
     */
    public function facilities() {
        return $this->hasMany('App\Facility', 'organizationId');
    }
    
    /**
     * Scope for public organizations.
     */
    public function scopeNotHidden($query)
    {
        $query->where('isHidden', false);
    }
    
    /**
     * Scope for hidden organizations.
     */
    public function scopeHidden($query)
    {
        $query->where('isHidden', true);
    }
}
