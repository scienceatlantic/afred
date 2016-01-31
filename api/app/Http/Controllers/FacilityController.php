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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $facility = new Facility();
        
        if ($this->_paginate) {
            $facility = $facility->paginate($this->_itemsPerPage);
        } else {
            $facility = $facility->all();
        }
        
        $this->_expandModelRelationships($request, $facility, true);
        return $this->_toCamelCase($facility->toArray());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $facility = Facility::find($id);        
        $this->_expandModelRelationships($request, $facility);
        return $this->_toCamelCase($facility->toArray());   
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
        return Facility::where('id', $id)->delete();
    }
}
