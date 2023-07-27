<?php

namespace App\Http\Requests\Ogrenci;

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
            'status' => 'bail|required|string|in:active,passive',
            'name' => 'bail|required|string',
            'email' => 'bail|required|email|unique:users',
            'password' => 'bail|required|string|min:8|confirmed',
            'ogrenci_no' => 'nullable|integer',
            'kayit_sekli' => 'nullable|string',
            'kayit_yili' => 'nullable|date',
            'telefon' => 'nullable|regex:/^(\s)?\d{1}(\()\d{3}(\))(\s)\d{3}(\s)\d{2}(\s)\d{2}$/',
            'adres' => 'nullable|string|max:100',
        ];
    }
}