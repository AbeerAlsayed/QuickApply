<?php

namespace App\Services;

use App\Models\Submission;
use App\Models\Company;
use App\Models\User;
use App\Notifications\JobSubmissionNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class SubmissionService
{
    public function notifyCompanies($userId, $countryId, $cvFile, $description, $position)
    {
        return DB::transaction(function () use ($userId, $countryId, $cvFile, $description, $position) {
            $user = User::findOrFail($userId); // جلب المستخدم
            $companies = Company::where('country_id', $countryId)->get();

            $cvPath = $cvFile->store('cvs', 'public'); // حفظ السيرة الذاتية

            $notifiedCompanies = [];

            foreach ($companies as $company) {
                // تحقق إذا تم الإرسال مسبقًا
                if (Submission::where('user_id', $userId)
                    ->where('company_id', $company->id)
                    ->where('is_sent', true)
                    ->exists()) {
                    continue;
                }

                // إنشاء وصف ديناميكي إذا لم يتم تقديمه
                $finalDescription = isset($description) ? $description : "Dear {$company->name}, I am interested in the {$position} role and would like to apply.";

                // تسجيل وقت البدء
                $start = microtime(true);

                // إرسال الإشعار عبر البريد الإلكتروني
                Notification::send($company, new JobSubmissionNotification($finalDescription, $position, $cvPath, $user->email));

                // تسجيل وقت الانتهاء
                $end = microtime(true);

                // حساب الزمن المستغرق
                $timeTaken = $end - $start;

                // تسجيل الزمن في الـ Logs
                Log::info("Email sent to {$company->email} in {$timeTaken} seconds.");

                // إنشاء سجل في جدول الطلبات
                Submission::create([
                    'user_id' => $userId,
                    'company_id' => $company->id,
                    'cv' => $cvPath,
                    'description' => $finalDescription,
                    'position' => $position,
                    'is_sent' => true,
                ]);

                $notifiedCompanies[] = [
                    'email' => $company->email,
                    'time_taken' => $timeTaken,
                ];
            }

            return $notifiedCompanies;
        });
    }
}
