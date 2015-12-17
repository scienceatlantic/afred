<?php

namespace App\Http\Requests;

use App;
use App\Http\Requests\Request;

class FacilityRevisionHistoryRequest extends Request
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
       
        if ($state == 'PENDING_APPROVAL'
            || $state == 'PENDING_EDIT_APPROVAL') {
            $rules = [
                'data.institutionId' => 'exists:institutions,id',
                'data.institution.name' => 'required_if:institutionId,null',
                'data.provinceId' => 'exists:provinces,id',
                'data.name' => 'required',
                'data.city' => 'required',
                'data.website' => '',
                'data.description' => 'required',
                'data.isPublic' => '',
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
                    $rules["data.contacts.$i.firstName"] = 'required';
                    $rules["data.contacts.$i.lastName"] = 'required';
                    $rules["data.contacts.$i.email"] = 'required';
                    $rules["data.contacts.$i.telephone"] = '';
                    $rules["data.contacts.$i.position"] = '';
                    $rules["data.contacts.$i.website"] = '';
                }
            }
            
            $equipment = $this->instance()->input('data.equipment');
            if (is_array($equipment)) {
              $length = count($equipment);
                
                for ($i = 0; $i < $length; $i++) {
                    $rules["data.equipment.$i.type"] = 'required';
                    $rules["data.equipment.$i.model"] = '';
                    $rules["data.equipment.$i.manufacturer"] = '';
                    $rules["data.equipment.$i.purpose"] = 'required';
                    $rules["data.equipment.$i.specifications"] = '';
                    $rules["data.equipment.$i.isPublic"] = 'boolean';
                    $rules["data.equipment.$i.hasExcessCapacity"] = 'boolean';
                }
            }
            
            if ($state == 'PENDING_EDIT_APPROVAL') {
                $rules['data.id'] = 'required|exists:facilities,id';
            }
            
        } else if ($state == 'PUBLISHED'
                   || $state == 'REJECTED'
                   || $state == 'PUBLISHED_EDIT'
                   || $state == 'REJECTED_EDIT') {
            $rules = [];
            
        } else {
            App::abort(400);
        }
        
        return $rules;
    }
}
