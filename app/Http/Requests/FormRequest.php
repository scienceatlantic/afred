<?php

namespace App\Http\Requests;

use App\Form;
use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;

class FormRequest extends BaseFormRequest
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
                if ($formId = $this->route('form')) {
                    return true;
                }
                if ($user = $this->user()) {
                    return $user->can('index', Form::class);
                }
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
