<?php

namespace App\Http\Requests;

// Misc.
use Route;

// Models.
use App\Organization;

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
                // No break.
            case 'PUT':
                return $this->isAdmin();
            case 'DELETE':
                $id = Route::input('organization');
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

                // Make sure `name` attribute is unique, unless it's an update
                // request where the attribute has not changed.
                $addCondition = true;
                if ($this->method() == 'PUT') {
                    $id = Route::input('organization');
                    $o = Organization::findOrFail($id);
                    $name = $this->instance()->input('name');
                    $addCondition = $o->name != $name;
                }
                $r['name'] .= $addCondition ? '|unique:organizations' : '';

                break;
        }
        return $r;
    }
}
