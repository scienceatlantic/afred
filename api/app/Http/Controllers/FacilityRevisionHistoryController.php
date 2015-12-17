<?php

namespace App\Http\Controllers;

use Log;

use Illuminate\Http\Request;
use App;
use App\Organization;
use App\Facility;
use App\Equipment;
use App\Contact;
use App\PrimaryContact;
use App\FacilityRevisionHistory;
use App\FacilityEditRequest;
use App\Events\FacilityRevisionHistoryEvent;
use App\Http\Requests\FacilityRevisionHistoryRequest;
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
        $frh = FacilityRevisionHistory
            ::where('state', $stateOp, $state)
            ->paginate($itemsPerPage);
            
        return $this->_toCamelCase($frh->toArray());
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
    public function update(FacilityRevisionHistoryRequest $request, $id = null)
    {
        // Unless we're dealing with a 'PENDING_APPROVAL' record, grab
        // the FacilityRevisionHistory object.
        if ($request->input('state') != 'PENDING_APPROVAL') {
           $frh = FacilityRevisionHistory::findOrFail($id); 
        }
        
        // Grab the current datetime.
        $now = $this->_now();
        
        switch ($request->input('state')) {
            case 'PENDING_APPROVAL':
                $frh = new FacilityRevisionHistory(); 
                $frh->state = 'PENDING_APPROVAL';
                $frh->data = $this->_createFrhData($request, $now);
                $frh->dateSubmitted = $now;
                $frh->save();
                break;
            
            case 'PUBLISHED':                
                $data = $this->_publishFacility($frh, $frh->data);
                $frh->facilityId = $data['facility']['id'];
                $frh->state = 'PUBLISHED';
                $frh->data = $data;
                $frh->update();
                break;
            
            case 'REJECTED':
                $frh->state = 'REJECTED';
                $frh->update();
                break;
            
            case 'PENDING_EDIT_APPROVAL':
                $frhBeforeUpdateId = $frh->id;
                
                // 
                $frh->state = 'PENDING_EDIT_APPROVAL';
                $frh->data = $this->_createFrhData($request, $now, true, $frh);
                $frh->dateSubmitted = $now;
                $frh = new FacilityRevisionHistory($frh->toArray());
                $frh->save();
                
                $fer = FacilityEditRequest
                     ::where('frhBeforeUpdateId', $frhBeforeUpdateId)
                     ->first();
                $fer->frhAfterUpdateId = $frh->id;
                $fer->save();
                break;
            
            case 'PUBLISHED_EDIT':
                $data = $this->_publishFacility($frh, $frh->data, true);
                $frh->facilityId = $data['facility']['id'];
                $frh->state = 'PUBLISHED_EDIT';
                $frh->data = $data;
                $frh->update();                
                break;
            
            case 'REJECTED_EDIT':
                $frh->state = 'REJECTED_EDIT';
                $frh->update(); 
                break;
        }
        
        event(new FacilityRevisionHistoryEvent($frh));
        return $frh;
    }
        
    private function _createFrhData($request,
                                    $now,
                                    $isEdit = false,
                                    $frh = null)
    {
        $data = [];
        
        // Organization.
        if (!$request->data['organizationId']) {
            if ($request->data['organization']['name']) {
                $data['organization'] =
                    (new Organization((array) $request->data['organization']))
                        ->toArray();
                $data['organization']['isHidden'] = false;
                $data['organization']['dateAdded'] = $now;
            }
        }

        // Facility.
        if ($isEdit) {
            $facility = $frh->facility()->first();
            $data['facility'] = (new Facility((array) $request->data))
                ->toArray();
            $data['facility']['id'] = $facility->id;
            $data['facility']['dateSubmitted'] = $facility->dateSubmitted
                ->toDateTimeString();
            $data['facility']['dateUpdated'] = $now; 
        } else {
            $data['facility'] = (new Facility((array) $request->data))
                ->toArray();
            $data['facility']['dateSubmitted'] = $now;
            $data['facility']['dateUpdated'] = $now;            
        }
        $data['facility']['isPublic'] = true;
        
        // Primary contact.
        $data['facility']['primaryContact'] =
            (new PrimaryContact((array) $request->data['primaryContact']))
                ->toArray();
        $data['facility']['primaryContact']['facilityId'] = null;
        
        // Contacts.
        $data['facility']['contacts'] = [];
        foreach($request->data['contacts'] as $i => $contact) {
            $data['facility']['contacts'][$i] =
                (new Contact($contact))->toArray();
            $data['facility']['contacts'][$i]['facilityId'] = null;
        }
        
        // Equipment.
        $data['facility']['equipment'] = [];
        foreach($request->data['equipment'] as $i => $equipment) {
            $data['facility']['equipment'][$i] =
                (new Equipment($equipment))->toArray();
            $data['facility']['equipment'][$i]['facilityId'] = null;
        }
        
        return $data;
    }
    
    private function _publishFacility($frh, $data, $isEdit = false)
    {
        // FIX THIS!
        if (!$data['facility']['organizationId']) {
            if ($data['organization']['name']) {
                $data['facility']['organizationId'] =
                    Organization::create($data['organization'])
                    ->getKey();
            }
        }
        
        if ($isEdit) {            
            Contact
                ::where('facilityId', $data['facility']['id'])
                ->delete();
                
            PrimaryContact
                ::where('facilityId', $data['facility']['id'])
                ->delete();
                
            Equipment
                ::where('facilityId', $data['facility']['id'])
                ->delete();
            
            Facility
                ::where('id', $data['facility']['id'])
                ->update($this->_unset($data['facility']));
        } else {
            $data['facility']['facilityRevisionHistoryId'] = $frh->id;
            $data['facility']['id'] =
                Facility::create($data['facility'])->getKey();            
        }
        
        // Primary contact.
        $data['facility']['primaryContact']['facilityId'] =
            $data['facility']['id'];
        $data['facility']['primaryContact']['id'] =
            PrimaryContact::create($data['facility']['primaryContact'])
            ->getKey();
        
        // Contacts.
        if (array_key_exists('contacts', $data['facility'])) {
            foreach($data['facility']['contacts'] as $i => $contact) {
                $contact['facilityId'] = $data['facility']['id'];
                $data['facility']['contacts'][$i]['id'] =
                    Contact::create($contact)->getKey();           
            }                       
        }
        
        // Equipment.
        if (array_key_exists('equipment', $data['facility'])) {
            foreach($data['facility']['equipment'] as $i => $e) {
                $e['facilityId'] = $data['facility']['id'];
                $data['facility']['equipment'][$i]['id'] =
                    Equipment::create($e)->getKey();           
            }
        }
        
        return $data;
    }
    
    private function _unset($arr)
    {
        foreach($arr as $key => $value) {
            if (is_array($value)) {
                unset($arr[$key]);
            }
        }
        
        return $arr;
    }
}
