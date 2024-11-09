<?php

namespace App\Http\Requests\BannerManageRequest;

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
            'titleVi'  =>'required',
            'titleEn'  =>'required',
            'bannerType' =>'required',
            'objects'    =>'required',
            'objectType'=>'required',
            'show_from' =>'required|date_multi_format:"Y-m-d H:i:s","Y-m-d H:i"',
            'show_to'   =>'required|date_multi_format:"Y-m-d H:i:s","Y-m-d H:i"|after:show_from',
        ];
    }
}
