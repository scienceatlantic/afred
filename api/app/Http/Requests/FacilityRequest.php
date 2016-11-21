<?php

namespace App\Http\Requests;

// Misc.
use Route;

// Models.
use App\Facility;

// Requests.
use App\Http\Requests\Request;

class FacilityRequest extends Request
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
            case 'PUT':
                return $this->isAdmin();
            case 'DELETE':
                $f = Facility::findOrFail(Route::input('facility'));
                $fr = $f->currentRevision;
                
                // Make sure facility does not have any open/pending update 
                // requests.
                if ($fr->updateRequests()->notClosed()->count()) {
                    return false;
                }

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
                $r['isPublic'] = 'required|numeric|between:0,1';
                break;
        }
        return $r;
    }
}
