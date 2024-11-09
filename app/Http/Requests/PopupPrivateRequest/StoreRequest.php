<?php

namespace App\Http\Requests\PopupPrivateRequest;

use App\Rules\NumberPhoneRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * @var int
     */
    private $limit;
    /**
     * @var string
     */
    private $email;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function __construct()
    {
        $this->limit = LIMIT_PHONE;
        $this->email = EMAIL_FTEL_PHONE;
    }
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
            'type'      => 'required',
            'iconUrl' => 'required',
            'timeline' => 'required',
            'dataAction' => 'required',
            'actionType' => 'required',
            'iconButtonUrl' => 'required_if:type,popup_custom_image_transparent,popup_full_screen',
//            'number_phone' => [
//                'required',
//                new NumberPhoneRule()
//            ]
        ];
    }

    public function messages()
    {
        return [
            'type.required'         => 'Loại popup không được bỏ trống!',
//            'number_phone.required' => 'List SDT không được bỏ trống!',
            'iconUrl.required'      => 'Ảnh popup không được bỏ trống!',
            'timeline.required'     => 'Thời gian hiển thị không được bỏ trống!',
            'dataAction.required'   => 'URL điều hướng không được bỏ trống!',
            'actionType.required'   => 'Nơi điều hướng không được bỏ trống!',
            'iconButtonUrl.required_if' => 'Ảnh nút điều hướng không được bỏ trống!',
        ];
    }
}
