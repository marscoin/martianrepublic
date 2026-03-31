<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AmendProposalRequest extends FormRequest
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
            'proposalId' => 'required|integer',
            'title' => 'required|string|max:500',
            'description' => 'required|string|max:50000',
            'note' => 'nullable|string|max:1000',
        ];
    }
}
