<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\PrimaryContact;

class GenerateEditTokenRequest extends Request
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
            'facilityId' => 'required|exists:facilities,id',
            'email' => 'required|exists:'
        ];
        
        $facilityId = $this->instance()->input('facilityId');
        $email = $this->instance()->input('email');
        $pc = PrimaryContact
            ::where('email', $email)
            ->where('facilityId', $facilityId);
            
        if ($pc) {
            $rules['email'] =
                "required|exists:primary_contact,email,facilityId,$facilityId";
        } else {
            $rules['email'] =
                "required|exists:contacts,email,facilityId,$facilityId";            
        }
        
        
    }
}
