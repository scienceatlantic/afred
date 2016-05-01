<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class FacilityUpdateLinkRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        switch($this->method()) {
            // Admin only methods.
            case 'GET':
            case 'DELETE':
            case 'UPDATE':
                return $this->isAtLeastAdmin();
            
            // A new token is being opened.
            case 'POST':
                return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
