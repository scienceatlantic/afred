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
        // If the request was made with a token, we're going to assume that the
        // request is being made for an edit session. We need to verify that the
        // token is valid (i.e. matches an open facility update link record). If
        // no token was provided with the request, assume that an admin is
        // request the record.
        $token = $this->instance()->input('token', null);
        if ($token) {
            $id = Route::input('facility_repository');
            return FacilityUpdateLink::verifyToken($id, $token);
        }
        return $this->isAtLeastAdmin();
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
