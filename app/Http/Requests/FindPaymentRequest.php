<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FindPaymentRequest extends FormRequest
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
            'phone' => [
                function ($attribute,$value, $fail){
                    $pattern = '/^(03|05|06|07|08|09)[0-9, ]*$/';
                    if ((strlen($value)!==10)) {
                        return $fail("Trường $value phải đúng 10 kí tự");
                    }
                    if(!preg_match($pattern, $value)) {
                        return $fail("Trường $value sai định dạng số điện thoại Việt Nam");
                    }
                }
            ],
            'fromDate' => 'required',
            'toDate' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'fromDate.required' => 'Ngày bắt đầu là bắt buộc',
            'toDate.required' => 'Ngày kết thúc là bắt buộc',
        ];
    }
}
