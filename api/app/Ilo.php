<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ilo extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['dateAdded',
                        'created_at',
                        'updated_at'];
    
    public function organization()
    {
        return $this->belongsTo('App\Organization', 'organizationId');
    }
}
