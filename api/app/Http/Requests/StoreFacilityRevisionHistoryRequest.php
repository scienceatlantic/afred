<?php

namespace App\Http\Requests;

use App\Province;
use App\Institution;

use App\Http\Requests\Request;

class StoreFacilityRevisionHistoryRequest extends Request
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
        $rules = [
            'institutionId' => 'exists:institutions,id',
            'institution.name' => 'required_unless:institutionId,null',
            'provinceId' => 'exists:provinces,id',
            'name' => 'required',
            'city' => 'required',
            'website' => '',
            'description' => 'required',
            'isPublic' => '',
            'primaryContact.firstName' => 'required',
            'primaryContact.lastName' => 'required',
            'primaryContact.email' => 'required',
            'primaryContact.telephone' => 'required',
            'primaryContact.position' => 'required',
            'primaryContact.website' => '',
            'contacts' => 'array',
            'equipment' => 'array'
        ];
        
        $contacts = $this->instance()->input('contacts');
        if (is_array($contacts)) {
            $length = count($contacts);
            
            for ($i = 0; $i < $length; $i++) {
                $rules["contacts.$i.firstName"] = 'required';
                $rules["contacts.$i.lastName"] = 'required';
                $rules["contacts.$i.email"] = 'required';
                $rules["contacts.$i.telephone"] = '';
                $rules["contacts.$i.position"] = '';
                $rules["contacts.$i.website"] = '';
            }
        }
        
        $equipment = $this->instance()->input('equipment');
        if (is_array($equipment)) {
          $length = count($equipment);
            
            for ($i = 0; $i < $length; $i++) {
                $rules["equipment.$i.type"] = 'required';
                $rules["equipment.$i.model"] = '';
                $rules["equipment.$i.manufacturer"] = '';
                $rules["equipment.$i.purpose"] = 'required';
                $rules["equipment.$i.specifications"] = '';
                $rules["equipment.$i.isPublic"] = 'boolean';
                $rules["equipment.$i.hasExcessCapacity"] = 'boolean';
            }
        }
        
        return $rules;
    }
}
