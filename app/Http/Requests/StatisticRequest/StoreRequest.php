<?php

namespace App\Http\Requests\StatisticRequest;

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
            'show_from'   =>'nullable|date_format:Y-m-d\TH:i',
            'show_to'   =>'nullable|date_format:Y-m-d\TH:i',
        ];
    }
}
