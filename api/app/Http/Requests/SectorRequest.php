<?php

namespace App\Http\Requests;

// Misc.
use Route;

// Models. 
use App\Sector;

class SectorRequest extends Request
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
                $s = Sector::findOrFail(Route::input('sector'));
                
                // Make sure that the sector does not have any facilities.
                if ($s->facilities()->count()) {
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

                // Make sure `name` attribute is unique, unless it's an update
                // request where the attribute has not changed.
                $addCondition = true;
                if ($this->method() == 'PUT') {
                    $id = Route::input('sector');
                    $s = Sector::findOrFail($id);
                    $name = $this->instance()->input('name');
                    $addCondition = $s->name != $name;
                }
                $r['name'] .= $addCondition ? '|unique:sectors' : '';

                break;
        }
        return $r;
    }
}
