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
    public function index()
    {
        return Facility::paginate(15);
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
        
        if ($request->has('expand')) {
            foreach (explode(',', $request->input('expand')) as
                $relationship) {
                    $facility->$relationship;
            }
        } 
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
