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

class OrganizationController extends Controller
{
    function __construct(Request $request) {
        parent::__construct($request);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $o = Organization::notHidden()->orderBy('name', 'asc');
        $o = $this->_paginate ? $o->paginate($this->_itemsPerPage) : $o->get();
        $this->_expandModelRelationships($o, true);
        return $this->_toCamelCase($o->toArray());
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
        $o = Organization::findOrFail($id);
        $this->_expandModelRelationships($o);
        return $this->_toCamelCase($o->toArray());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Organization::destroy($id);
    }
}
