<?php

namespace App\Http\Requests\PopupPrivateRequest;

use App\Rules\NumberPhoneRule;
use Illuminate\Foundation\Http\FormRequest;

class ImportRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'number_phone' => [
                'required',
                new NumberPhoneRule()
            ]
        ];
    }

    public function messages()
    {
        return [
            'number_phone.required' => 'List SDT không được bỏ trống!'
        ];
    }
}
