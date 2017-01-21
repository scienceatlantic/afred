<?php

namespace App\Http\Requests;

// Models.
use App\Ilo;

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
            case 'POST':
                $organizationId = $this->instance()->input('organizationId');

                // Make sure organization doesn't already have an ILO. 
                if (Ilo::where('organizationId', $organizationId)->count()) {
                    return false;
                }

                // No break.
            case 'PUT':
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
