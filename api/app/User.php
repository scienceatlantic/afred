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
     * Relationship between a user and its roles.
     */
    public function roles()
    {
        return $this->belongsToMany('App\Role', 'role_user', 'userId',
            'roleId');
    }

    /**
     * Relationship between a user and its settings.
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
     * Check if user is at least a SUPER ADMIN.
     *
     * @return boolean True is user is at least a SUPER ADMIN.
     */
    public function isAtLeastSuperAdmin()
    {
        return $this->getMaxPermission() >= Role::lookup('SUPER_ADMIN');
    }

    /**
     * Check if user is at least an ADMIN.
     *
     * @return boolean True if user is at least an ADMIN.
     */
    public function isAtLeastAdmin()
    {    
        return $this->getMaxPermission() >= Role::lookup('ADMIN');
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

    /**
     * Get the user's max permission level.
     */
    public function getMaxPermission()
    {
         return $this->roles()->orderBy('permission', 'desc')->first()
            ->value('permission');       
    }

    /**
     * Retrieves a setting value.
     * 
     * @see UserSetting:lookup() for how this works.
     */
    public function lookup($name, $default = null)
    {
        return UserSetting::lookup($this->id, $name, $default);
    }
}
