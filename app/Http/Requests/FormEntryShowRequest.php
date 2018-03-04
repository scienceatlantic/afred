<?php

namespace App\Http\Requests;

use App\FormEntry;
use Illuminate\Foundation\Http\FormRequest;

class FormEntryShowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($user = $this->user()) {
            return $user->can(
                'show',
                FormEntry::findOrFail($this->route('entry'))
            );
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
