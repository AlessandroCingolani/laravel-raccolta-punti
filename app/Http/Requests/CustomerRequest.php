<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:2', 'max:50'],
            'email' => ['required', 'unique:customers,email', 'string', 'lowercase', 'email', 'max:255']
        ];
    }

    public function messages()
    {
        return [
            "name.required" => "Il nome è un campo obbligatorio.",
            "name.max" => "Il nome deve essere massimo :max caratteri",
            "name.min" => "Il nome deve avere minimo :min caratteri",
            "email.required" => "L'email è un campo obbligatorio.",
            "email.unique" => "L'email è già stata utilizzata.",
            "email.max" => "L'email deve essere massimo :max caratteri",
            "email.email" => "L'email deve essere valida!",
        ];
    }
}