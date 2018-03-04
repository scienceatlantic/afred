<?php

namespace App\Http\Requests;

use App\Directory;
use App\FormEntryToken as Token;
use App\User;
use Illuminate\Foundation\Http\FormRequest;

class FormEntryTokenOpenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (!$user = User::findByEmail($this->get('email')) ?: $this->user()) {
            abort(400);
        }

        $formEntry = Directory
            ::findOrFail($this->route('directory'))
            ->forms()
            ->findOrFail($this->route('form'))
            ->formEntries()
            ->findOrFail($this->route('entry'));

        return $user->can('openToken', $formEntry);
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
