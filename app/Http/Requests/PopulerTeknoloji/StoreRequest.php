<?php

namespace App\Http\Requests\PopulerTeknoloji;

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
            'mesleki_faaliyet' => 'bail|required|max:100',
            'kullanilan_teknolojiler' => 'bail|required|max:255',
            'aciklamalar' => 'bail|nullable|max:255',
        ];
    }
}
