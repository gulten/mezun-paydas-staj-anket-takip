<?php

namespace App\Http\Requests\Anket;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            'anket_sorular.*.id' => 'required|integer',
            'anket_sorular.*.cevap' => 'required_if:anket_sorular.*.required,1',
            'anket_sorular.*.digerCevap' => 'nullable|string',
            'anket_sorular.*.required' => 'required|numeric|in:0,1',
        ];
    }
}
