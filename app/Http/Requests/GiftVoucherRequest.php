<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GiftVoucherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:0',
            'recipient_first_name' => 'required|string|max:255',
            'recipient_last_name' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'Il campo importo è obbligatorio.',
            'amount.numeric' => 'Il campo importo deve essere un numero.',
            'amount.min' => 'L\'importo non può essere negativo.',
            'recipient_first_name.required' => 'Il nome del destinatario è obbligatorio.',
            'recipient_first_name.string' => 'Il nome del destinatario deve essere una stringa.',
            'recipient_first_name.max' => 'Il nome del destinatario non può superare i 255 caratteri.',
            'recipient_last_name.required' => 'Il cognome del destinatario è obbligatorio.',
            'recipient_last_name.string' => 'Il cognome del destinatario deve essere una stringa.',
            'recipient_last_name.max' => 'Il cognome del destinatario non può superare i 255 caratteri.',
        ];
    }
}
