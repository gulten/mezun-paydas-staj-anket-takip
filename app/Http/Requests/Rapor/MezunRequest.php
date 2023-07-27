<?php

namespace App\Http\Requests\Rapor;

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
            'start_date' => 'bail|required|date',
            'end_date' => 'bail|required|date',
            'kayit_sekli.*' => 'bail|nullable|string|exists:ogrenciler,kayit_sekli',

        ];
    }
}
