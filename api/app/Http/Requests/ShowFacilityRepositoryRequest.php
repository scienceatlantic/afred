<?php

namespace App\Http\Requests;

// Misc.
use Log;
use Route;

// Models.
use App\FacilityUpdateLink;

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
        // An admin is allowed to view a facility repository record. However,
        // given the right id and token, an unauthenticated user (or a user
        // other than an admin) would also be allowed to view the facility
        // record (ie. for edits).
        if ($this->_isAdmin()) {
            return true;
        } else {
            // Gets the ID from the route. The name 'facility_repository' to
            // refer to the ID parameter was create by Laravel. You can view
            // this by running 'php artisan route:list' in the terminal.
            $frId = Route::input('facility_repository');
            $token = $this->instance()->input('token', null);
            
            // Find a record with a matching token and frIdBefore value.
            $ful = FacilityUpdateLink::where('frIdBefore', $frId)
                ->where('token', $token)->first();
            
            return $ful && $ful->status == 'OPEN';
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
