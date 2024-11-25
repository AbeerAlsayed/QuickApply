<?php
namespace App\Services;

use App\Models\Submission;
use App\Models\Company;
use App\Notifications\JobSubmissionNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SubmissionService
{
    public function notifyCompanies($countryId, $cvFile, $description, $position)
    {
        return DB::transaction(function () use ($countryId, $cvFile, $description, $position) {
            $companies = Company::where('country_id', $countryId)->get();

            $cvPath = $cvFile->store('cvs', 'public'); // حفظ السيرة الذاتية

            $notifiedCompanies = [];

            foreach ($companies as $company) {
                // تحقق إذا تم الإرسال مسبقًا
                if (Submission::where('company_id', $company->id)
                    ->where('is_sent', true)
                    ->exists()) {
                    continue;
                }

                // إنشاء وصف ديناميكي إذا لم يتم تقديمه
                $finalDescription = $description ?? "Dear {$company->name}, I am interested in the {$position} role and would like to apply.";

                // إرسال الإشعار عبر البريد الإلكتروني
                $company->notify(new JobSubmissionNotification($finalDescription, $position, $cvPath));

                // إنشاء سجل في جدول الطلبات
                Submission::create([
                    'user_id' => auth()->id(),
                    'company_id' => $company->id,
                    'email' => $company->email,
                    'cv' => $cvPath,
                    'description' => $finalDescription,
                    'position' => $position,
                    'is_sent' => true,
                ]);

                $notifiedCompanies[] = $company->email;
            }

            return $notifiedCompanies;
        });
    }
}
