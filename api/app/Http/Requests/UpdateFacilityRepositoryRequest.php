<?php

namespace App\Http\Requests;

// Misc.
use Route;
use Log;

// Models.
use App\FacilityRepository;

// Requests.
use App\Http\Requests\Request;

class UpdateFacilityRepositoryRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $state = $this->instance()->input('state', null);
        
        // The first two states can be performed by an unauthenticated user
        // (ie. when submitting a new record or submitting an edit). However,
        // for the second, we still have to make sure that the right token
        // is used. The last four are protected functions that can only be
        // performed by an admin.
        switch ($state) {
            case 'PENDING_APPROVAL':
                return true;
            
            case 'PENDING_EDIT_APPROVAL':
                $id = Route::input('facility_repository', null);
                $token = $this->instance()->input('token', null);
                $ful = FacilityRepository::findOrFail($id)->fulsB()
                    ->where('token', $token)->first();
                return $ful && $ful->status == 'OPEN';
            
            case 'PUBLISHED':
            case 'PUBLISHED_EDIT':
            case 'REJECTED':
            case 'REJECTED_EDIT':
                return $this->_isAdmin();
            
            default:
                return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $state = $this->instance()->input('state', null);
        $r = [];
        
        // Search for the existing (if applicable) facility repository record
        // so we can make sure that its current state is valid for the update
        // that is about to take place.
        if ($state != 'PENDING_APPROVAL') {
            $id = Route::input('facility_repository', null);
            $fr = FacilityRepository::findOrFail($id);
            $currentState = $fr->state;
        } else {
            $currentState = null;
        }
        
        switch ($state) {
            case 'PENDING_EDIT_APPROVAL':
                if ($currentState != 'PUBLISHED'
                    || $currentState != 'PUBLISHED_EDIT') {
                    abort(400);
                }
                
                $r['data.facility.id'] = 'required|exists:facilities,id';
                
            case 'PENDING_APPROVAL':
                $r['data.facility.organizationId'] = 'exists:organizations,id';
                $r['data.facility.provinceId'] = 'exists:provinces,id';
                $r['data.facility.name'] = 'required';
                $r['data.facility.city'] = 'required';
                $r['data.facility.website'] = '';
                $r['data.facility.description'] = 'required';
                $r['data.facility.isPublic'] = '';
                $r['data.facility.dateSubmitted'] = 'date';
                $r['data.facility.dateUpdated'] = 'date';
                $r['data.organization.name'] =
                        'required_if:data.facility.organizationId,null';
                $r['data.disciplines'] = 'required|array';
                $r['data.sectors'] = 'required|array';
                $r['data.primaryContact.firstName'] = 'required';
                $r['data.primaryContact.lastName'] = 'required';
                $r['data.primaryContact.email'] = 'required';
                $r['data.primaryContact.telephone'] = 'required';
                $r['data.primaryContact.position'] = 'required';
                $r['data.primaryContact.website'] = '';
                $r['data.contacts'] = 'array';
                $r['data.equipment'] = 'required|array';
                
                // Disciplines section.
                $disciplines = $this->instance()->input('data.disciplines');
                $length = count($disciplines);
                for ($i = 0; $i < $length; $i++) {
                    $r["data.disciplines.$i"] = 'exists:disciplines,id';
                }
                
                // Sectors section.
                $sectors = $this->instance()->input('data.sectors');
                $length = count($sectors);
                for ($i = 0; $i < $length; $i++) {
                    $r["data.sectors.$i"] = 'exists:sectors,id';
                }
                
                // Contacts section. Contacts are optional.
                $contacts = $this->instance()->input('data.contacts');
                if (is_array($contacts)) {
                    $length = count($contacts);
                    
                    for ($i = 0; $i < $length; $i++) {
                        $r["data.contacts.$i.firstName"] = 'required';
                        $r["data.contacts.$i.lastName"] = 'required';
                        $r["data.contacts.$i.email"] = 'required';
                        $r["data.contacts.$i.telephone"] = '';
                        $r["data.contacts.$i.position"] = '';
                        $r["data.contacts.$i.website"] = '';
                    }
                }
                
                // Equipment section
                $equipment = $this->instance()->input('data.equipment');
                $length = count($equipment);
                
                for ($i = 0; $i < $length; $i++) {
                    $r["data.equipment.$i.type"] = 'required';
                    $r["data.equipment.$i.model"] = '';
                    $r["data.equipment.$i.manufacturer"] = '';
                    $r["data.equipment.$i.purpose"] = 'required';
                    $r["data.equipment.$i.specifications"] = '';
                    $r["data.equipment.$i.isPublic"] = 'boolean';
                    $r["data.equipment.$i.hasExcessCapacity"] = 'boolean';
                    $r["data.equipment.$i.yearPurchased"] = 'date_format:Y';
                }            
                break;
            
            case 'PUBLISHED':
            case 'REJECTED':
                if ($currentState != 'PENDING_APPROVAL') {
                    abort(400);
                }
                break;
            
            case 'PUBLISHED_EDIT':
            case 'REJECTED_EDIT':
                if ($currentState != 'PENDING_EDIT_APPROVAL') {
                    abort(400);
                }
                break;
        }
        
        return $r;
    }
}
