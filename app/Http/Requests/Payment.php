<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Payment extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount' => 'required|integer|min:1',
            'currency' => 'required|in:EUR,USD', //ISO 4217
        ];
    }
}
