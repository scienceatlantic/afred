<?php

namespace App\Http\Requests;

class MiscRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $item = $this->instance()->input('item');

        switch ($item) {
            case 'searchFilters':
                return true;
            case 'facilitiesByEmailWithUnclosedUpdateRequests':
                return true;
            case 'facilityRepositoryBreakdown':
                return $this->isAdmin();
            case 'randomEquipment':
                return true;
            case 'refreshSearchIndices':
                return $this->isAdmin();
            case 'searchIndices':
                return $this->isAdmin();
            default:
                abort(404);
        }
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
