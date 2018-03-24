<?php

namespace App\Http\Requests;

use App\FormEntry;
use Illuminate\Foundation\Http\FormRequest;
use Log;

class FormEntryActionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $action = $this->get('action');
        
        switch ($action) {
            case 'submit':
                $isEdit = (bool) $this->route('entry');
                if (!$isEdit) {
                    return true;
                }

                $formEntry = FormEntry::findOrFail($this->route('entry'));

                if (!$token = $formEntry->tokens()->open()->first()) {
                    $msg = 'Attempting to update a form entry that doesn\'t '
                         . ' an open token';
                    Log::warning($msg, [
                        'formEntry' => $formEntry->toArray()
                    ]);
                    return false;
                }

                if (!$token->value === $this->get('token')) {
                    $msg = 'Attempting to update a form entry using invalid '
                         . 'token';
                    Log::warning($msg, [
                        'formEntry'          => $formEntry->toArray(),
                        'tokenValue'         => $token->value,
                        'providedTokenValue' => $this->get('token')
                    ]);
                    return false;
                }

                return true;
            case 'publish':
            case 'reject':
            case 'delete':
            case 'hide':
            case 'unhide':
                if (!$user = $this->user()) {
                    return false;
                }

                return $user->can(
                    $action,
                    FormEntry::findOrFail($this->route('entry'))
                );                
        }
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
