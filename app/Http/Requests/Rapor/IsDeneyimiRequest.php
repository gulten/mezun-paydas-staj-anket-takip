<?php

namespace App\Http\Requests\Rapor;

use Illuminate\Foundation\Http\FormRequest;

class IsDeneyimiRequest extends FormRequest
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
            'mezun' => 'bail|nullable|integer',
            'paydas' => 'bail|nullable|integer',
            'start_date' => 'bail|required|date',
            'end_date' => 'bail|required|date',
            'devam' => 'bail|required|digits_between:0,1',
        ];
    }
}
