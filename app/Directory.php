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

    public function forms()
    {
        return $this->hasMany('App\Form');
    }

    public function formEntries()
    {
        return $this->belongsToMany('App\FormEntries')->withTimestamps();
    }

    public static function findDirectory($name)
    {
        return self::where('name', $name)->first();
    }

    public function getTargetWpAdminBaseUrlAttribute()
    {
        return $this->getTargetWpAdminBaseUrl();
    }

    public function getTargetWpAdminBaseUrl()
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
