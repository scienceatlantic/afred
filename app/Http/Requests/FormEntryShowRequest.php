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
        $formEntry = FormEntry::findOrFail($this->route('entry'));

        if ($tokenValue = $this->get('token')) {
            if ($token = $formEntry->tokens()->open()->first()) {
                return $token->value === $tokenValue;
            }

            return false;
        }

        if ($user = $this->user()) {
            return $user->can('show', $formEntry);
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
