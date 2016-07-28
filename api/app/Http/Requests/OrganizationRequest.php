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
        $r = [];
        switch ($this->method()) {            
            case 'PUT':
                // No break.
            case 'POST':
                $r['name'] = 'required';
                $r['isHidden'] = 'digits_between:0,1';
                break;
        }
        return $r;
    }
}
