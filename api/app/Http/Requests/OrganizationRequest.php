<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class OrganizationRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        switch ($this->method()) {
            case 'GET':
                return true;
            default:
                return $this->isAtLeastAdmin(); 
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'GET':
            case 'DELETE':
                return [];
            
            case 'PUT':            
            case 'POST':
                return [
                  'name'     => 'required',
                  'isHidden' => 'digits_between:0,1'
                ];
        }
    }
}
