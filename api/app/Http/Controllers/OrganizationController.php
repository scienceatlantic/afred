<?php

namespace App\Http\Controllers;

// Controllers.
use App\Http\Controllers\Controller;

// Laravel.
use Illuminate\Http\Request;

// Models.
use App\Ilo;
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
        if ($request->input('showHidden', false)) {
            $o = Organization::with('ilo')->orderBy('name', 'asc');
        } else {
            $o = Organization::with('ilo')->notHidden()->orderBy('name', 'asc');
        }
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $o = Organization::with('ilo')->find($id);
        $o->name = $request->name;
        $o->isHidden = $request->isHidden;
        $o->save();
        
        if ($request->ilo) {
            $ilo = Ilo::where('organizationId', $o->id)->first();
            
        }
        
        return $this->_toCamelCase($o->toArray());
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
        Organization::destroy($id);
    }
}
