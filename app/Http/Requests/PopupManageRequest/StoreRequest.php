<?php

namespace App\Http\Requests\PopupManageRequest;

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
            'templateType' => 'required',
            'titleVi' => 'required',
            'titleEn' => 'required',
            'image_popup_name' => 'required',
            'directionId' => 'required_if:templateType,popup_custom_image_transparent,popup_full_screen',
            'directionUrl' => 'required_if:directionId,1',
            'buttonImage_popup_name' => 'required_if:templateType,popup_custom_image_transparent,popup_full_screen'
        ];
    }
}
