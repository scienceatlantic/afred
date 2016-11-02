<?php

namespace App\Http\Requests;

// Misc.
use Route;

// Models.
use App\Discipline;

// Requests.
use App\Http\Requests\Request;

class DisciplineRequest extends Request
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
                $id = Route::input('discipline');
                $d = Discipline::findOrFail($id);

                // Make sure it does not have any facilities.
                if ($d->facilities()->count()) {
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
                    $id = Route::input('discipline');
                    $d = Discipline::findOrFail($id);
                    $name = $this->instance()->input('name');
                    $addCondition = $d->name != $name;
                }
                $r['name'] .= $addCondition ? '|unique:disciplines' : '';

                break;
        }
        return $r;
    }
}
