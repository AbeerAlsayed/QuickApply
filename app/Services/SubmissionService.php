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
    public function notifyCompanies($userId, $countryId)
    {
//        $user = User::findOrFail($userId);
//        $cvPath = $user->cv;
//        $position = $user->position;
//        $email = $user->email;
//
//        $cvPath = 'cvs/' . basename($cvPath);
//        $cvStoragePath = "public/{$cvPath}";
//
//        // تحويل جميع الفواصل إلى الاتجاه الصحيح بناءً على نظام التشغيل
//        $cvFullPath = storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $cvPath);
//        $cvFullPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $cvFullPath); // توحيد الفواصل
//
////        $cvFullPath = storage_path("app/" . str_replace('\\', '/', $cvPath));
//        if (file_exists($cvFullPath)) {
//            Log::info("CV file found at: " . $cvFullPath);
//        } else {
//            Log::error("CV file not found at: " . $cvFullPath);
//        }
//        dd($cvFullPath);
        return DB::transaction(function () use ($userId, $countryId) {
            $user = User::findOrFail($userId);
            $companies = Company::where('country_id', $countryId)->get();

            $notifiedCompanies = [];

            foreach ($companies as $company) {
                if (Submission::where('user_id', $userId)
                    ->where('company_id', $company->id)
                    ->where('is_sent', true)
                    ->exists()) {
                    continue;
                }

                $finalDescription = $user->message;
                $cvPath = $user->cv;
                $position = $user->position;
                $email = $user->email;


                Notification::send($company, new JobSubmissionNotification($finalDescription, $position, $cvPath));


                Submission::create([
                    'user_id' => $userId,
                    'company_id' => $company->id,
                    'is_sent' => true,
                ]);

                $notifiedCompanies[] = [
                    'email' => $company->email,
                ];
            }

            return $notifiedCompanies;
        });
    }
}
