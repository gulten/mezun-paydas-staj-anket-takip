<?php

namespace App\Http\Requests\Rapor;

use Illuminate\Foundation\Http\FormRequest;

class BolumeOneriRequest extends FormRequest
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
            'ders_id' => 'bail|nullable|integer',
            'donem' => 'bail|nullable|string',
            'durum' => 'bail|nullable|string',
            'sinif' => 'bail|nullable|string',
            'skor_alt' => 'bail|required_with:skor_ust|integer|min:1|max:5',
            'skor_ust' => 'bail|required_with:skor_alt|integer|min:1|max:5',
        ];
    }
}
