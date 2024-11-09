<?php

namespace App\Http\Requests\RenderDeeplinkRequest;

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
     * Get the valRenderDeeplinkation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|integer',
            'title' => 'required|string|max:255',
            'dataAction' => 'required|string|max:255',
            'iconUrl' => 'required|string|max:255'
        ];
    }
}
