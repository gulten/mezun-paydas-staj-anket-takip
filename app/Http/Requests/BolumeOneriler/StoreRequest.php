<?php

namespace App\Http\Requests\BolumeOneriler;

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
            'ders_id' => 'bail|required|integer',
            'amaca_yonelik' => 'bail|nullable|max:1000',
            'icerige_yonelik' => 'bail|nullable|max:1000',
            'ders_yariyilina_saatine_yonelik' => 'bail|nullable|max:1000',
            'diger' => 'bail|nullable|max:255',
        ];
    }
}
