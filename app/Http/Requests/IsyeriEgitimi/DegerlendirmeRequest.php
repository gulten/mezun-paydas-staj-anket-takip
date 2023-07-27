<?php

namespace App\Http\Requests\IsyeriEgitimi;

use Illuminate\Foundation\Http\FormRequest;

class DegerlendirmeRequest extends FormRequest
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
            'sicil_fisi_notu' => 'bail|required|integer|min:0|max:100',
            'dosya_notu' => 'bail|required|integer|min:0|max:100',
            'mulakat_notu' => 'bail|required|integer|min:0|max:100',
            'kabul_edilen_gun_sayisi' => 'bail|required|integer|min:0|max:'. config('global.isyeri.toplam'),
        ];
    }
}
