<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{        
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
