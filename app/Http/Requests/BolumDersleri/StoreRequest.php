<?php

namespace App\Http\Requests\BolumDersleri;

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
            'sinif' => 'bail|required|string|max:8|in:Hazırlık,1,2,3,4',
            'donem' => 'bail|required|string|in:Güz,Bahar',
            'durum' => 'bail|required|string|in:Zorunlu,Seçmeli',
            'ders_kodu' => 'bail|required|string',
            'ders_adi' => 'bail|required|string',
            'haftalik_ders_saati' => 'bail|nullable|string',
            'amac' => 'nullable|string',
            'icerik' => 'nullable|string',
        ];
    }
}
