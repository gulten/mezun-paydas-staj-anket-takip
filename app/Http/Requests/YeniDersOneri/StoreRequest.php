<?php

namespace App\Http\Requests\YeniDersOneri;

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
            'ders_adi' => 'bail|required|max:100',
            'icerik' => 'bail|required|max:255',
            'on_sartlar' => 'bail|nullable|max:255',
            'kaynaklar' => 'bail|nullable|max:255',
            'aciklamalar' => 'bail|nullable|max:255',
        ];
    }
}
