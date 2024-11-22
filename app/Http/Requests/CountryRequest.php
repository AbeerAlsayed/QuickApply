<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CountryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:countries,name,' . $this->country,
            'code' => 'required|string|max:5|unique:countries,code,' . $this->country,
        ];
    }
}
