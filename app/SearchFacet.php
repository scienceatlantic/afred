<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SearchFacet extends Model
{
    /**
     * Relationship with the search section it belongs to.
     */
    public function searchSection()
    {
        return $this->belongsTo('App\SearchSection');
    }

    /**
     * Relationship with the search facet operator it belongs to.
     */
    public function operator()
    {
        return $this->belongsTo(
            'App\SearchFacetOperator',
            'search_facet_operator_id'
        );
    }
}
