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
        if (!($email = $request->input('email', null))) {
            $f = new Facility();
            $f = $this->_paginate ? $f->paginate($this->_itemsPerPage) : $f->get();
            $this->_expandModelRelationships($f, true);
            return $this->_toCamelCase($f->toArray());            
        } else {
            return $this->_indexMatchingFacilities($email);
        }
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
    
    private function _indexMatchingFacilities($email)
    {
        // Find all matching contacts and grab their facility IDs.
        $cF = Contact::where('email', $email)->select('facilityId');
        
        // Find all matching primary contacts and grab their facility IDs.
        $pcF = PrimaryContact::where('email', $email)->select('facilityId');
        
        // Add the two results together and grab all the matching facilities.
        $ids = $cF->union($pcF)->get()->toArray();
        
        $f = Facility::leftJoin('facility_update_links',
                                'facility_update_links.frIdBefore', '=',
                                'facilities.facilityRepositoryId')
            ->whereIn('facilities.id', $ids)
            ->select('facilities.id',
                     'facilities.name',
                     'facilities.city',
                     'facility_update_links.editorFirstName',
                     'facility_update_links.editorLastName',
                     'facility_update_links.editorEmail',
                     'facility_update_links.status');
                
        $f = $this->_paginate ? $f->paginate($this->_itemsPerPage) : $f->get();
        return $this->_toCamelCase($f->toArray());
    }
}
