<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;

use Log;

use App;
use App\FacilityRevisionHistory;
use App\Institution;
use App\Facility;
use App\Equipment;
use App\Contact;
use App\PrimaryContact;

use App\Http\Requests;
use App\Http\Requests\StoreFacilityRevisionHistoryRequest;
use App\Http\Requests\UpdateFacilityRevisionHistoryRequest;
use App\Http\Controllers\Controller;

class FacilityRevisionHistoryController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Check if requesting for only a specific state.
        if ($request->has('state')) {
            $frhs = FacilityRevisionHistory::where('state',
                $request->input('state'))->paginate(15);
        }
        else {
            $frhs = FacilityRevisionHistory::paginate(15);
        }
        
        $this->_formatFij($frhs, true);
        
        return $frhs;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFacilityRevisionHistoryRequest $request)
    {        
        return FacilityRevisionHistory::create([
            'state'            => 'PENDING_APPROVAL',
            'institution_id'   => $request->input('institutionId'),
            'province_id'      => $request->input('provinceId'),
            'facility_in_json' => json_encode($this->_createFij($request))
        ]);
    
        // If the user is auth-ed, then 'PUBLISHED immediatly??'
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!$frh = FacilityRevisionHistory::find($id)) {
            App::abort(404);
        }

        $this->_formatFij($frh, false);
        
        return $frh;            
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFacilityRevisionHistoryRequest $request, $id)
    {        
        switch ($request->input('state')) {
            case 'PUBLISHED':                
                $fij = json_decode($frh->facilityInJson);
                    
                if (!$frh->institutionId) {
                    $frh->institutionId = Institution::create(
                        (array) $fij->institution)->getKey();
                }                
                
                $fij->facility->id = Facility::create((array) $fij->facility)
                    ->getKey();
                
                $fij->primaryContact->facility_id = $fij->facility->id;
                PrimaryContact::create((array) $fij->primaryContact); 
                
                foreach($fij->contacts as $contact) {
                    $contact->facility_id = $fij->facility->id;
                    Contact::create((array) $contact);           
                }                      
                
                foreach($fij->equipment as $equipment) {
                    $equipment->facility_id = $fij->facility->id;
                    Equipment::create((array) $equipment);           
                }     
                
                // Update the state of facility revision history.
                $frh->facilityId = $fij->facility->id;
                $frh->institutionId = $fij->facility->institutionId;
                $frh->state = 'PUBLISHED';
                $frh->update();
                break;
            
            case 'REJECTED':
                
                // Validate states..?
                $frh->state = 'REJECTED';
                $frh->update();
                break;
            
            case 'EDIT_DRAFT':
                $frh->state = 'EDIT_DRAFT';
                $frh->update();
                break;
            
            case 'PENDING_EDIT_APPROVAL':
                $frh->state = 'PENDING_EDIT_APPROVAL';
                $frh->update();               
                break;
            
            case 'REJECTED_EDIT':
                $frh->state = 'REJECTED_EDIT';
                $frh->update(); 
                break;
        }
        
    }
    
    private function _createFij($request)
    {        
        $fij = [
            'institution' => [
                'name' => $request->input('institution.name')
            ],
            'facility' => [
                'institution_id' => $request->input('institutionId'),
                'province_id'    => $request->input('provinceId'),
                'name'           => $request->input('name'),
                'city'           => $request->input('city'),
                'website'        => $request->input('website'),
                'description'    => $request->input('description'),
                'is_public'      => true
            ],
            'primaryContact' => [
                'facility_id' => null,
                'first_name'  => $request->input('primaryContact.firstName'),
                'last_name'   => $request->input('primaryContact.lastName'),
                'email'       => $request->input('primaryContact.email'),
                'telephone'   => $request->input('primaryContact.telephone'),
                'extension'   => $request->input('primaryContact.extension'),
                'position'    => $request->input('primaryContact.position'),
                'website'     => $request->input('primaryContact.website')
            ],
            'contacts' => [],
            'equipment' => []
        ];
        
        foreach($request->input('contacts') as $i => $contact) {
            $p = "contact.$i."; // Prefix.
            
            array_push($fij['contacts'], [
                'facility_id' => null,
                'first_name'  => $request->input("$p.firstName"),
                'last_name'   => $request->input("$p.lastName"),
                'email'       => $request->input("$p.email"),
                'telephone'   => $request->input("$p.telephone"),
                'extension'   => $request->input("$p.extension"),
                'position'    => $request->input("$p.position"),
                'website'     => $request->input("$p.website")
            ]);
        }
        
        foreach($request->input('equipment') as $i => $equipment) {
            $p = "equipment.$i."; // Prefix.
            
            array_push($fij['equipment'], [
                'facility_id'         => null,
                'type'                => $request->input("$p.type"),
                'manufacturer'        => $request->input("$p.manufacturer"),
                'purpose'             => $request->input("$p.purpose"),
                'specifications'      => $request->input("$p.specifications"),
                'is_public'           => $request->input("$p.isPublic"),
                'has_excess_capacity' => $request->input("$p.hasExcessCapacity")
            ]);           
        }
        
        return $fij;
    }
    
    private function _formatFij($frhs, $isArray = false)
    {
        function format($frh)
        {
            //$frh->province();
            
            
            
            $fij = json_decode($frh->facilityInJson);
            $frh->facilityInJson = $fij->facility;
            $frh->facilityInJson->institution = (object) ['name' => 'sds'];
            $frh->facilityInJson->primaryContact = $fij->primaryContact;
            $frh->facilityInJson->contacts = $fij->contacts;
            $frh->facilityInJson->equipment = $fij->equipment;           
        }
        
        if ($isArray) {
            foreach($frhs as $frh) {
                format($frh);
            }
        }
        else {
            format($frhs);
        }
    }
}
