<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;

class FormMetricIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($user = $this->user()) {
            return $user->is_at_least_editor;

        // This is a hack to allow local environment development
        } else if (env('APP_ENV') == "local"){
          $user = User::whereEmail('afred@scienceatlantic.ca')->first();
          return $user->is_at_least_editor;
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
