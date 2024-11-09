<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class IconCategorySaveRequest extends FormRequest
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
    public function rules() {
        return [
            'productTitleNameVi'    => 'required|max:50',
            'productTitleNameEn'    => 'max:50|regex:/^[a-zA-Z0-9 ]+$/u',
            'description'           => 'max:120',
        ];
    }

    public function messages() {
        return [
            'productTitleNameVi.required'   => 'Tên danh mục không được để trống',
            'productTitleNameVi.max'        => 'Tên danh mục chỉ giới hạn trong 50 ký tự',
            'productTitleNameEn.max'        => 'Tên danh mục chỉ giới hạn trong 50 ký tự',
            'productTitleNameEn.regex'      => 'Tên tiếng Anh không được nhập ký tự tiếng Việt',
            'description'                   => 'Mô tả chỉ giới hạn trong 120 ký tự'
        ];
    }
}
