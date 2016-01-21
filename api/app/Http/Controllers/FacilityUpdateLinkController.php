<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Log;
use App;
use App\Facility;
use App\Contact;
use App\PrimaryContact;
use App\FacilityEditRequest;
use App\facilityRepository;
use App\Events\FacilityEditTokenRequestedEvent;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class FacilityUpdateLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexMatchingFacilities(Request $request)
    {
        // Find all matching contacts and grab their facility IDs.
        $cF = Contact
            ::where('email', $request->email)
            ->select('facilityId');
        
        // Find all matching primary contacts and grab their facility IDs.
        $pcF = PrimaryContact
            ::where('email', $request->email)
            ->select('facilityId');
        
        // Add the two results together and grab all the matching facilities.
        $ids = $cF->union($pcF)->get()->toArray();
        $f = Facility::whereIn('facilities.id', $ids);
        
        // For each facility, grab its latest revision.
        $f->join('facility_revision_history',
            'facilities.facilityRepositoryId',
            '=', 'facility_revision_history.id');
            
        // Finally, check if each facility already has an edit 'token'
        // associated with it.
        $f->leftJoin('facility_update_links',
                     'facility_revision_history.id',
                     '=', 'facility_update_links.frhBeforeUpdateId')
          ->select('facilities.*',
                   'facility_update_links.id as facilityEditRequestId');
        
        $paginate = $request->input('paginate', true);
        $itemsPerPage = $request->input('itemsPerPage', 15);
        
        if ($paginate) {
            return $f->paginate($itemsPerPage);
        } else {
            return $f->get();
        }
    }    
    
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generateToken(Request $request)
    {
        // First grab the facility.
        $f = Facility::findOrFail($request->facilityId);
        
        // See if the email address provided matches a primary contact and
        // the facility provided. If not, try looking in contacts. If that
        // also fails, abort because the user (based on the email address
        // provided) is not a contact of the facility and therefore not allowed
        // to edit it.
        $editor = PrimaryContact
            ::where('email', $request->email)
            ->where('facilityId', $f->id)
            ->first();
        if (!$editor) {
            $editor = Contact
                ::where('email', $request->email)
                ->where('facilityId', $f->id)
                ->first();
            
            if (!$editor) {
                App::abort(400);
            }
        }
    
        // Grab the current revision.
        $frh = $f->currentRevision()->first();
                 
        // Generate the token only if an existing token doesn't already exist.
        $fer = FacilityEditRequest
            ::where('frhBeforeUpdateId', $frh->id)
            ->get();
            
        if (!count($fer)) {
            $fer = new FacilityEditRequest();
            $fer->frhBeforeUpdateId = $frh->id;
            $fer->firstName = $editor->firstName;
            $fer->lastName = $editor->lastName;
            $fer->email = $editor->email;
            $fer->token = strtolower(str_random(20));
            $fer->dateRequested = $this->_now();
            $fer->save();            
        } else {
            App::abort(400);
        }

        event(new FacilityEditTokenRequestedEvent($fer));
        return $fer;
    }
    
    public function verifyToken(Request $request, $id)
    {
        $frhBeforeUpdateId = $request->input('facilityRepositoryId');
        $token = $request->input('token');
        
        $fer = FacilityEditRequest
            ::where('frhBeforeUpdateId', $frhBeforeUpdateId)
            ->where('token', $token)
            ->first();
            
        if ($fer) {
            return $fer;
        } else {
            App::abort(404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyToken($id)
    {
        //
    }
}
