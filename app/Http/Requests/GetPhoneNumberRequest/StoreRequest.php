<?php

namespace App\Http\Requests\GetPhoneNumberRequest;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
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
            'excel' => 'required_without:show_from,show_to|mimes:xlsx,csv',
            'show_from' => 'nullable',
            'show_to' => 'nullable'
        ];
    }
}
