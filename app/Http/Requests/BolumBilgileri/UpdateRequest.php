<?php

namespace App\Http\Requests\BolumBilgileri;

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
            'universite_adi' => 'required|string|max:100',
            'fakulte_adi' => 'required|string|max:100',
            'adi' => 'required|string|max:100',
            'kurulus_yili' => 'required|date',
            'faaliyet_baslangic_tarihi' => 'required|date',
            'adres' => 'required|string|max:200',
            'telefon' => 'required|regex:/^(\s)?\d{1}(\()\d{3}(\))(\s)\d{3}(\s)\d{2}(\s)\d{2}$/',
            'email' => 'required|email',
        ];
    }
}
