<?php

namespace App\Http\Requests\Anket;

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
            'id' => 'required|integer',
            'anket_sorular.*.id' => 'nullable|integer',
            'anket_sorular.*.soru' => 'required|string',
            'anket_sorular.*.soru_tipi' => 'required|string',
            'anket_sorular.*.detay' => 'nullable|array',
            'anket_sorular.*.detay.tip' => 'nullable|string',
            'anket_sorular.*.detay.secenek' => 'nullable|string',
        ];
    }
}
