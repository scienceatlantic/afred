<?php

namespace App\Http\Requests;

abstract class SettingBaseRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function applyRules($type)
    {
        if ($this->method() !== 'PUT') {
            return [];
        }

        switch ($type) {
            case 'INT':
                return ['value' => 'integer'];
            case 'BOOLEAN':
                return ['value' => 'between:0,1'];
            case 'DOUBLE':
                return ['value' => 'numeric'];
            case 'DATE':
                return ['value' => 'date_format:Y-m-d'];
            case 'DATETIME':
                return ['value' => 'date_format:Y-m-d H:i:s'];
            case 'EMAIL':
                return ['value' => 'email'];
            case 'URL':
                return ['value' => 'url'];
            case 'STRING':
                // No break.
            case 'TEXT':
                return [];
            case 'JSON':
                // No break.
            case 'JSONTEXT':
                return ['value' => 'json'];
            default:
                return [
                    'type' => [
                        'required', 
                        'regex:/INT|BOOLEAN|DOUBLE|DATE|DATETIME|EMAIL|URL|STRING|TEXT|JSON|JSONTEXT/'
                    ]
                ];
        }
    }
}
