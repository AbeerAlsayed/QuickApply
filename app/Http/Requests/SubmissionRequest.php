<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmissionRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'company_id' => 'required|exists:companies,id',
            'email' => 'required|email|max:255',
            'description' => 'nullable|string',
            'is_sent' => 'nullable|boolean',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
