<?php

namespace App;

// Laravel.
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{   
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
        'id',
        'facilityId',
        'type',
        'manufacturer',
        'model',
        'purpose',
        'purposeNoHtml',
        'specifications',
        'specificationsNoHtml',
        'isPublic',
        'hasExcessCapacity',
        'yearPurchased',
        'yearManufactured',
        'keywords'
    ];
    
    /**
     * Relationship between an equipment and the facility it belongs to.
     */
    public function facility()
    {
        return $this->belongsTo('App\Facility', 'facilityId');
    }
    
    /**
     * Scope for hidden equipment.
     */
    public function scopeHidden($query)
    {
        return $query->where('isPublic', false);
    }
    
    /**
     * Scope for public equipent.
     */
    public function scopeNotHidden($query)
    {
        return $query->where('isPublic', true);
    }
    
    /**
     * Scope for excess capacity.
     *
     * @param bool $condition The default is true.
     */
    public function scopeExcessCapacity($query, $condition = true)
    {
        return $query->where('hasExcessCapacity', $condition);
    }
}
