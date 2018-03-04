<?php

namespace App\Http\Requests;

use App\FormEntryStatus as Status;
use Illuminate\Foundation\Http\FormRequest;

class FormEntryStatusIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($user = $this->user()) {
            return $user->can('index', Status::class);
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
