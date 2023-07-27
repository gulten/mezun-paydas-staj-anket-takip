<?php

namespace App\Http\Requests\Belgeler;

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
            'belge_adi' => 'bail|string|required|max:255',
            'belge_aciklama' => 'bail|string|nullable',
            'status' => 'bail|required|string|in:active,passive',
        ];
    }
}
