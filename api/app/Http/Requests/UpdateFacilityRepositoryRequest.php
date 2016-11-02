<?php

namespace App\Http\Requests;

// Misc.
use Route;
use Log;

// Models.
use App\FacilityRepository;
use App\FacilityUpdateLink;

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
        // When updating facility repository records, we need to make sure that
        // the record's current (before the update) state is valid for the
        // update request being made (i.e. a record with 'PUBLISHED' is not
        // being updated to 'REJECTED').
        $state = $this->instance()->input('state');        
        $id = Route::input('facility_repository');
        if ($this->method() != 'POST') {
            $fr = FacilityRepository::findOrFail($id);
        } else {
            $fr = FacilityRepository::find($id);
        }
        $currentState = $fr ? $fr->state : null;
    
        switch ($state) {
            // New record is being submitted. Make sure that it's actually a 
            // new submission (i.e. 'POST' instead of 'PUT' since both 
            // operations have been merged in the controller).
            case 'PENDING_APPROVAL':
                return $this->method() == 'POST';
            // An update is being submitted, check the current state and token
            // submitted with the request.
            case 'PENDING_EDIT_APPROVAL':
                if ($currentState == 'PUBLISHED'
                    || $currentState == 'PUBLISHED_EDIT') {
                    $token = $this->instance()->input('token', null);
                    return FacilityUpdateLink::verifyToken($id, $token);                    
                }
                return false;
            // Approve/reject requests can only be performed by (at least) an
            // admin.
            case 'PUBLISHED':
            case 'REJECTED':
                if ($currentState == 'PENDING_APPROVAL') {
                    return $this->isAdmin();
                }
                return false;
            // Like the above, can only be performed by at least an admin.
            case 'PUBLISHED_EDIT':
            case 'REJECTED_EDIT':
                if ($currentState == 'PENDING_EDIT_APPROVAL') {
                    return $this->isAdmin();
                }
                return false;
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
        $r = [];
        
        // String prefixes to shorten code.
        $f = 'data.facility';
        $o = 'data.organization';
        $d = 'data.disciplines';
        $s = 'data.sectors';
        $p = 'data.primaryContact';
        $c = 'data.contacts';
        $e = 'data.equipment';
        
        switch ($this->instance()->input('state')) {
            case 'PENDING_EDIT_APPROVAL':
                // Get the facility repository ID for the rule below.
                $frId = Route::input('facility_repository');
                
                // Validate facility ID.
                $r["$f.id"] = 'required|exists:facilities,id,'
                    . 'facilityRepositoryId,' . $frId;
                
                // Get the facility ID for the rules below.
                $fId = $this->instance()->input("$f.id");
                
                // If a primary contact ID is provided, make sure it's valid
                // (i.e. belongs to the facility being updated).
                $primaryContact = $this->instance()->input($p);
                $r["$p.id"] = 'nullable|exists:primary_contacts,id,facilityId,'. $fId;
                
                // If a contact ID is provided, make sure it's valid (i.e.
                // belongs to the facility being updated).
                $contacts = $this->instance()->input($c);
                if (is_array($contacts)) {
                    $length = count($contacts);              
                    for ($i = 0; $i < $length; $i++) {
                        $r["$c.$i.id"] = 'nullable|exists:contacts,id,facilityId,'. $fId;
                    }
                }
                
                // If an equipment ID is provided, make sure it's valid (i.e.
                // belongs to the facility being updated).
                $equipment = $this->instance()->input($e);
                $length = count($equipment);              
                for ($i = 0; $i < $length; $i++) {
                    $r["$e.$i.id"] = 'nullable|exists:equipment,id,facilityId,'. $fId;
                }     
                // No break.
            case 'PENDING_APPROVAL':
                // Facility section.
                $r["$f.organizationId"] = 'nullable|exists:organizations,id';
                $r["$f.provinceId"] = 'required|exists:provinces,id';
                $r["$f.name"] = 'required';
                $r["$f.city"] = '';
                $r["$f.website"] = '';
                $r["$f.description"] = 'required';
                $r["$f.isPublic"] = '';
                
                // Organization section (new organization is being submitted).
                // Note: comma after 'null' is intentional. Do not remove. It
                // signifies that this rule should apply if 'organizationId'
                // is null or not provided.
                $r["$o.name"] = "required_if:$f.organizationId,null,";
                
                // Disciplines section.
                $r[$d] = 'required|array';
                $length = count($this->instance()->input($d));
                for ($i = 0; $i < $length; $i++) {
                    $r["$d.$i"] = 'exists:disciplines,id';
                }
                
                // Sectors section.
                $r[$s] = 'required|array';
                $length = count($this->instance()->input($s));
                for ($i = 0; $i < $length; $i++) {
                    $r["$s.$i"] = 'exists:sectors,id';
                }
                
                // Primary contact section.
                $r["$p.firstName"] = 'required';
                $r["$p.lastName"] = 'required';
                $r["$p.email"] = 'required';
                $r["$p.telephone"] = 'required';
                $r["$p.extension"] = '';
                $r["$p.position"] = 'required';
                $r["$p.website"] = '';
                
                // Contacts section. Contacts are optional.
                $r[$c] = 'nullable|array|between:1,10';
                $contacts = $this->instance()->input($c);
                if (is_array($contacts)) {
                    $length = count($contacts);              
                    for ($i = 0; $i < $length; $i++) {
                        $r["$c.$i.firstName"] = 'required';
                        $r["$c.$i.lastName"] = 'required';
                        $r["$c.$i.email"] = 'required';
                        $r["$c.$i.telephone"] = '';
                        $r["$c.$i.extension"] = '';
                        $r["$c.$i.position"] = '';
                        $r["$c.$i.website"] = '';
                    }
                }
                
                // Equipment section
                $r[$e] = 'required|array|between:1,50';
                $equipment = $this->instance()->input($e);
                $length = count($equipment);              
                for ($i = 0; $i < $length; $i++) {
                    $r["$e.$i.type"] = 'required';
                    $r["$e.$i.model"] = '';
                    $r["$e.$i.manufacturer"] = '';
                    $r["$e.$i.purpose"] = 'required';
                    $r["$e.$i.specifications"] = '';
                    $r["$e.$i.isPublic"] = 'required|boolean';
                    $r["$e.$i.hasExcessCapacity"] = 'required|boolean';
                    $r["$e.$i.yearPurchased"] = 'date_format:Y';
                    $r["$e.$i.yearManufactured"] = 'date_format:Y';
                    $r["$e.$i.keywords"] = '';
                }            
                break; 
            case 'PUBLISHED':
            case 'REJECTED':
            case 'PUBLISHED_EDIT':
            case 'REJECTED_EDIT':
                $r['reviewerMessage'] = '';
                break;
        }
        return $r;
    }
}
