<?php

namespace App\Http\Requests\PopupPrivateRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'type' => 'required',
            'actionType' => 'required',
            'dataAction' => 'required',
            'iconUrl' => 'required',
            'timeline' => 'required',
            'iconButtonUrl' => 'required_if:type,popup_custom_image_transparent,popup_full_screen',
            'id' => 'required',
            'popupGroupId' => 'required',
            'temPerId' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'type.required'         => 'Loại popup không được bỏ trống!',
            'iconUrl.required'      => 'Ảnh popup không được bỏ trống!',
            'timeline.required'     => 'Thời gian hiển thị không được bỏ trống!',
            'dataAction.required'   => 'URL điều hướng không được bỏ trống!',
            'actionType.required'   => 'Nơi điều hướng không được bỏ trống!',
            'iconButtonUrl.required_if' => 'Ảnh nút điều hướng không được bỏ trống!',
        ];
    }
}
