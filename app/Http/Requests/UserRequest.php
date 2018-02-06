<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (!($user = $this->user())) {
            return false;
        }

        switch ($this->method()) {
            case 'GET':
                if ($id = $this->route('user')) {
                    $user->can('show', User::findOrFail($id));
                }
                return $user->can('index', User::class);
            case 'POST':
                return $user->can('create', User::class);
            case 'PUT':
                $id = $this->route('user');
                return $user->can('update', User::findOrFail($id));
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
