<?php

namespace App\Http\Requests\IsDeneyimi;

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
            'kurum' => 'bail|required|string',
            'birim' => 'bail|required|string',
            'gorev' => 'bail|required|string',
            'maas' => 'bail|required|integer',
            'baslama_tarihi' => 'bail|required|date',
            'ayrilma_tarihi' => 'bail|nullable|date|after:baslama_tarihi',
        ];
    }
}
