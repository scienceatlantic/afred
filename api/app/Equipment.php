<?php

namespace App;

// Laravel.
use Illuminate\Database\Eloquent\Model;

// Misc.
use Sofa\Eloquence\Eloquence;

class Equipment extends Model
{
    use Eloquence;
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at',
                        'updated_at'];
    
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
    
    public function facility()
    {
        return $this->belongsTo('App\Facility', 'facilityId');
    }
    
    public function scopeHidden($query)
    {
        return $query->where('isPublic', false);
    }
    
    public function scopeNotHidden($query)
    {
        return $query->where('isPublic', true);
    }
    
    public function scopeExcessCapacity($query, $condition = true)
    {
        return $query->where('hasExcessCapacity', $condition);
    }
}
