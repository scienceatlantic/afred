<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public function scopeGetValue($query, $name)
    {
        $setting = $query->select('value')->where('name', $name)->first();
        return $setting['value'];
    }
}
