<?php

namespace App\Http\Requests\DersGereklilik;

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
            'gereklilik' => 'bail|nullable|integer|max:5',
            'sebep' => 'bail|string|nullable|max:255'
        ];
    }
}
