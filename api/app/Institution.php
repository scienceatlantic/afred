<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    public function ilo()
    {
        return $this->hasOne('App\Institution');
    }
}
