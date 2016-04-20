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
use App\Contact;
use App\PrimaryContact;

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
            $f = Facility::with('province',
                                'organization',
                                'disciplines',
                                'sectors',
                                'primaryContact',
                                'contacts',
                                'equipment');
            return $this->pageOrGet($f);            
        }
        return $this->indexMatchingFacilities($email);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $f = Facility::with('province',
                            'organization',
                            'disciplines',
                            'sectors',
                            'primaryContact',
                            'contacts',
                            'equipment')->findOrFail($id)->toArray();        
        return $this->toCcArray($f);   
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
    
    private function indexMatchingFacilities($email)
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
        
        return $this->pageOrGet($f);
    }
}
