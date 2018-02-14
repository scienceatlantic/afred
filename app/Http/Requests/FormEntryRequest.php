<?php

namespace App\Http\Requests;

use App\FormEntry;
use Illuminate\Foundation\Http\FormRequest;

class FormEntryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // TODO for seeders
        if (!$user = $this->user()) {
            return false;
        }

        switch ($this->method()) {
            case 'GET':
                if ($id = $this->route('entry')) {
                    return $user->can('show', FormEntry::findOrFail($id));
                }
                return $user->can('index', FormEntry::class);
        }
        return false;
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
