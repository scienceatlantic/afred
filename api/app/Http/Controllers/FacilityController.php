<?php

namespace App\Http\Controllers;

// Controllers.
use App\Http\Controllers\Controller;

// Laravel.
use Illuminate\Http\Request;

// Misc.
use Log;

// Models.
use App\Facility;
use App\Organization;
use App\PrimaryContact;
use App\Contact;
use App\Equipment;

// Requests.
use App\Http\Requests;

class FacilityController extends Controller
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
        $f = new Facility();
        $f = $this->_paginate ? $f->paginate($this->_itemsPerPage) : $f->get();
        $this->_expandModelRelationships($f, true);
        return $this->_toCamelCase($f->toArray());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $f = Facility::findOrFail($id);        
        $this->_expandModelRelationships($f);
        return $this->_toCamelCase($f->toArray());   
    }
    
    public function updateVisibility()
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Facility::find($id)->delete();
    }
}
