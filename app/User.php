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
        'is_subscriber',
        'is_at_least_subscriber'
    ];    

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    public function directories()
    {
        return $this->belongsToMany('App\Directory')->withTimestamps();
    }

    public function formEntries()
    {
        return $this->belongsToMany('App\FormEntry')->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAdministrators($query)
    {
        return $query->where(
            'role_id',
            Role::findRole('Administrator')->id
        );
    }

    public function scopeEditors($query)
    {
        return $query->where(
            'role_id',
            Role::findRole('Editor')->id
        );
    }
    
    public function scopeAuthors($query)
    {
        return $query->where(
            'role_id',
            Role::findRole('Author')->id
        );
    }
    
    public function scopeContributors($query)
    {
        return $query->where(
            'role_id',
            Role::findRole('Contributor')->id
        );
    }
    
    public function scopeSubscribers($query)
    {
        return $query->where(
            'role_id',
            Role::findRole('Subscriber')->id
        );
    }

    public static function findByWpUsername($wpUsername)
    {
        return self::where('wp_username', $wpUsername)->first();
    }

    public static function findByEmail($email)
    {
        return self::where('email', $email)->first();
    }

    public static function findByEmailOrFail($email)
    {
        return self::findByEmail($email) ?: abort(404);
    }
    
    public function getNameAttribute()
    {
        if ($name = trim($this->first_name . ' ' . $this->last_name)) {
            return $name;
        }
        return null;
    }

    public function getIsAdministratorAttribute()
    {
        return $this->role_id === Role::findRole('Administrator')->id;
    }   

    public function getIsEditorAttribute()
    {
        return $this->role_id === Role::findRole('Editor')->id;
    }

    public function getIsAtLeastEditorAttribute()
    {
        return Role::find($this->role_id)->level
            >= Role::findRole('Editor')->level;
    }

    public function getIsAuthorAttribute()
    {
        return $this->role_id === Role::findRole('Author')->id;
    }

    public function getIsAtLeastAuthorAttribute()
    {
        return Role::find($this->role_id)->level
            >= Role::findRole('Author')->level;
    }    

    public function getIsContributorAttribute()
    {
        return $this->role_id === Role::findRole('Contributor')->id;
    }

    public function getIsAtLeastContributorAttribute()
    {
        return Role::find($this->role_id)->level
            >= Role::findRole('Contributor')->level;
    }    

    public function getIsSubscriberAttribute()
    {
        return $this->role_id === Role::findRole('Subscriber')->id;
    }

    public function getIsAtLeastSubscriberAttribute()
    {
        return Role::find($this->role_id)->level
            >= Role::findRole('Subscriber')->level;
    }   
}
