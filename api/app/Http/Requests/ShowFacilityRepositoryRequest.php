<?php

namespace App\Http\Requests;

// Misc.
use Log;
use Route;

// Models.
use App\FacilityRepository;

// Requests.
use App\Http\Requests\Request;

class ShowFacilityRepositoryRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // If both a facility repository ID and token (facility update link) was
        // provided in the request, we're going to assume the user (could be a
        // regular user or an admin) wants to update a particular facility.
        // If a token wasn't provided, we're assuming the admin just wants to
        // view a particular facility. Remember that an admin will also need to
        // generate a token in order to be able to update a particular facility.
        $id = Route::input('facility_repository');
        $token = $this->instance()->input('token', null);
        
        if ($id && $token) {            
            // Find a facility update link record with a matching 'frIdBefore'
            // and 'token' value.
            
            // find or fail?
            $ful = FacilityRepository::find($id)->fulsB()->where('token',
                $token)->first();
            
            return $ful && $ful->status == 'OPEN';
        } else if ($this->_isAtLeastAdmin()) {
            return true;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
