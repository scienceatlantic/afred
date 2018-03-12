<?php

namespace App\Http\Requests;

use App\Directory;
use Illuminate\Foundation\Http\FormRequest;

class FormEntryIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $directory = Directory::findOrFail($this->route('directory'));

        if ($user = $this->user()) {
            return $user->can('indexFormEntries', $directory);
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
