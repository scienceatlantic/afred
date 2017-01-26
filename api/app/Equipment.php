<?php

namespace App;

// Laravel.
use Illuminate\Database\Eloquent\Model;

// Misc.
use Laravel\Scout\Searchable;

// Models. 
use App\Facility;

class Equipment extends Model
{   
    use Searchable;
    
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
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return config('scout.prefix') . 'equipment';
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        // Get a fresh copy instead of lazy loading on the actual model 
        // instance.
        $e = Equipment::find($this->id);

        // If either the facility it belongs to or the equipment itself is 
        // not public, return an empty array.
        if (!$e->facility->isPublic) {
            return [];
        }
        if (!$e->isPublic) {
            return [];
        }

        $e->facility->contacts;
        $e->facility->equipment;
        $e->facility->disciplines;
        $e->facility->organization;
        $e->facility->organization->ilo;
        $e->facility->primaryContact;
        $e->facility->province;        
        $e->facility->sectors;

        return $e->toArray();
    }
    
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
    public function scopeHidden($query, 
        $equipmentFromHiddenFacilitiesAlsoCountAsHidden = false)
    {
        $query->where('isPublic', false);

        if ($equipmentFromHiddenFacilitiesAlsoCountAsHidden) {
            $query->whereIn('facilityId', Facility::hidden()->pluck('id'), 
                'or');
        }

        return $query;
    }
    
    /**
     * Scope for public equipent.
     */
    public function scopeNotHidden($query, 
        $equipmentFromHiddenFacilitiesAlsoCountAsHidden = false)
    {
        if ($equipmentFromHiddenFacilitiesAlsoCountAsHidden) {
            $query->whereNotIn('facilityId', Facility::hidden()->pluck('id'));
        }

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
