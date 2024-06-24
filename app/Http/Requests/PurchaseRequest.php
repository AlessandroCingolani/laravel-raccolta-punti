<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'amount' => ['required', 'numeric', 'max:1000'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Il cliente è un campo obbligatorio.',
            'amount.required' => 'Il prezzo è un campo obbligatorio.',
            'amount.numeric' => 'Il prezzo deve essere un numero.',
            'amount.max' => 'Il prezzo non può superare i :max euro.',
        ];
    }
}
