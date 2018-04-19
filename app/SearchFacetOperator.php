<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SearchFacetOperator extends Model
{
    /**
     * Find a search facet operator by its operator name
     * 
     * @param {string} $name
     */
    public static function findOperator($name)
    {
        return self::where('name', $name)->first();
    }
}
