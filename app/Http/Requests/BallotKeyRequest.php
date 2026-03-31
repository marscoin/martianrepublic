<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BallotKeyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'proposal_id' => 'required|integer',
            'encrypted_key' => 'required|string|max:2000',
            'encryption_iv' => 'nullable|string|max:500',
            'hidden_target' => 'nullable|string|max:200',
        ];
    }
}
