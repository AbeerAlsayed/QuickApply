<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserService
{
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            // رفع ملف السيرة الذاتية إذا تم تقديمه
            if (isset($data['cv'])) {
                $data['cv'] = $data['cv']->store('cvs', 'public');
            }

            return User::create($data);
        });
    }

    public function update(User $user, array $data)
    {
        return DB::transaction(function () use ($user, $data) {
            // إذا كان هناك ملف جديد في البيانات
            if (array_key_exists('cv', $data) && $data['cv'] instanceof \Illuminate\Http\UploadedFile) {
                // حذف الملف القديم إذا كان موجودًا
                if ($user->cv) {
                    Storage::disk('public')->delete($user->cv);
                }

                // رفع الملف الجديد وتخزين مساره
                $data['cv'] = $data['cv']->store('cvs', 'public');
            }

            // تحديث بيانات المستخدم
            $user->update($data);

            return $user;
        });
    }

    public function delete(User $user)
    {
        return DB::transaction(function () use ($user) {
            // حذف ملف السيرة الذاتية إذا كان موجودًا
            if ($user->cv) {
                Storage::disk('public')->delete($user->cv);
            }

            return $user->delete();
        });
    }
}
