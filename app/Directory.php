<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Directory extends Model
{
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'target_wp_admin_base_url'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['wp_api_password'];

    /**
     * Relationship with all the forms it has.
     */
    public function forms()
    {
        return $this->hasMany('App\Form');
    }

    /**
     * Relationship with all the users that are responsible for managing it.
     */
    public function users()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }

    /**
     * Find a directory by its name
     *
     * @param {string} $name
     */
    public static function findDirectory($name)
    {
        return self::where('name', $name)->first();
    }

    /**
     * The "target" WP Admin Base URL.
     *
     * Have a look at the GitHub documentation for more information.
     */
    public function getTargetWpAdminBaseUrlAttribute()
    {
        if (array_key_exists('targetDirectoryId', $_REQUEST)
            && $id = $_REQUEST['targetDirectoryId']) {
            if ($targetDirectory = self::find($id)) {
                return $targetDirectory->wp_admin_base_url;
            }

            abort(400);
        }

        return $this->wp_admin_base_url;
    }
}
