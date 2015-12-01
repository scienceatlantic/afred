<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacilityRevisionHistory extends Model
{
    use \Eloquence\Database\Traits\CamelCaseModel;
    
    /**
     * Name of the database table. An exception had to be made here since
     * we're not calling it 'facility_revision_histories'. Otherwise, this
     * property wouldn't be necessary.
     */
    protected $table = 'facility_revision_history';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'facility_id',
        'province_id',
        'institution_id',
        'state',
        'facility_in_json'
    ];
    
    public function facility()
    {
        $this->hasOne('App\Facility');
    }
}
