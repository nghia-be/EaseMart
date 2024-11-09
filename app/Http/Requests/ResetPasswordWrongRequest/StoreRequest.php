<?php

namespace App\Http\Requests\ResetPasswordWrongRequest;

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
            'numberPhone' => [
                function ($attribute,$value, $fail){
                    $pattern = '/^(03|05|07|08|09)[0-9, ]*$/';
                    if($value == null) {
                        return $fail("Số điện thoại không được bỏ trống!");
                    }
                    if ((strlen($value)!==10)) {
                        return $fail("Số $value phải đúng 10 kí tự");
                    }
                    if(!preg_match($pattern, $value)) {
                        return $fail("Số $value sai định dạng số điện thoại Việt Nam");
                    }
                }
            ]
        ];
    }
}
