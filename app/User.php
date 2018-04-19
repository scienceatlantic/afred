<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'name',
        'is_administrator',
        'is_editor',
        'is_at_least_editor',
        'is_author',
        'is_at_least_author',
        'is_contributor',
        'is_at_least_contributor',
        'is_subscriber'
    ];    

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Relationship with the role it belongs to.
     */
    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    /**
     * Relationship with the directories it is "in charge" of.
     * 
     * See GitHub documentation for more details about this relationship.
     */
    public function directories()
    {
        return $this->belongsToMany('App\Directory')->withTimestamps();
    }

    /**
     * Relationship with the form entries it has "edit" rights to.
     */
    public function formEntries()
    {
        return $this->belongsToMany('App\FormEntry')->withTimestamps();
    }

    /**
     * Is active scope.
     * 
     * I.e. an inactive user won't be able to login and will not receive any
     * notification emails.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Administrator role scope.
     */
    public function scopeAdministrators($query)
    {
        return $query->where(
            'role_id',
            Role::findRole('Administrator')->id
        );
    }

    /**
     * Editor role scope.
     */
    public function scopeEditors($query)
    {
        return $query->where(
            'role_id',
            Role::findRole('Editor')->id
        );
    }
    
    /**
     * Author role scope.
     */    
    public function scopeAuthors($query)
    {
        return $query->where(
            'role_id',
            Role::findRole('Author')->id
        );
    }
    
    /**
     * Contributor role scope.
     */    
    public function scopeContributors($query)
    {
        return $query->where(
            'role_id',
            Role::findRole('Contributor')->id
        );
    }
    
    /**
     * Subscriber role scope.
     */    
    public function scopeSubscribers($query)
    {
        return $query->where(
            'role_id',
            Role::findRole('Subscriber')->id
        );
    }

    /**
     * Find user by their WordPress username
     * 
     * @param {string} $wpUsername
     */
    public static function findByWpUsername($wpUsername)
    {
        return self::where('wp_username', $wpUsername)->first();
    }

    /**
     * Find user by their email address
     * 
     * @param {string} $email
     */    
    public static function findByEmail($email)
    {
        return self::where('email', $email)->first();
    }

    /**
     * Find user by their email address or fail (HTTP 404) if user is not found.
     * 
     * @param {string} $email
     */
    public static function findByEmailOrFail($email)
    {
        return self::findByEmail($email) ?: abort(404);
    }
    
    /**
     * Dynamic attribute returning user's full name.
     */    
    public function getNameAttribute()
    {
        if ($name = trim($this->first_name . ' ' . $this->last_name)) {
            return $name;
        }
        return null;
    }

    /**
     * Is the user an administrator?
     */
    public function getIsAdministratorAttribute()
    {
        return $this->role_id === Role::findRole('Administrator')->id;
    }   

    /**
     * Is the user an editor?
     */    
    public function getIsEditorAttribute()
    {
        return $this->role_id === Role::findRole('Editor')->id;
    }

    /**
     * Is the user at least (permission level) an editor?
     */    
    public function getIsAtLeastEditorAttribute()
    {
        return Role::find($this->role_id)->level
            >= Role::findRole('Editor')->level;
    }

    /**
     * Is the user an author?
     */    
    public function getIsAuthorAttribute()
    {
        return $this->role_id === Role::findRole('Author')->id;
    }

    /**
     * Is the user at least (permission level) an author?
     */    
    public function getIsAtLeastAuthorAttribute()
    {
        return Role::find($this->role_id)->level
            >= Role::findRole('Author')->level;
    }    

    /**
     * Is the user a contributor?
     */    
    public function getIsContributorAttribute()
    {
        return $this->role_id === Role::findRole('Contributor')->id;
    }

    /**
     * Is the user at least (permission level) a contributor?
     */    
    public function getIsAtLeastContributorAttribute()
    {
        return Role::find($this->role_id)->level
            >= Role::findRole('Contributor')->level;
    }    

    /**
     * Is the user a subscriber?
     */    
    public function getIsSubscriberAttribute()
    {
        return $this->role_id === Role::findRole('Subscriber')->id;
    }
}
