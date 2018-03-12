<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SearchFacet extends Model
{
    public function searchSection()
    {
        return $this->belongsTo('App\SearchSection');
    }

    public function operator()
    {
        return $this->belongsTo(
            'App\SearchFacetOperator',
            'search_facet_operator_id'
        );
    }
}
