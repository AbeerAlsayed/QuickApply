<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'country_id' => 'required|exists:countries,id',
            'positions' => 'nullable|array',
            'positions.*.title' => 'required|string|max:255',
        ];
    }
}

