<?php

namespace App\Http\Requests\ScreenRequest;

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
            'screenId'      =>'required|unique:screen',
            'screenName'    =>'required',
            'typeLog'       =>'nullable',
            'api_url'       =>'nullable',
            'image'         =>'nullable',
            'example_code'  =>'nullable',
            'status'        =>'nullable'
        ];
    }
}
