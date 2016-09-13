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

    /**
     * Returns the maximum permission level.
     *
     * @param array $roleIds (Optional) If provided, query will narrow down by 
     *     the role IDs in this array.
     * @return integer Permission level or -1 if $roleIds doesn't contain any
     *     valid role IDs.
     */
    public function scopeMaxPermission($query, $roleIds = [])
    {
        // If an array of role IDs were provided, check that at least one of the
        // IDs are valid. If none of the IDs are valid, return -1.
        if ($len = count($roleIds)) {
            $found = false;
            for ($i = 0; $i < $len; $i++) {
                if (Role::find($roleIds[$i])) {
                    $query->whereIn('id', $roleIds);
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                return -1;
            }
        }
        return $query->max('permission');
    }
}
