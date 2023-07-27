<?php

namespace App\Http\Requests\Mezun;

use Illuminate\Foundation\Http\FormRequest;

class MezunRequest extends FormRequest
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
            'name' => 'bail|required|string',
            'email' => 'bail|required|email',
            'telefon' => 'nullable|regex:/^(\s)?\d{1}(\()\d{3}(\))(\s)\d{3}(\s)\d{2}(\s)\d{2}$/',
            'mezuniyet_tarihi' => 'nullable|date',
            'mezuniyet_suresi' => 'nullable|integer',
        ];
    }
}
