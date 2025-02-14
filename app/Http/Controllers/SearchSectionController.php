<?php

namespace App\Http\Controllers;

use App\Directory;
use App\Http\Requests\SearchSectionIndexRequest;

class SearchSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(
        SearchSectionIndexRequest $request,
        $directoryId,
        $formId
    ) {
        return Directory
            ::findOrFail($directoryId)
            ->forms()
            ->findOrFail($formId)
            ->searchSections()
            ->with(['searchFacets' => function($query) {
                $query->with('operator')->orderBy('placement_order', 'asc');
            }])
            ->orderBy('placement_order', 'asc')
            ->get();
    }
}
