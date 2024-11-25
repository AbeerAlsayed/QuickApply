<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $userId = $this->route('user') ? $this->route('user')->id : null;

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userId, // استثناء البريد الإلكتروني الحالي
            'password' => 'nullable|string|min:8', // اجعل كلمة المرور nullable أثناء التحديث
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'position' => 'nullable|string',
            'description' => 'nullable|string',
        ];
    }

}
