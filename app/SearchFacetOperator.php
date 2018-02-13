<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SearchFacetOperator extends Model
{
    public static function findOperator($name)
    {
        return self::where('name', $name)->first();
    }
}
