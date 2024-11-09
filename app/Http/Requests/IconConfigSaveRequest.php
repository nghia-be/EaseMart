<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class IconConfigSaveRequest extends FormRequest
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
            'titleVi'       => 'required|max:50',
            'titleEn'       => 'required|max:50',
            'name'          => 'required|max:50|alpha_underscore',
            'iconsPerRow'   => 'required|numeric|min:0|between:1,4',
            'rowOnPage'     => 'required|numeric|min:0|not_in:0',
            'arrayId'       => 'limit_icon_in_array'
        ];
    }

    public function messages() {
        return [
            'titleVi.max'                   => 'Tên tiếng Việt không được quá 50 ký tự',
            'titleVi.required'              => 'Tên tiếng Việt không được để trống',
            'titleEn.max'                   => 'Tên tiếng Anh không được quá 50 ký tự',
            'titleEn.required'              => 'Tên tiếng Anh không được để trống',
            'name.alpha_underscore'         => 'Tên vị trí - code chỉ được chứa ký tự và dấu gạch nối (_)',
            'name.required'                 => 'Tên vị trí  - code không được để trống',
            'iconsPerRow.required'          => 'Số icon trên 1 dòng không được để trống',
            'iconsPerRow.numeric'           => 'Số icon trên 1 dòng phải là số',
            'iconsPerRow.between'           => 'Số icon trên 1 dòng phải lớn hơn 0 và bé hơn 5',
            'rowOnPage.required'            => 'Số dòng tối đa không được để trống',
            'rowOnPage.required'            => 'Số dòng tối đa không được để trống',
            'rowOnPage.not_in'              => 'Số dòng tối đa phải lớn hơn 0',
            'arrayId.limit_icon_in_array'   => 'Số Icon trong danh sách icon không được lớn hơn số icon tối đa 1 dòng * số dòng tối đa'
        ];
    }
}
