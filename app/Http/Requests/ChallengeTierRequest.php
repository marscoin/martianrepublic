<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChallengeTierRequest extends FormRequest
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
            'proposedTier' => 'required|in:signal,operational,legislative,constitutional',
            'reason' => 'required|string|max:5000',
        ];
    }
}
