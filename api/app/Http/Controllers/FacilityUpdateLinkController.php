<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Log;
use App;
use App\Facility;
use App\Contact;
use App\PrimaryContact;
use App\FacilityUpdateLink;
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
        $f->join('facility_repository',
            'facilities.facilityRepositoryId',
            '=', 'facility_repository.id');
            
        // Finally, check if each facility already has an edit 'token'
        // associated with it.
        $f->leftJoin('facility_update_links',
                     'facility_repository.id',
                     '=', 'facility_update_links.frIdBefore')
          ->select('facilities.*',
                   'facility_update_links.id as facilityUpdateLinkId');
        
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
        $fr = $f->currentRevision()->first();
                 
        // Generate the token only if an existing token doesn't already exist.
        $fal = FacilityUpdateLink
            ::where('frIdBefore', $fr->id)
            ->get();
            
        if (!count($fal)) {
            $fal = new FacilityUpdateLink();
            $fal->frIdBefore = $fr->id;
            $fal->firstName = $editor->firstName;
            $fal->lastName = $editor->lastName;
            $fal->email = $editor->email;
            $fal->token = strtolower(str_random(20));
            $fal->dateRequested = $this->_now();
            $fal->save();            
        } else {
            App::abort(400);
        }

        //event(new FacilityEditTokenRequestedEvent($fal));
        return $fal;
    }
    
    public function verifyToken(Request $request, $id)
    {
        $frIdBefore = $request->input('facilityRepositoryId');
        $token = $request->input('token');
        
        $fal = FacilityUpdateLink
            ::where('frIdBefore', $frIdBefore)
            ->where('token', $token)
            ->first();
            
        if ($fal) {
            return $fal;
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
