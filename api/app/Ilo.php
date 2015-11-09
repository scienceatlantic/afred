<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ilo extends Model
{
    public function institution()
    {
        return $this->belongsTo('App\Institution');
    }
}
