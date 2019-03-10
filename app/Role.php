<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * Relationship with all the users it has.
     */
    public function users()
    {
        return $this->hasMany('App\User');
    }

    /**
     * Find a role by the role's name.
     *
     * @param {string} $name
     */
    public static function findRole($name)
    {
        return self::where('name', $name)->first();
    }
}
