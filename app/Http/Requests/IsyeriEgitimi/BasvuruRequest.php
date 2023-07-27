<?php

namespace App\Http\Requests\IsyeriEgitimi;

use Illuminate\Foundation\Http\FormRequest;

class BasvuruRequest extends FormRequest
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
            'baslangic_tarihi' => 'bail|required|date|after:yesterday',
            'bitis_tarihi' => 'bail|required|date|after:baslama_tarihi',
            'cumartesi' => 'bail|required|boolean',
            'firma.id' => 'nullable|integer',
            'firma.adi' => 'bail|required|string|max:50',
            'firma.telefon' => 'nullable|regex:/^(\s)?\d{1}(\()\d{3}(\))(\s)\d{3}(\s)\d{2}(\s)\d{2}$/',
            'firma.email' => 'bail|required|email',
        ];
    }
}
