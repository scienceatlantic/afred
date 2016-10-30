<?php

namespace App\Http\Requests;

// Misc.
use Route;

// Models.
use App\Organization;

// Requests.
use App\Http\Requests\Request;

class OrganizationRequest extends Request
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
                $name = $this->instance()->input('name');

                // Make sure organization with identical name doesn't exist.
                if (Organization::where('name', $name)->count()) {
                    return false;
                }

                return $this->isAdmin();
            case 'PUT':
                return $this->isAdmin();
            case 'DELETE':
                $id = Route::input('organizations');
                $o = Organization::findOrFail($id);

                // Make sure organization does not have any facilities.
                if ($o->facilities()->count()) {
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
                // No break.
            case 'POST':
                $r['name'] = 'required';
                $r['isHidden'] = 'required|numeric|between:0,1';
                break;
        }
        return $r;
    }
}
