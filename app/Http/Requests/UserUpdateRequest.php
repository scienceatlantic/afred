<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($user = $this->user()) {
            $id = $this->route('user');

            if (filter_var($id, FILTER_VALIDATE_EMAIL)) {
                $userBeingUpdated = User::findByEmailOrFail($id);
            } else {
                $userBeingUpdated = User::findOrFail($id);
            }

            return $user->can('update', $userBeingUpdated);
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
