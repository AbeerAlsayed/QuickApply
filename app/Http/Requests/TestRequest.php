<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {

             return [
                 'position' => 'required|string|max:255',
                 'difficulty' => 'required|string|in:easy,medium,hard',
             ];
    }
}
