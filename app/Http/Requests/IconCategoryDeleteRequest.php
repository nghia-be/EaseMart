<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class IconCategoryDeleteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    public function getValidatorInstance() {
        $this->getData();
        parent::getValidatorInstance();
    }

    public function getData() {
        $request = $this->request->all();
        $this->merge(json_decode($request['formData'], true));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'arrayId' => [
                function($attribute, $value, $fail) {
                    $arrProdId = explode(',', $value);
                    if(count($arrProdId) > 0) {
                        return $fail("Xin vui lòng bỏ các sản phẩm trong danh sách sản phẩm được chọn trước khi xoá danh mục");
                    }
                }
            ]
        ];
    }
}
