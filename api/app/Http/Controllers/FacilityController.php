<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use App\Facility;
use App\Organization;
use App\PrimaryContact;
use App\Contact;
use App\Equipment;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class FacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $paginate = $request->input('paginate', true);
        $itemsPerPage = $request->input('itemsPerPage', 15);
        $facility = new Facility();
        
        if ($paginate) {
            $facility = $facility->paginate($itemsPerPage);
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
