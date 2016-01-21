<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Log;

use App\Organization;
use App\Facility;
use App\Equipment;
use App\Contact;
use App\PrimaryContact;
use App\FacilityRepository;
use App\FacilityEditRequest;
use App\Events\FacilityRepositoryEvent;
use App\Http\Requests\FacilityRepositoryRequest;
use App\Http\Controllers\Controller;

class FacilityRepositoryController extends Controller
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
        $fr = FacilityRepository
            ::where('state', $stateOp, $state)
            ->paginate($itemsPerPage);
            
        return $this->_toCamelCase($fr->toArray());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return FacilityRepository::findOrFail($id);            
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FacilityRepositoryRequest $request, $id = null)
    {
        // Unless we're dealing with a 'PENDING_APPROVAL' record, grab
        // the FacilityRepository object.
        if ($request->input('state') != 'PENDING_APPROVAL') {
           $fr = FacilityRepository::findOrFail($id); 
        }
        
        // Grab the current datetime.
        $now = $this->_now();
        
        switch ($request->input('state')) {
            case 'PENDING_APPROVAL':
                $fr = new FacilityRepository(); 
                $fr->state = 'PENDING_APPROVAL';
                $fr->data = $this->_createFrhData($request, $now);
                $fr->dateSubmitted = $now;
                $fr->save();
                break;
            
            case 'PUBLISHED':                
                $data = $this->_publishFacility($fr, $fr->data);
                $fr->facilityId = $data['facility']['id'];
                $fr->state = 'PUBLISHED';
                $fr->data = $data;
                $fr->update();
                break;
            
            case 'REJECTED':
                $fr->state = 'REJECTED';
                $fr->update();
                break;
            
            case 'PENDING_EDIT_APPROVAL':
                $frBeforeUpdateId = $fr->id;
                
                // 
                $fr->state = 'PENDING_EDIT_APPROVAL';
                $fr->data = $this->_createFrhData($request, $now, true, $fr);
                $fr->dateSubmitted = $now;
                $fr = new FacilityRepository($fr->toArray());
                $fr->save();
                
                $fer = FacilityEditRequest
                     ::where('frhBeforeUpdateId', $frBeforeUpdateId)
                     ->first();
                $fer->frhAfterUpdateId = $fr->id;
                $fer->save();
                break;
            
            case 'PUBLISHED_EDIT':
                $data = $this->_publishFacility($fr, $fr->data, true);
                $fr->facilityId = $data['facility']['id'];
                $fr->state = 'PUBLISHED_EDIT';
                $fr->data = $data;
                $fr->update();                
                break;
            
            case 'REJECTED_EDIT':
                $fr->state = 'REJECTED_EDIT';
                $fr->update(); 
                break;
        }
        
        //event(new FacilityRepositoryEvent($fr));
        return $fr;
    }
        
    private function _createFrhData($request,
                                    $now,
                                    $isEdit = false,
                                    $fr = null)
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
            $facility = $fr->facility()->first();
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
    
    private function _publishFacility($fr, $data, $isEdit = false)
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
            $data['facility']['FacilityRepositoryId'] = $fr->id;
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
