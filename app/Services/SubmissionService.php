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
                $cvFullPath = public_path("storage/" . str_replace('\\', '/', $cvPath));

                Log::info('CV Path: ' . public_path("{$cvFullPath}"));
                $start = microtime(true);

                Notification::send($company, new JobSubmissionNotification($finalDescription, $position, $cvPath));

                $end = microtime(true);
                $timeTaken = $end - $start;

                Log::info("Email sent to {$company->email} in {$timeTaken} seconds.");

                Submission::create([
                    'user_id' => $userId,
                    'company_id' => $company->id,
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
