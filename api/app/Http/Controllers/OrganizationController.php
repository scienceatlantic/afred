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
    function __construct(Request $request)
    {
        parent::__construct($request);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $o = Organization::with('ilo')->notHidden()->orderBy('name', 'asc');
        $o = $this->_paginate ? $o->paginate($this->_ipp) : $o->get();
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
        $o = Organization::with('ilo')->findOrFail($id);
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
        // 
        //Organization::destroy($id);
    }
}
