<?php

namespace App\Http\Requests;

use App\User;
use App\Directory;
use Illuminate\Foundation\Http\FormRequest;

class FormIndexRequest extends FormRequest
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
            return $user->can('indexForms', $directory);

        // This is a hack to allow local environment development
        } else if (env('APP_ENV') == "local"){
            $user = User::whereEmail('afred@scienceatlantic.ca')->first();
            return $user->can('indexForms', $directory);
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
