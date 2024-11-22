<?php

namespace App\Services;

use App\Models\Submission;
use Illuminate\Support\Facades\DB;

class SubmissionService
{
    public function create(array $data): Submission
    {
        return DB::transaction(function () use ($data) {
            return Submission::create([
                'user_id' => $data['user_id'],
                'company_id' => $data['company_id'],
                'email' => $data['email'],
                'description' => $data['description'] ?? 'No description provided',
                'is_sent' => false,
            ]);
        });
    }

    public function sendEmailAndUpdateStatus(Submission $submission)
    {
        // إرسال الإيميل هنا
        // بعد إرسال الإيميل نحدث حالة الطلب
        $submission->update(['is_sent' => true]);
    }
}
