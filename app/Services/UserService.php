<?php

namespace App\Services;

use GuzzleHttp\Client;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http; // لاستخدام API

class UserService
{


    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            // تشفير كلمة المرور
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                throw new \Exception('Password is required.');
            }

            // معالجة السيرة الذاتية إذا كانت موجودة
            if (isset($data['cv']) && $data['cv']->isValid()) {
                $data['cv'] = $data['cv']->store('cvs', 'public');
            }

            // إذا لم يكن هناك رسالة، نقوم بتوليد واحدة باستخدام OpenRouter API
            if (empty($data['message'])) {
                $data['message'] = $this->generateAIMessage($data);
            }

            // إنشاء المستخدم في قاعدة البيانات
            return User::create($data);
        });
    }

    private function generateAIMessage(array $data): string
    {
        $apiKey = env('OPENROUTER_API_KEY');
        if (empty($apiKey)) {
            Log::error("OpenRouter API Key is missing.");
            return "API Key is missing.";
        }

        // تأكد من أن 'skills' هي مصفوفة وإذا كانت سلسلة نصية، حولها إلى مصفوفة
        $skills = is_array($data['skills']) ? $data['skills'] : explode(',', $data['skills']);

        $prompt = "Generate a professional job application message for the following user:\n" .
            "Name: {$data['name']}\n" .
            "Education: {$data['education']}\n" .
            "Experience: {$data['experience']}\n" .
            "Skills: " . implode(', ', $skills) . "\n" .
            "Position: {$data['position']}\n";

        try {
            // زيادة المهلة إلى 30 ثانية
            $response = Http::timeout(30)->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
            ])->post('https://openrouter.ai/api/v1/chat/completions', [
                'model'    => 'deepseek/deepseek-r1:free',
                'messages' => [['role' => 'user', 'content' => $prompt]],
            ]);

            Log::debug("OpenRouter Response: " . $response->body()); // طباعة الاستجابة لتتبع الأخطاء

            if ($response->failed()) {
                Log::error("Error connecting to OpenRouter API: " . $response->body());
                return "Error connecting to OpenRouter API.";
            }

            $result = $response->json();

            // تحقق مما إذا كان الرد يحتوي على البيانات المتوقعة
            if (!isset($result['choices'][0]['message']['content'])) {
                Log::error("Unexpected API response structure: " . json_encode($result));
                return "Unexpected API response.";
            }

            return $result['choices'][0]['message']['content'];

        } catch (\Exception $e) {
            Log::error("Exception in OpenRouter API call: " . $e->getMessage());
            return "An error occurred while generating the message.";
        }
    }


    public function update(User $user, array $data)
    {
        return DB::transaction(function () use ($user, $data) {
            // تحديث كلمة المرور فقط إذا تم تمريرها
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']); // لا تقم بتحديث كلمة المرور إذا لم يتم إرسالها
            }

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
