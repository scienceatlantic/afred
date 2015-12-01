<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateFacilityRevisionHistoryRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|exists:facility_revision_history,id',
            'state' => 'regex:/'
                     . 'PUBLISHED|'
                     . 'REJECTED|'
                     . 'EDIT_DRAFT|'
                     . 'PENDING_EDIT_APPROVAL|'
                     . 'REJECTED_EDIT/'
        ];
    }
}
