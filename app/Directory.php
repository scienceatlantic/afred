<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Directory extends Model
{
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
}
