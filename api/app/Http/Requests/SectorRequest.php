<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class SectorRequest extends Request
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
            case 'POST':
                // No break.
            case 'PUT':     
                return $this->isAdmin();
            case 'DELETE':
                return $this->isAdmin();
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
        switch ($this->method()) {            
            case 'PUT':
                // No break.
            case 'POST':
                $r['name'] = 'required|unique:sectors';
                break;
        }
        return $r;
    }
}
