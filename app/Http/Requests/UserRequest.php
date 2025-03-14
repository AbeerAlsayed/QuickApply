<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        // السماح فقط للمستخدم نفسه أو للمسؤول بإجراء التعديلات
//        return auth()->check() && (auth()->id() === optional($this->route('user'))->id || auth()->user()->isAdmin());
        return true;
    }

    public function rules()
    {
        $userId = $this->route('user') ? $this->route('user')->id : null;

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userId,
            'password' => $userId ? 'nullable|string|min:8' : 'required|string|min:8', // كلمة المرور مطلوبة عند الإنشاء فقط
            'education' => 'nullable|string|max:255',
            'experience' => 'nullable|string|max:500',
            'skills' => 'nullable|string|max:500',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'position' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:1000',
        ];
    }
}
