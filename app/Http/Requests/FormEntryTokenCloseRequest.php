<?php

namespace App\Http\Requests;

use App\Directory;
use Illuminate\Foundation\Http\FormRequest;

class FormEntryTokenCloseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $token = Directory
            ::findOrFail($this->route('directory'))
            ->forms()
            ->findOrFail($this->route('form'))
            ->formEntries()
            ->findOrFail($this->route('entry'))
            ->tokens()
            ->findOrFail($this->route('token'));

        if ($user = $this->user()) {
            return $user->can('closeToken', $token);
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
