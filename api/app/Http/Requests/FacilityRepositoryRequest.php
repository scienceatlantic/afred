<?php

namespace App\Http\Requests;

use App;
use App\Http\Requests\Request;

class FacilityRepositoryRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {        
        $state = $this->instance()->input('state');
        
        switch ($state) {
            case 'PENDING_APPROVAL':
            case 'PENDING_EDIT_APPROVAL':
                $r = [
                    'data.facility.organizationId' => 'exists:organizations,id',
                    'data.facility.provinceId' => 'exists:provinces,id',
                    'data.facility.name' => 'required',
                    'data.facility.city' => 'required',
                    'data.facility.website' => '',
                    'data.facility.description' => 'required',
                    'data.facility.isPublic' => '',
                    'data.primaryContact.firstName' => 'required',
                    'data.primaryContact.lastName' => 'required',
                    'data.primaryContact.email' => 'required',
                    'data.primaryContact.telephone' => 'required',
                    'data.primaryContact.position' => 'required',
                    'data.primaryContact.website' => '',
                    'data.contacts' => 'array',
                    'data.equipment' => 'array'
                ];
                
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
                
                $equipment = $this->instance()->input('data.equipment');
                if (is_array($equipment)) {
                  $length = count($equipment);
                    
                    for ($i = 0; $i < $length; $i++) {
                        $r["data.equipment.$i.type"] = 'required';
                        $r["data.equipment.$i.model"] = '';
                        $r["data.equipment.$i.manufacturer"] = '';
                        $r["data.equipment.$i.purpose"] = 'required';
                        $r["data.equipment.$i.specifications"] = '';
                        $r["data.equipment.$i.isPublic"] = 'boolean';
                        $r["data.equipment.$i.hasExcessCapacity"] = 'boolean';
                    }
                }
                
                if ($state == 'PENDING_EDIT_APPROVAL') {
                    $r['data.facility.id'] = 'required|exists:facilities,id';
                }               
                break;
            
            case 'PUBLISHED':
            case 'REJECTED':
            case 'PUBLISHED_EDIT':
            case 'REJECTED_EDIT':
                $r = [];
                break;
            
            default:
                App::abort(400);
        }
        
        return $r;
    }
}
