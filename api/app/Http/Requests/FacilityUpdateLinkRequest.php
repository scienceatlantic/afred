<?php

namespace App\Http\Requests;

// Misc.
use Route;

// Models.
use App\FacilityUpdateLink;

// Requests.
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
            // A new token is being opened.
            case 'POST':
                return true;
            case 'PUT':            
            case 'DELETE':
                // Only allowed to update/delete a record that is 'OPEN'.
                $id = Route::input('facility_update_links');
                $ful = FacilityUpdateLink::findOrFail($id);
                return $ful->status == 'OPEN' && $this->isAdmin();
            default:
                return $this->isAdmin();            
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
            case 'POST':
                $r['facilityId'] = 'required';
                $r['email'] = '';
                $r['isAdmin'] = '';
                break;
            
            case 'PUT':
                $r['status'] = 'regex:/CLOSED/';
        }
        return $r;
    }
}
