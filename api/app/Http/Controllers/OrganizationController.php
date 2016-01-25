<?php

namespace App\Http\Controllers;

// Controllers.
use App\Http\Controllers\Controller;

// Laravel.
use Illuminate\Http\Request;

// Models.
use App\Organization;

// Requests.
use App\Http\Requests;
use App\Http\Requests\IndexOrganizationRequest;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndexOrganizationRequest $request)
    {
        $paginate = $request->input('paginate', true);
        $itemsPerPage = $request->input('itemsPerPage', 15);
        $organization = Organization
                     ::where('isHidden', false)
                     ->orderBy('name', 'asc');
        $organization = $paginate ?
            $organization->paginate($itemsPerPage) : $organization->get();
        $this->_expandModelRelationships($request, $organization, true);
        return $organization;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $organization = Organization::findOrFail($id);
        $this->_expandModelRelationships($request, $organization);
        return $organization;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Organization::destroy($id);
    }
}
