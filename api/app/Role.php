<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['dateCreated',
                        'created_at',
                        'updated_at'];
    
    public function users()
    {
        return $this->belongsToMany('App\User', 'role_user', 'roleId',
            'userId');
    }
    
    public function scopeAdmin($query)
    {
        return $query->where('name', 'Admin')->first();
    }
}
