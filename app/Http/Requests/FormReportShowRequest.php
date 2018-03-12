<?php

namespace App\Http\Requests;

use App\Directory;
use Illuminate\Foundation\Http\FormRequest;

class FormReportShowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $formReport = Directory
            ::findOrFail($this->route('directory'))
            ->forms()
            ->findOrFail($this->route('form'))
            ->formReports()
            ->findOrFail($this->route('report'));

        if ($user = $this->user()) {
            return $user->can('show', $formReport);
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
            'fileType' => ['regex:/(xlsx|xls|csv)/']
        ];
    }
}
