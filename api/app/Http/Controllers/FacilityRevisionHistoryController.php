<?php

namespace App\Http\Controllers;

use Log;

use Illuminate\Http\Request;
use Event;
use App;
use App\FacilityRevisionHistory;
use App\Institution;
use App\Facility;
use App\Equipment;
use App\Contact;
use App\PrimaryContact;
use App\Events\FacilityRevisionHistory\FacilitySubmittedEvent;
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
        $itemsPerPage = $request->input('itemsPerPage', 15);
        $state = $request->input('state', '%');
        $stateOp = $state  == '%' ? 'like' : '=';
        return FacilityRevisionHistory::where('state', $stateOp, $state)
            ->paginate($itemsPerPage);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFacilityRevisionHistoryRequest $request)
    {        
        $frh = new FacilityRevisionHistory(); 
        $frh->state = 'PENDING_APPROVAL';
        $frh->data = $this->_createFrhData($request);
        $frh->save();
        Event::fire(new FacilitySubmittedEvent($frh));
        return $frh;
        // If the user is auth-ed, then 'PUBLISHED immediately??'
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return FacilityRevisionHistory::findOrFail($id);            
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
        $frh = FacilityRevisionHistory::findOrFail($id);
        $data = $frh->data;
        
        switch ($request->input('state')) {
            case 'PUBLISHED':                    
                if (!$data['facility']['institutionId']) {
                    $data['facility']['institutionId'] =
                        Institution::create($data['institution'])
                            ->getKey();
                }          
                
                $data['facility']['id'] =
                    Facility::create($data['facility'])->getKey();
                
                $data['facility']['primaryContact']['facilityId'] =
                    $data['facility']['id'];
                $data['facility']['primaryContact']['id'] =
                    PrimaryContact::create($data['facility']['primaryContact'])
                    ->getKey();
                
                if (array_key_exists('contacts', $data['facility'])) {
                    foreach($data['facility']['contacts'] as $i => $contact) {
                        $contact['facilityId'] = $data['facility']['id'];
                        $data['facility']['contacts'][$i]['id'] =
                            Contact::create($contact)->getKey();           
                    }                       
                }
                
                if (array_key_exists('equipment', $data['facility'])) {
                    foreach($data['facility']['equipment'] as $i => $equipment) {
                        $equipment['facilityId'] = $data['facility']['id'];
                        $data['facility']['equipment'][$i]['id'] =
                            Equipment::create($equipment)->getKey();           
                    }
                }
                
                $frh->facilityId = $data['facility']['id'];
                $frh->state = 'PUBLISHED';
                $frh->data = $data;
                $frh->update();
                return $frh;
                break;
            
            case 'REJECTED':
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
    
    private function _createFrhData($request)
    {
        $data = [];
        
        // Institution.
        if (!$request->institutionId) {
            $data['institution'] =
                (new Institution((array) $request->institution))->toArray();            
        }

        // Facility.
        $data['facility'] =
            (new Facility((array) $request->all()))->toArray();
        
        // Primary contact.
        $data['facility']['primaryContact'] =
            (new PrimaryContact((array) $request->primaryContact))->toArray();
        $data['facility']['primaryContact']['facilityId'] = null;
        
        // Contacts.
        $data['facility']['contacts'] = [];
        foreach($request->contacts as $i => $contact) {
            $data['facility']['contacts'][$i] =
                (new Contact($contact))->toArray();
            $data['facility']['contacts'][$i]['facilityId'] = null;
        }
        
        // Equipment.
        $data['facility']['equipment'] = [];
        foreach($request->equipment as $i => $equipment) {
            $data['facility']['equipment'][$i] =
                (new Equipment($equipment))->toArray();
            $data['facility']['equipment'][$i]['facilityId'] = null;
        }
        
        return $data;
    }
    
    private function _formatFij($frhs, $isArray = false)
    {
        function _format($frh)
        {
            $fij = $frh->facilityInJson;
            $frh->facilityInJson = $fij['facility'];
            $frh->facilityInJson['institution'] = (object) ['name' => 'sds'];
            $frh->facilityInJson['primaryContact'] = $fij->primaryContact;
            $frh->facilityInJson['contacts'] = $fij->contacts;
            $frh->facilityInJson['equipment'] = $fij->equipment;           
        }
        
        if ($isArray) {
            foreach($frhs as $frh) {
                _format($frh);
            }
        }
        else {
            _format($frhs);
        }
    }
}
