<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemUser extends Model
{   
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'rememberToken'];
}
