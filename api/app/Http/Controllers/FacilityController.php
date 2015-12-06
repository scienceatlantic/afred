<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use App\Facility;
use App\Institution;
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
        $itemsPerPage = $request->input('itemsPerPage', 15);
        return Facility::paginate($itemsPerPage);
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
        $this->_expandRelationships($request, $facility);
        return $facility;      
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
