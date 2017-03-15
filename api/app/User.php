<?php

namespace App;

// Laravel.
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

// Misc.
use DB;
use Log;

// Models.
use App\Role;
use App\UserSetting;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['dateLastLogin',
                        'dateLastPinged',
                        'dateCreated',
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
        'firstName',
        'lastName',
        'email',
        'password'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    
    /**
     * Relationship between a user and their roles.
     */
    public function roles()
    {
        return $this->belongsToMany('App\Role', 'role_user', 'userId',
            'roleId');
    }

    /**
     * Relationship between a user and their reviewed facility repository
     * records. 
     */
    public function frs()
    {
        return $this->hasMany('App\FacilityRepository', 'reviewerId');
    }

    /**
     * Relationship between a user and their settings.
     */
    public function settings()
    {
        return $this->hasMany('App\UserSetting', 'userId');
    }

    /**
     * Active users scope.
     */
    public function scopeActive($query)
    {
        return $query->where('isActive', true);
    }

    /**
     * Inactive users scope.
     */
    public function scopeNotActive($query)
    {
        return $query->where('isActive', false);
    }

    /**
     * Super admin users scope.
     *
     * @uses User::role()
     *
     * @param boolean $strict Default is false. False = Super admin and up are 
     *     returned, true = only super admins are returned.
     */
    public function scopeSuperAdmins($query, $strict = false)
    {
        return $this->role($query, 'SUPER_ADMIN');
    }

    /**
     * Admin users scope.
     *
     * @uses User::role()
     *
     * @param boolean $strict Default is false. False = Admin and up are 
     *     returned, false = only admins are returned.
     */
    public function scopeAdmins($query, $strict = false)
    {
        return $this->role($query, 'ADMIN', $strict);
    }

    /**
     * Check if user is a SUPER ADMIN
     *
     * @uses User::isRole()
     *
     * @param boolean $strict Default is false. True = user must be assigned the
     *     SUPER ADMIN role explicitly for a true value to be returned, false = 
     *     a true value is returned even if the user is not an SUPER ADMIN 
     *     explicitly but is assigned a role with a higher permission level.
     *
     * @return boolean True is user a SUPER ADMIN.
     */
    public function isSuperAdmin($strict = false)
    {
        return $this->isRole('SUPER_ADMIN', $strict);
    }

    /**
     * Check if user is an ADMIN.
     *
     * @uses User::isRole()
     *
     * @param boolean $strict Default is false. True = user must be assigned the
     *     ADMIN role explicitly for a true value to be returned, false = a true
     *     value is returned even if the user is not an ADMIN explicitly but is
     *     assigned a role with a higher permission level.
     *
     * @return boolean True if user is an ADMIN.
     */
    public function isAdmin($strict = false)
    {    
        return $this->isRole('ADMIN', $strict);
    }
    
    /**
     * Gets a concatenated string of the user's first name and last name with a
     * space in between.
     *
     * @return string 
     */
    public function getFullName()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function lookup($name, $default = null, $keys = false, $query = null)
    {
        $query = $query ?: UserSetting::where('userId', $this->id);
        return UserSetting::lookup($name, $default, $keys, $query);
    }

    public function setting($name)
    {
        $query = UserSetting::where('userId', $this->id);
        return UserSetting::findByName($name, $query);
    }

    private function role($query, $role, $strict = true)
    {
        // Get permission level.
        $roleIds = Role::where('permission', $strict ? '=' : '>=', 
            Role::lookup($role))->get()->pluck('id');

        // Search bridge table for matching users and return if matches found.
        if ($ru = DB::table('role_user')->whereIn('roleId', $roleIds)->get()) {
            return $query->whereIn('id', collect($ru)->pluck('userId'));
        }

        // Otherwise, return empty collection.
        return $query->whereIn('id', [-1]);
    }

    private function isRole($role, $strict = true)
    {
        if ($strict) {
            return $this->getPermission($role) === Role::lookup($role);
        }
        return $this->getMaxPermission($role) >= Role::lookup($role);
    }

    private function getPermission($role)
    {
        if ($role = $this->roles()->where('name', $role)->first()) {
            return $role->permission;
        }
        Log::warning('`User::getPermission()` returning -1.', [
            'user'  => $this->toArray(),
            'roles' => $this->roles()->get()->toArray()
        ]);
        return -1;
    }

    public function getMaxPermission()
    {
        if ($role = $this->roles()->orderBy('permission', 'desc')->first()) {
            return $role->permission;
        }
        Log::warning('`User::getMaxPermission()` returning -1.', [
            'user'  => $this->toArray(),
            'roles' => $this->roles()->get()->toArray()
        ]);
        return -1;
    }
}
