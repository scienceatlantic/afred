<?php

namespace App;

// Laravel.
use Illuminate\Database\Eloquent\Model;

// Misc.
use Log;

class Role extends Model
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
        'permission'
    ];
    
    /**
     * Relationship between roles and users.
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'role_user', 'roleId',
            'userId');
    }

    /**
     * Scope for the SUPER_ADMIN record.
     */
    public function scopeSuperAdmin($query)
    {
        return $query->where('name', 'SUPER_ADMIN')->first();
    }
    
    /**
     * Scope for the ADMIN record.
     */
    public function scopeAdmin($query)
    {
        return $query->where('name', 'ADMIN')->first();
    }

    /**
     * Returns a particular role's permission level.
     * 
     * @param string Name of the role. If an invalid role is provided, it will
     * be logged and the application will be aborted with an HTTP 500.
     *
     * @return integer
     */
    public function scopeLookup($query, $role)
    {
        if (!$role = $query->where('name', $role)->first()) {
            Log::error('Role not found. Aborting!', [
                'role' => $role
            ]);
            abort(500);
        }
        return $role->permission;
    }
}
