<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class IloRequest extends Request
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
                $r['id'] = 'required|exists:ilos,id';
            case 'POST':
                $r['organizationId'] = 'required|exists:organizations,id';
                $r['firstName'] = 'required';
                $r['lastName'] = 'required';
                $r['email'] = 'required';
                $r['telephone'] = 'required';
                $r['extension'] = '';
                $r['position'] = 'required';
                $r['website'] = '';
                break;    
        }
        return $r;
    }
}
